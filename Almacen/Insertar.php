<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $ubicacion = $_POST['ubicacion'];
    $stock_actual = intval($_POST['stock_actual']);
    $stock_minimo = intval($_POST['stock_minimo']);
    $stock_maximo = intval($_POST['stock_maximo']);
    $unidad_medida = $_POST['unidad_medida'];
    $costo = floatval($_POST['costo']);
    $precio_venta = floatval($_POST['precio_venta']);
    $lote_serie = $_POST['lote_serie'];

    // Validar datos obligatorios
    if (empty($codigo) || empty($nombre) || empty($ubicacion)) {
        header("Location: Index.php?error=Faltan campos obligatorios");
        exit();
    }

    $sql = "INSERT INTO almacen (codigo, nombre, descripcion, ubicacion, stock_actual, stock_minimo, stock_maximo, unidad_medida, costo, precio_venta, lote_serie)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        header("Location: Index.php?error=Error en la preparación: " . urlencode($conexion->error));
        exit();
    }

    // Ajustar tipos: s=string, i=integer, d=double
    $stmt->bind_param("ssssiiissds", $codigo, $nombre, $descripcion, $ubicacion, $stock_actual, $stock_minimo, $stock_maximo, $unidad_medida, $costo, $precio_venta, $lote_serie);

    if ($stmt->execute()) {
        header("Location: Index.php?mensaje=Producto agregado correctamente");
    } else {
        header("Location: Index.php?error=Error al agregar producto: " . urlencode($stmt->error));
    }

    $stmt->close();
    $conexion->close(); // Corregido: era $conn->close()
    exit();
}