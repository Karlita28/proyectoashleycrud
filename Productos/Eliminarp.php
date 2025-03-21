<?php
include 'Productos/Conexionp.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!$conexion) {
    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
}

if (isset($_GET["id_producto"]) && is_numeric($_GET["id_producto"])) {
    $id = intval($_GET["id_producto"]); 

    $stmt = $conexion->prepare("DELETE FROM producto WHERE id_producto = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: Indexp.php?mensaje=Producto eliminado correctamente");
        exit();
    } else {
        echo "Error al eliminar: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID inválido o no recibido.";
}

$conexion->close();
?>
