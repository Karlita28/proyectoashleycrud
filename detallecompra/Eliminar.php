<?php
include 'Conexion.php';

if (!empty($_GET['id_DetalleCompra']) && is_numeric($_GET['id_DetalleCompra'])) {
    $stmt = $conexion->prepare("DELETE FROM detallecompra WHERE ID_DetalleCompra = ?");
    $stmt->bind_param("i", $_GET['id_DetalleCompra']);
    
    if ($stmt->execute()) {
        header("Location: Index.php?mensaje=Registro eliminado con éxito");
        exit();
    } else {
        echo "Error al eliminar: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "ID inválido.";
}

$conexion->close();
?>
