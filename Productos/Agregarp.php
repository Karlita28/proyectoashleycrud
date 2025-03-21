<?php
include 'Productos/Conexionp.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $stock_minimo = intval($_POST["stock_minimo"]);
    $stock_actual = intval($_POST["stock_actual"]);
    $id_categoria = intval($_POST["id_categoria"]);
    $costo_adquisicion = floatval($_POST["costo_adquisicion"]);
    $precio_unitario = floatval($_POST["precio_unitario"]);

    $stmt = $conexion->prepare("INSERT INTO Producto (nombre, descripcion, stock_minimo, stock_actual, id_categoria, costo_adquisicion, precio_unitario) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiidd", $nombre, $descripcion, $stock_minimo, $stock_actual, $id_categoria, $costo_adquisicion, $precio_unitario);

    if ($stmt->execute()) {
        header("Location: Index.php?mensaje=Producto agregado correctamente");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
?>
