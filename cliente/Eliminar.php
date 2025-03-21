<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'Conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cliente'])) {
    $id_cliente = intval($_POST['id_cliente']); 

    if ($id_cliente > 0) {
        $verificar = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_cliente = ?");
        $verificar->bind_param("i", $id_cliente);
        $verificar->execute();
        $resultado = $verificar->get_result();

        if ($resultado->num_rows > 0) {
            $sql = "DELETE FROM cliente WHERE id_cliente = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $id_cliente);

            if ($stmt->execute()) {
                header("Location: Index.php?mensaje=Cliente eliminado con éxito");
                exit();
            } else {
                echo "❌ Error al eliminar: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "❌ No se encontró un cliente con ID $id_cliente.";
        }
    } else {
        echo "❌ ID inválido.";
    }
} else {
    echo "❌ No se proporcionó el ID del cliente.";
}

$conexion->close();
?>
