<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $fecha_venta = $_POST['fecha_venta'];
    $fact_code = $_POST['fact_code'];
    $id_cliente = intval($_POST['id_cliente']);
    $metodo_pago = $_POST['metodo_pago'];
    $estado = $_POST['estado'];
    $monto_total = floatval($_POST['monto_total']);

    // Validar datos obligatorios
    if (empty($fecha_venta) || empty($fact_code) || empty($id_cliente) || $monto_total <= 0) {
        header("Location: Index.php?error=Faltan campos obligatorios o monto inválido");
        exit();
    }

    $sql = "INSERT INTO venta (fecha_venta, fact_code, id_cliente, metodo_pago, estado, monto_total)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        header("Location: Index.php?error=Error en la preparación: " . urlencode($conexion->error));
        exit();
    }

    // Ajustar tipos: s=string, i=integer, d=double
    $stmt->bind_param("ssissd", $fecha_venta, $fact_code, $id_cliente, $metodo_pago, $estado, $monto_total);

    if ($stmt->execute()) {
        header("Location: Index.php?mensaje=Venta agregada correctamente");
    } else {
        header("Location: Index.php?error=Error al agregar venta: " . urlencode($stmt->error));
    }

    $stmt->close();
    $conexion->close();
    exit();
}