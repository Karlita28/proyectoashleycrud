<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'Conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;
    $fecha_venta = $_POST['fecha_venta'] ?? '';
    $monto_total = isset($_POST['monto_total']) ? floatval($_POST['monto_total']) : 0.0;
    $metodo_pago = $_POST['metodo_pago'] ?? '';
    $fact_code = $_POST['fact_code'] ?? '';
    $estado = $_POST['estado'] ?? '';

    if ($id_cliente <= 0 || empty($fecha_venta) || $monto_total <= 0 || empty($metodo_pago) || empty($fact_code) || empty($estado)) {
        echo "⚠️ Error: Todos los campos son obligatorios y deben ser válidos.";
        exit();
    }

    // Verificar si el cliente existe
    $sql_verificar = "SELECT id_cliente FROM cliente WHERE id_cliente = ?";
    $stmt_verificar = $conexion->prepare($sql_verificar);
    $stmt_verificar->bind_param("i", $id_cliente);
    $stmt_verificar->execute();
    $stmt_verificar->store_result();

    if ($stmt_verificar->num_rows > 0) {
        // El cliente existe, proceder con la inserción
        $sql = "INSERT INTO venta (id_cliente, fecha_venta, monto_total, metodo_pago, fact_code, estado) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("isdsss", $id_cliente, $fecha_venta, $monto_total, $metodo_pago, $fact_code, $estado);

        if ($stmt->execute()) {
            header("Location: Index.php?mensaje=Venta agregada con éxito");
            exit();
        } else {
            echo "❌ Error al insertar la venta: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "❌ Error: El cliente con ID $id_cliente no existe. <a href='Index.php'>Volver</a>";
    }
    $stmt_verificar->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Agregar Venta</h2>
        <form action="Agregar.php" method="POST">
            <div class="mb-3">
                <label for="id_cliente" class="form-label">ID Cliente:</label>
                <input type="number" class="form-control" id="id_cliente" name="id_cliente" required>
            </div>
            
            <div class="mb-3">
                <label for="fecha_venta" class="form-label">Fecha de Venta:</label>
                <input type="date" class="form-control" id="fecha_venta" name="fecha_venta" required>
            </div>
            
            <div class="mb-3">
                <label for="monto_total" class="form-label">Monto Total:</label>
                <input type="number" step="0.01" class="form-control" id="monto_total" name="monto_total" required>
            </div>

            <div class="mb-3">
                <label for="metodo_pago" class="form-label">Método de Pago:</label>
                <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta</option>
                    <option value="Transferencia">Transferencia</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="fact_code" class="form-label">Código de Factura:</label>
                <input type="text" class="form-control" id="fact_code" name="fact_code" required>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Pagada">Pagada</option>
                    <option value="Entregada">Entregada</option>
                    <option value="En Proceso">En Proceso</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Agregar Venta</button>
            <a href="Index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
