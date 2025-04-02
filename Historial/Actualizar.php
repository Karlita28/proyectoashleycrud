<?php
include 'conexion.php';

// Verificar si se está editando (GET) o actualizando (POST)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_venta = intval($_GET['id']);

    // Verificar si el ID es válido
    if ($id_venta <= 0) {
        header("Location: index.php?error=ID de venta inválido");
        exit();
    }

    // Obtener datos actuales de la venta
    $sql = "SELECT * FROM venta WHERE id_venta = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_venta);
    $stmt->execute();
    $result = $stmt->get_result();
    $venta = $result->fetch_assoc();
    $stmt->close();

    if (!$venta) {
        header("Location: index.php?error=Venta no encontrada");
        exit();
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
                <input type="hidden" name="id_venta" value="<?= htmlspecialchars($venta['id_venta'] ?? '') ?>">

                <div class="mb-3">
                    <label class="form-label">Fecha de Venta:</label>
                    <input type="date" class="form-control" name="fecha_venta" 
                           value="<?= isset($venta['fecha_venta']) ? date('Y-m-d', strtotime($venta['fecha_venta'])) : '' ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Código de Factura:</label>
                    <input type="text" class="form-control" name="fact_code" 
                           value="<?= htmlspecialchars($venta['fact_code'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">ID Cliente:</label>
                    <input type="number" class="form-control" name="id_cliente" 
                           value="<?= htmlspecialchars($venta['id_cliente'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Método de Pago:</label>
                    <select name="metodo_pago" class="form-select" required>
                        <option value="Efectivo" <?= ($venta['metodo_pago'] ?? '') === 'Efectivo' ? 'selected' : '' ?>>Efectivo</option>
                        <option value="Tarjeta" <?= ($venta['metodo_pago'] ?? '') === 'Tarjeta' ? 'selected' : '' ?>>Tarjeta</option>
                        <option value="Transferencia" <?= ($venta['metodo_pago'] ?? '') === 'Transferencia' ? 'selected' : '' ?>>Transferencia</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Monto Total:</label>
                    <input type="number" step="0.01" class="form-control" name="monto_total" 
                           value="<?= htmlspecialchars($venta['monto_total'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado:</label>
                    <select name="estado" class="form-select" required>
                        <option value="Pagada" <?= ($venta['estado'] ?? '') === 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                        <option value="En proceso" <?= ($venta['estado'] ?? '') === 'En proceso' ? 'selected' : '' ?>>En proceso</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </body>
    </html>

    <?php
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación de datos antes de actualizar
    $id_venta = intval($_POST['id_venta'] ?? 0);
    $fecha_venta = $_POST['fecha_venta'] ?? '';
    $fact_code = $_POST['fact_code'] ?? '';
    $id_cliente = intval($_POST['id_cliente'] ?? 0);
    $metodo_pago = $_POST['metodo_pago'] ?? '';
    $monto_total = floatval($_POST['monto_total'] ?? 0);
    $estado = $_POST['estado'] ?? '';

    // Verificar que los datos estén completos
    if ($id_venta <= 0 || empty($fecha_venta) || empty($fact_code) || $id_cliente <= 0 || empty($metodo_pago) || $monto_total <= 0 || empty($estado)) {
        header("Location: index.php?error=Datos incompletos");
        exit();
    }

    // Actualizar la venta en la base de datos
    $stmt = $conexion->prepare("UPDATE venta SET fecha_venta=?, fact_code=?, id_cliente=?, metodo_pago=?, monto_total=?, estado=? WHERE id_venta=?");
    $stmt->bind_param("ssisisi", $fecha_venta, $fact_code, $id_cliente, $metodo_pago, $monto_total, $estado, $id_venta);

    if ($stmt->execute()) {
        header("Location: index.php?mensaje=Venta actualizada con éxito");
    } else {
        header("Location: index.php?error=Error al actualizar venta");
    }
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
