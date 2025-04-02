<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'Conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_venta = intval($_GET['id']);
    
    // Obtener datos actuales de la venta
    $sql = "SELECT * FROM venta WHERE id_venta = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_venta);
    $stmt->execute();
    $result = $stmt->get_result();
    $venta = $result->fetch_assoc();
    $stmt->close();
    
    if (!$venta) {
        die("❌ Error: No se encontró la venta con ID $id_venta.");
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Editar Venta</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-4">
            <h2>Editar Venta</h2>
            <form action="Actualizar.php" method="POST">
                <input type="hidden" name="id_venta" value="<?= $venta['id_venta'] ?>">
                
                <div class="mb-3">
                    <label class="form-label">Fecha de Venta:</label>
                    <input type="date" class="form-control" name="fecha_venta" value="<?= htmlspecialchars($venta['fecha_venta']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Código de Factura:</label>
                    <input type="text" class="form-control" name="fact_code" value="<?= htmlspecialchars($venta['fact_code']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">ID Cliente:</label>
                    <input type="number" class="form-control" name="id_cliente" value="<?= htmlspecialchars($venta['id_cliente']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Método de Pago:</label>
                    <select name="metodo_pago" class="form-select">
                        <option value="Efectivo" <?= $venta['metodo_pago'] == 'Efectivo' ? 'selected' : '' ?>>Efectivo</option>
                        <option value="Tarjeta" <?= $venta['metodo_pago'] == 'Tarjeta' ? 'selected' : '' ?>>Tarjeta</option>
                        <option value="Transferencia" <?= $venta['metodo_pago'] == 'Transferencia' ? 'selected' : '' ?>>Transferencia</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado:</label>
                    <select name="estado" class="form-select">
                        <option value="Pendiente" <?= $venta['estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="Entregada" <?= $venta['estado'] == 'Entregada' ? 'selected' : '' ?>>Entregada</option>
                        <option value="Pagada" <?= $venta['estado'] == 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                        <option value="En proceso" <?= $venta['estado'] == 'En proceso' ? 'selected' : '' ?>>En proceso</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="Index.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </body>
    </html>
    <?php
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar actualización
    if (!isset($_POST['id_venta'], $_POST['fecha_venta'], $_POST['fact_code'], $_POST['id_cliente'], $_POST['metodo_pago'], $_POST['estado'])) {
        header("Location: Index.php?error=Datos incompletos");
        exit();
    }

    $id_venta = intval($_POST['id_venta']);
    $fecha_venta = $_POST['fecha_venta'];
    $fact_code = $_POST['fact_code'];
    $id_cliente = intval($_POST['id_cliente']);
    $metodo_pago = $_POST['metodo_pago'];
    $estado = $_POST['estado'];

    $stmt = $conexion->prepare("UPDATE venta SET fecha_venta=?, fact_code=?, id_cliente=?, metodo_pago=?, estado=? WHERE id_venta=?");
    $stmt->bind_param("ssissi", $fecha_venta, $fact_code, $id_cliente, $metodo_pago, $estado, $id_venta);

    if ($stmt->execute()) {
        header("Location: Index.php?mensaje=Venta actualizada con éxito");
        exit();
    } else {
        header("Location: Index.php?error=" . urlencode("Error al actualizar venta: " . $stmt->error));
        exit();
    }

    $stmt->close();
    $conexion->close();
} else {
    header("Location: Index.php");
    exit();
}
?>