<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id_venta = intval($_POST['id']);
    
    try {
        $conexion->begin_transaction();
        
        // 1. Eliminar detalles de venta
        $sql_detalles = "DELETE FROM venta WHERE id_venta = ?";
        $stmt_detalles = $conexion->prepare($sql_detalles);
        $stmt_detalles->bind_param("i", $id_venta);
        $stmt_detalles->execute();
        
        // 2. Eliminar la venta principal
        $sql_venta = "DELETE FROM venta WHERE id_venta = ?";
        $stmt_venta = $conexion->prepare($sql_venta);
        $stmt_venta->bind_param("i", $id_venta);
        $stmt_venta->execute();
        
        $conexion->commit();
        header("Location: index.php?mensaje=Venta+eliminada+correctamente");
    } catch (Exception $e) {
        $conexion->rollback();
        header("Location: index.php?error=Error+al+eliminar+venta");
    }
    exit();
}

header("Location: index.php");
exit();
?>