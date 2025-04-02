<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion.php';

// Verificar si se recibió un ID válido en la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("❌ No se proporcionó un ID válido del producto.");
}

$id_producto = intval($_GET['id']); // Convertir a número entero para evitar inyecciones SQL

// Verificar si el producto existe antes de eliminarlo
$verificar = $conexion->prepare("SELECT id_producto FROM producto WHERE id_producto = ?");
$verificar->bind_param("i", $id_producto);
$verificar->execute();
$resultado = $verificar->get_result();

if ($resultado->num_rows === 0) {
    die("❌ No se encontró un producto con ID $id_producto.");
}

// Verificar si el producto tiene movimientos en el historial
$check_sql = "SELECT COUNT(*) AS total FROM historial WHERE id_producto = ?";
$check_stmt = $conexion->prepare($check_sql);
$check_stmt->bind_param("i", $id_producto);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$row = $check_result->fetch_assoc();

if ($row['total'] > 0) {
    header("Location: Index.php?error=No se puede eliminar, el producto tiene movimientos registrados");
    exit();
}

// Proceder con la eliminación del producto
$sql = "DELETE FROM producto WHERE id_producto = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_producto);

if ($stmt->execute()) {
    header("Location: Index.php?mensaje=Producto eliminado con éxito");
    exit();
} else {
    echo "❌ Error al eliminar: " . $stmt->error;
}

// Cerrar conexiones
$stmt->close();
$check_stmt->close();
$verificar->close();
$conexion->close();
?>
