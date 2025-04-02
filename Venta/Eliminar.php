<?php
include 'Conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id_venta = intval($_POST['id']);
    
    try {
        // Iniciar transacción para eliminar primero los detalles
        $conexion->begin_transaction();
        
        // 1. Eliminar detalles de venta primero (si existen)
        $sql_detalles = "DELETE FROM detalleventa WHERE id_venta = ?";
        $stmt_detalles = $conexion->prepare($sql_detalles);
        $stmt_detalles->bind_param("i", $id_venta);
        $stmt_detalles->execute();
        
        // 2. Eliminar la venta principal
        $sql_venta = "DELETE FROM venta WHERE id_venta = ?";
        $stmt_venta = $conexion->prepare($sql_venta);
        $stmt_venta->bind_param("i", $id_venta);
        $stmt_venta->execute();
        
        // Confirmar transacción
        $conexion->commit();
        
        header("Location: Index.php?mensaje=Venta+eliminada+correctamente");
    } catch (Exception $e) {
        $conexion->rollback();
        header("Location: Index.php?error=Error+al+eliminar+venta");
    }
    exit();
}

header("Location: Index.php");
?>