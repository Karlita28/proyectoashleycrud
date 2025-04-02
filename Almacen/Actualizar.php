<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que los datos existen en $_POST antes de usarlos
    $id_producto   = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : null;
    $codigo        = isset($_POST['codigo']) ? $_POST['codigo'] : '';
    $nombre        = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $descripcion   = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    $ubicacion     = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : '';
    $stock_actual  = isset($_POST['stock_actual']) ? intval($_POST['stock_actual']) : 0;
    $stock_minimo  = isset($_POST['stock_minimo']) ? intval($_POST['stock_minimo']) : 0;
    $stock_maximo  = isset($_POST['stock_maximo']) ? intval($_POST['stock_maximo']) : 0;
    $unidad_medida = isset($_POST['unidad_medida']) ? $_POST['unidad_medida'] : '';
    $costo         = isset($_POST['costo']) ? floatval($_POST['costo']) : 0.0;
    $precio_venta  = isset($_POST['precio_venta']) ? floatval($_POST['precio_venta']) : 0.0;
    $lote_serie    = isset($_POST['lote_serie']) ? $_POST['lote_serie'] : '';

    // Verificar conexión antes de preparar la consulta
    if (!$conexion) {
        die("❌ Error de conexión a la base de datos.");
    }

    $sql = "UPDATE almacen SET 
            codigo = ?, 
            nombre = ?, 
            descripcion = ?, 
            ubicacion = ?, 
            stock_actual = ?, 
            stock_minimo = ?, 
            stock_maximo = ?, 
            unidad_medida = ?, 
            costo = ?, 
            precio_venta = ?, 
            lote_serie = ? 
            WHERE id_producto = ?";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ssssiiissdsi", 
            $codigo, $nombre, $descripcion, $ubicacion, 
            $stock_actual, $stock_minimo, $stock_maximo, 
            $unidad_medida, $costo, $precio_venta, $lote_serie, $id_producto);

        if ($stmt->execute()) {
            header("Location: index.php?mensaje=Producto actualizado correctamente");
        } else {
            header("Location: index.php?error=Error al actualizar producto: " . urlencode($stmt->error));
        }

        $stmt->close();
    } else {
        die("❌ Error al preparar la consulta: " . $conexion->error);
    }

    $conexion->close();
} else {
    // Verificar si se recibe un ID válido
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die("❌ Error: ID de producto inválido.");
    }

    $id_producto = intval($_GET['id']);

    $sql = "SELECT * FROM almacen WHERE id_producto = ?";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();
        $stmt->close();
        $conexion->close();

        if (!$producto) {
            die("❌ Error: Producto no encontrado.");
        }
    } else {
        die("❌ Error al preparar la consulta: " . $conexion->error);
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="text-center">Editar Producto</h2>
            </div>
            <div class="card-body">
                <form action="Actualizar.php" method="POST">
                    <input type="hidden" name="id_producto" value="<?= htmlspecialchars($producto['id_producto']) ?>">

                    <div class="mb-3">
                        <label class="form-label">Código:</label>
                        <input type="text" class="form-control" name="codigo" value="<?= htmlspecialchars($producto['codigo']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción:</label>
                        <textarea class="form-control" name="descripcion" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ubicación:</label>
                        <input type="text" class="form-control" name="ubicacion" value="<?= htmlspecialchars($producto['ubicacion']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stock Actual:</label>
                        <input type="number" class="form-control" name="stock_actual" value="<?= $producto['stock_actual'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stock Mínimo:</label>
                        <input type="number" class="form-control" name="stock_minimo" value="<?= $producto['stock_minimo'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stock Máximo:</label>
                        <input type="number" class="form-control" name="stock_maximo" value="<?= $producto['stock_maximo'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unidad de Medida:</label>
                        <input type="text" class="form-control" name="unidad_medida" value="<?= htmlspecialchars($producto['unidad_medida']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Costo:</label>
                        <input type="text" class="form-control" name="costo" value="<?= $producto['costo'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Precio de Venta:</label>
                        <input type="text" class="form-control" name="precio_venta" value="<?= $producto['precio_venta'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lote / Serie:</label>
                        <input type="text" class="form-control" name="lote_serie" value="<?= htmlspecialchars($producto['lote_serie']) ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php 
} // Fin del else
?>
