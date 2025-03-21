<?php
include 'Conexion.php'; // Incluye la conexión a la base de datos

// Verificar si se ha enviado el formulario de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_compra'])) {
    $id_compra = $_POST['id_compra'];

    try {
        // 1. Eliminar los registros relacionados en `detallecompra`
        $sql_delete_detalle = "DELETE FROM detallecompra WHERE ID_Compra = ?";
        $stmt_delete_detalle = $conn->prepare($sql_delete_detalle);
        $stmt_delete_detalle->bind_param("i", $id_compra);
        $stmt_delete_detalle->execute();
        $stmt_delete_detalle->close();

        // 2. Eliminar la compra
        $sql_delete_compra = "DELETE FROM compra WHERE ID_Compra = ?";
        $stmt_delete_compra = $conn->prepare($sql_delete_compra);
        $stmt_delete_compra->bind_param("i", $id_compra);
        $stmt_delete_compra->execute();
        $stmt_delete_compra->close();

        header("Location: Index.php?mensaje=Compra eliminada con éxito");
        exit();
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ID de compra no proporcionado.";
}

$conn->close();
?>