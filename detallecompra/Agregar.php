<?php
include 'Conexion.php'; // Incluye la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los datos del formulario
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];
    $costo_unitario = $_POST['costo_unitario'];
    $costo_total = $_POST['costo_total'];

    // Verifica si los campos no están vacíos
    if (!empty($id_producto) && !empty($cantidad) && !empty($costo_unitario) && !empty($costo_total)) {
        
        // Verifica si el id_producto existe en la tabla productos
        $stmt_check = $conexion->prepare("SELECT COUNT(*) FROM producto WHERE id_producto = ?");
        $stmt_check->bind_param("i", $id_producto);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        // Si el producto no existe, muestra un mensaje de error
        if ($count == 0) {
            echo "Error: El producto con ID $id_producto no existe.";
        } else {
            // Paso 1: Inserta el nuevo detalle de compra (sin incluir ID_Compra si es autoincremental)
            $sql_insert = "INSERT INTO detallecompra (id_producto, Cantidad, CostoUnitario, CostoTotal)
                           VALUES (?, ?, ?, ?)";
            $stmt_insert = $conexion->prepare($sql_insert);
            $stmt_insert->bind_param("iidd", $id_producto, $cantidad, $costo_unitario, $costo_total);

            if ($stmt_insert->execute()) {
                echo "Detalle de compra agregado con éxito.";
            } else {
                echo "Error: " . $stmt_insert->error;
            }

            $stmt_insert->close();
        }
    } else {
        echo "Por favor, complete todos los campos.";
    }
}

$conexion->close();
?>

<!-- Formulario para agregar un nuevo detalle de compra -->
<form action="Agregar.php" method="POST">
    <label for="id_producto">ID Producto:</label>
    <input type="number" name="id_producto" required>

    <label for="cantidad">Cantidad:</label>
    <input type="number" name="cantidad" required>

    <label for="costo_unitario">Costo Unitario:</label>
    <input type="number" step="0.01" name="costo_unitario" required>

    <label for="costo_total">Costo Total:</label>
    <input type="number" step="0.01" name="costo_total" required>

    <button type="submit">Agregar Detalle</button>
</form>
