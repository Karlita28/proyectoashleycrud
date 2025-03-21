<?php
include 'Conexion.php'; // Incluye la conexión a la base de datos

// Verifica si se ha enviado un parámetro 'id'
if (isset($_GET['id'])) {
    $id_compra = $_GET['id'];

    // Obtener la información actual de la compra
    $sql = "SELECT * FROM compra WHERE ID_Compra = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_compra);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si la compra existe, se obtiene la información
    if ($result->num_rows > 0) {
        $compra = $result->fetch_assoc();
    } else {
        echo "Compra no encontrada.";
        exit();
    }
} else {
    echo "ID de compra no proporcionado.";
    exit();
}

// Verifica si se ha enviado el formulario para actualizar los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los datos del formulario
    $id_proveedor = $_POST['id_proveedor'];
    $fecha_compra = $_POST['fecha_compra'];
    $numero_factura = $_POST['numero_factura'];
    $monto_total = $_POST['monto_total'];
    $metodo_pago = $_POST['metodo_pago'];
    $fecha_entrega = $_POST['fecha_entrega'];
    $estado_compra = $_POST['estado_compra'];
    $validacion_recepcion = $_POST['validacion_recepcion'];

    // Verifica si los campos no están vacíos
    if (!empty($id_proveedor) && !empty($fecha_compra) && !empty($numero_factura) && !empty($monto_total) && !empty($metodo_pago) && !empty($fecha_entrega) && !empty($estado_compra)) {
        // Actualiza la información de la compra
        $sql_update = "UPDATE compra 
                       SET ID_Proveedor = ?, FechaCompra = ?, NumeroFactura = ?, MontoTotal = ?, MetodoPago = ?, FechaEstimadaEntrega = ?, EstadoCompra = ?, ValidacionRecepcion = ?
                       WHERE ID_Compra = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("issdisssi", $id_proveedor, $fecha_compra, $numero_factura, $monto_total, $metodo_pago, $fecha_entrega, $estado_compra, $validacion_recepcion, $id_compra);

        if ($stmt_update->execute()) {
            header("Location: Index.php?mensaje=Compra actualizada con éxito");
            exit();
        } else {
            echo "Error: " . $stmt_update->error;
        }

        $stmt_update->close();
    } else {
        echo "Por favor, complete todos los campos.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Compra</title>
    <link rel="stylesheet" href="style.css"> <!-- Si tienes un archivo CSS -->
</head>
<body>
    <h1>Actualizar Compra</h1>

    <!-- Formulario para actualizar los datos de la compra -->
    <form action="Actualizar.php?id=<?php echo $compra['ID_Compra']; ?>" method="POST">
        <label for="id_proveedor">ID Proveedor:</label>
        <input type="text" name="id_proveedor" value="<?php echo $compra['ID_Proveedor']; ?>" required>

        <label for="fecha_compra">Fecha de Compra:</label>
        <input type="date" name="fecha_compra" value="<?php echo $compra['FechaCompra']; ?>" required>

        <label for="numero_factura">Número de Factura:</label>
        <input type="text" name="numero_factura" value="<?php echo $compra['NumeroFactura']; ?>" required>

        <label for="monto_total">Monto Total:</label>
        <input type="number" step="0.01" name="monto_total" value="<?php echo $compra['MontoTotal']; ?>" required>

        <label for="metodo_pago">Método de Pago:</label>
        <input type="text" name="metodo_pago" value="<?php echo $compra['MetodoPago']; ?>" required>

        <label for="fecha_entrega">Fecha Estimada de Entrega:</label>
        <input type="date" name="fecha_entrega" value="<?php echo $compra['FechaEstimadaEntrega']; ?>" required>

        <label for="estado_compra">Estado de la Compra:</label>
        <select name="estado_compra" required>
            <option value="Entregada" <?php echo ($compra['EstadoCompra'] == 'Entregada') ? 'selected' : ''; ?>>Entregada</option>
            <option value="Pendiente" <?php echo ($compra['EstadoCompra'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
            <option value="En tránsito" <?php echo ($compra['EstadoCompra'] == 'En tránsito') ? 'selected' : ''; ?>>En tránsito</option>
            <option value="Cancelada" <?php echo ($compra['EstadoCompra'] == 'Cancelada') ? 'selected' : ''; ?>>Cancelada</option>
        </select>

        <label for="validacion_recepcion">Validación de Recepción:</label>
        <input type="number" name="validacion_recepcion" value="<?php echo $compra['ValidacionRecepcion']; ?>" required>

        <button type="submit">Actualizar Compra</button>
    </form>

    <a href="Index.php">Volver a la lista de compras</a>
</body>
</html>