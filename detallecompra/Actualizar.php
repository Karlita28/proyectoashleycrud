<?php
include 'Conexion.php'; // Incluye la conexión a la base de datos

// Verifica si se ha enviado un parámetro 'id_detallecompra'
if (isset($_GET['id_DetalleCompra'])) {
    $id_detallecompra = $_GET['id_DetalleCompra'];

    // Obtener la información actual del detalle de compra
    $sql = "SELECT * FROM detallecompra WHERE ID_DetalleCompra = ?";
    $stmt = $conexion->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $id_detallecompra);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $detallecompra = $result->fetch_assoc();
        } else {
            echo "Detalle de compra no encontrado.";
            exit();
        }
    } else {
        echo "Error en la consulta: " . $conexion->error;
        exit();
    }
} else {
    echo "ID de detalle de compra no proporcionado.";
    exit();
}

// Verifica si se ha enviado el formulario para actualizar los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_compra = $_POST['id_compra'];
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];
    $costo_unitario = $_POST['costo_unitario'];
    $costo_total = $_POST['costo_total'];

    if (!empty($id_compra) && !empty($id_producto) && !empty($cantidad) && !empty($costo_unitario) && !empty($costo_total)) {
        $sql_update = "UPDATE detallecompra 
                       SET ID_Compra = ?, ID_Producto = ?, Cantidad = ?, CostoUnitario = ?, CostoTotal = ? 
                       WHERE ID_DetalleCompra = ?";
        $stmt_update = $conexion->prepare($sql_update);

        if ($stmt_update) {
            $stmt_update->bind_param("iiiddi", $id_compra, $id_producto, $cantidad, $costo_unitario, $costo_total, $id_detallecompra);

            if ($stmt_update->execute()) {
                header("Location: Index.php?mensaje=Detalle de compra actualizado con éxito");
                exit();
            } else {
                echo "Error al actualizar: " . $stmt_update->error;
            }
            $stmt_update->close();
        } else {
            echo "Error en la consulta de actualización: " . $conexion->error;
        }
    } else {
        echo "Por favor, complete todos los campos.";
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Detalle de Compra</title>
</head>
<body>
    <h1>Editar Detalle de Compra</h1>

    <form action="Actualizar.php" method="POST">
        <label for="id_compra">ID Compra:</label>
        <input type="number" name="id_compra" value="<?php echo htmlspecialchars($detallecompra['ID_Compra']); ?>" required>

        <label for="id_producto">ID Producto:</label>
        <input type="number" name="id_producto" value="<?php echo htmlspecialchars($detallecompra['ID_Producto']); ?>" required>

        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" value="<?php echo htmlspecialchars($detallecompra['Cantidad']); ?>" required>

        <label for="costo_unitario">Costo Unitario:</label>
        <input type="number" step="0.01" name="costo_unitario" value="<?php echo htmlspecialchars($detallecompra['CostoUnitario']); ?>" required>

        <label for="costo_total">Costo Total:</label>
        <input type="number" step="0.01" name="costo_total" value="<?php echo htmlspecialchars($detallecompra['CostoTotal']); ?>" required>

        <button type="submit">Actualizar Detalle</button>
    </form>

    <a href="Index.php">Volver a la lista</a>
</body>
</html>
