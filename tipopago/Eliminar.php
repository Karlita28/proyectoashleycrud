<?php
include 'Conexion.php'; 

// Asegurarse de que los errores se reporten
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ID_TipoPago'])) {
    $id_tipo = $_POST['ID_TipoPago'];

    // Verificar si el ID es numérico
    if (is_numeric($id_tipo)) {
        // Paso 1: Eliminar los registros dependientes en la tabla 'compra'
        $stmt_delete = $conexion->prepare("DELETE FROM compra WHERE MetodoPago = ?");
        $stmt_delete->bind_param("i", $id_tipo);
        $stmt_delete->execute();
        $stmt_delete->close();

        // Paso 2: Eliminar el tipo de pago de la tabla 'tipopago'
        $stmt = $conexion->prepare("DELETE FROM tipopago WHERE ID_TipoPago = ?");
        $stmt->bind_param("i", $id_tipo);

        if ($stmt->execute()) {
            // Redirigir con un mensaje de éxito
            header("Location: Index.php?mensaje=Método de pago eliminado con éxito");
            exit();
        } else {
            // Si hay un error al eliminar, mostrarlo
            echo "Error al eliminar el método de pago: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "ID inválido.";
    }
} else {
    echo "No se proporcionó un ID.";
}

$conexion->close();
?>
