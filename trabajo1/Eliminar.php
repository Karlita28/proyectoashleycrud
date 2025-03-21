<?php
include 'Proveedores/Conexion.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!$conexion) {
    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = intval($_GET["id"]); 

    $stmt = $conexion->prepare("DELETE FROM proveedor WHERE id_proveedor = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: Index.php?mensaje=Proveedor eliminado correctamente");
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
