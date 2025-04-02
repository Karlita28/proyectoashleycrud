<?php
// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir conexión
include 'conexion.php';

// Verificar conexión
if (!isset($conexion) || $conexion->connect_error) {
    die("Error de conexión: " . ($conexion->connect_error ?? "Conexión no establecida"));
}

// Procesar POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validar campos obligatorios
        if (empty($_POST['fecha']) || empty($_POST['numero_factura'])) {
            throw new Exception("Fecha y número de factura son obligatorios");
        }

        // Asignar valores a variables
        $fecha = $_POST['fecha'];
        $numero_factura = $_POST['numero_factura'];
        $cliente = $_POST['cliente'] ?? '';
        $tipo_pago = $_POST['tipo_pago'] ?? 'Efectivo';

        // Iniciar transacción
        $conexion->begin_transaction();

        // Insertar cabecera de venta
        $sql_venta = "INSERT INTO venta (fecha_venta, fact_code, id_cliente, metodo_pago, monto_total, estado) 
                      VALUES (?, ?, ?, ?, 0, 'En proceso')";
        $stmt_venta = $conexion->prepare($sql_venta);
        if (!$stmt_venta) {
            throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
        }
        $stmt_venta->bind_param("ssis", $fecha, $numero_factura, $cliente, $tipo_pago);
        $stmt_venta->execute();
        $id_venta = $conexion->insert_id;

        if ($id_venta <= 0) {
            throw new Exception("No se pudo registrar la venta.");
        }

        // Insertar detalles de venta y calcular total
        $total = 0;
        if (!empty($_POST['productos'])) {
            foreach ($_POST['productos'] as $producto) {
                if (!empty($producto['id']) && !empty($producto['cantidad']) && !empty($producto['precio_unitario'])) {
                    $subtotal = $producto['cantidad'] * $producto['precio_unitario'];
                    $total += $subtotal;

                    // Insertar detalle de venta
                    $sql_detalle = "INSERT INTO detalleventa (id_venta, id_producto, cantidad, precio_unitario, subtotal) 
                                    VALUES (?, ?, ?, ?, ?)";
                    $stmt_detalle = $conexion->prepare($sql_detalle);
                    if (!$stmt_detalle) {
                        throw new Exception("Error en la consulta de detalle: " . $conexion->error);
                    }
                    $stmt_detalle->bind_param("iiidd", $id_venta, $producto['id'], $producto['cantidad'], $producto['precio_unitario'], $subtotal);
                    $stmt_detalle->execute();

                    // Actualizar stock
                    $sql_stock = "UPDATE almacen SET stock_actual = stock_actual - ? WHERE id_producto = ?";
                    $stmt_stock = $conexion->prepare($sql_stock);
                    if (!$stmt_stock) {
                        throw new Exception("Error en la actualización de stock: " . $conexion->error);
                    }
                    $stmt_stock->bind_param("ii", $producto['cantidad'], $producto['id']);
                    $stmt_stock->execute();
                }
            }
        }

        // Actualizar total en ventas
        $sql_update_total = "UPDATE venta SET monto_total = ? WHERE id_venta = ?";
        $stmt_update_total = $conexion->prepare($sql_update_total);
        if (!$stmt_update_total) {
            throw new Exception("Error al actualizar el total de la venta: " . $conexion->error);
        }
        $stmt_update_total->bind_param("di", $total, $id_venta);
        $stmt_update_total->execute();

        // Confirmar transacción
        $conexion->commit();
        header("Location: index.php?mensaje=Venta guardada correctamente");
        exit();
    } catch (Exception $e) {
        $conexion->rollback();
        die("❌ Error al procesar la venta: " . $e->getMessage());
    }
}

// Obtener productos
$productos = [];
$sql = "SELECT id_producto, nombre, precio_venta FROM almacen WHERE stock_actual > 0 ORDER BY nombre";
$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error al obtener productos: " . $conexion->error);
}

while ($fila = $resultado->fetch_assoc()) {
    $productos[] = $fila;
}
?>
