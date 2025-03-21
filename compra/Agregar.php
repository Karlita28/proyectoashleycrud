<?php
include 'Conexion.php'; // Incluye la conexión a la base de datos

// Verifica si se ha enviado el formulario
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

    // Aquí puedes procesar los datos, como insertarlos en la base de datos
}

    // Verifica si los campos no están vacíos
    if (!empty($id_proveedor) && !empty($fecha_compra) && !empty($numero_factura) && !empty($monto_total) && !empty($metodo_pago) && !empty($fecha_entrega) && !empty($estado_compra)) {
        // Inserta la nueva compra en la base de datos
        $sql_insert = "INSERT INTO compra (ID_Proveedor, FechaCompra, NumeroFactura, MontoTotal, MetodoPago, FechaEstimadaEntrega, EstadoCompra, ValidacionRecepcion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("issdisss", $id_proveedor, $fecha_compra, $numero_factura, $monto_total, $metodo_pago, $fecha_entrega, $estado_compra, $validacion_recepcion);

        if ($stmt_insert->execute()) {
            header("Location: Index.php?mensaje=Compra agregada con éxito");
            exit();
        } else {
            echo "Error: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    } else {
        echo "Por favor, complete todos los campos.";
    }


$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nueva Compra</title>
    <link rel="stylesheet" href="style.css"> <!-- Si tienes un archivo CSS -->
</head>
<body>
    <h1>Agregar Nueva Compra</h1>

    <!-- Formulario para agregar una nueva compra -->
    <form action="Agregar.php" method="POST">
        <label for="id_proveedor">ID Proveedor:</label>
        <input type="text" name="id_proveedor" required>

        <label for="fecha_compra">Fecha de Compra:</label>
        <input type="date" name="fecha_compra" required>

        <label for="numero_factura">Número de Factura:</label>
        <input type="text" name="numero_factura" required>

        <label for="monto_total">Monto Total:</label>
        <input type="number" step="0.01" name="monto_total" required>

        <label for="metodo_pago">Método de Pago:</label>
        <input type="text" name="metodo_pago" required>

        <label for="fecha_entrega">Fecha Estimada de Entrega:</label>
        <input type="date" name="fecha_entrega" required>

        <label for="estado_compra">Estado de la Compra:</label>
        <select name="estado_compra" required>
            <option value="Entregada">Entregada</option>
            <option value="Pendiente">Pendiente</option>
            <option value="En tránsito">En tránsito</option>
            <option value="Cancelada">Cancelada</option>
        </select>

        <label for="validacion_recepcion">Validación de Recepción:</label>
        <input type="number" name="validacion_recepcion" required>

        <button type="submit">Guardar Compra</button>
    </form>

    <a href="Index.php">Volver a la lista de compras</a>
</body>
</html>
