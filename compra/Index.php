<?php
include 'Conexion.php'; // Incluye la conexión a la base de datos

// Verificar si la variable $conn está definida
if (!isset($conn)) {
    die("Error: La conexión a la base de datos no está definida.");
}

// Obtener todas las compras registradas
$sql = "SELECT * FROM compra";
$result = $conn->query($sql);

// Verificar si hubo un error en la consulta
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Compras</title>
    <link rel="stylesheet" href="style.css"> <!-- Si tienes un archivo CSS -->
</head>
<body>
    <h1>Gestión de Compras</h1>
    
    <a href="Agregar.php">Agregar Nueva Compra</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID Compra</th>
                <th>ID Proveedor</th>
                <th>Fecha de Compra</th>
                <th>Número de Factura</th>
                <th>Monto Total</th>
                <th>Método de Pago</th>
                <th>Fecha Estimada de Entrega</th>
                <th>Estado de Compra</th>
                <th>Validación de Recepción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ID_Compra']); ?></td>
                        <td><?php echo htmlspecialchars($row['ID_Proveedor']); ?></td>
                        <td><?php echo htmlspecialchars($row['FechaCompra']); ?></td>
                        <td><?php echo htmlspecialchars($row['NumeroFactura']); ?></td>
                        <td><?php echo htmlspecialchars($row['MontoTotal']); ?></td>
                        <td><?php echo htmlspecialchars($row['MetodoPago']); ?></td>
                        <td><?php echo htmlspecialchars($row['FechaEstimadaEntrega']); ?></td>
                        <td><?php echo htmlspecialchars($row['EstadoCompra']); ?></td>
                        <td><?php echo htmlspecialchars($row['ValidacionRecepcion']); ?></td>
                        <td>
                            <a href="Actualizar.php?id=<?php echo htmlspecialchars($row['ID_Compra']); ?>">Editar</a> |
                            <form action="Eliminar.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id_compra" value="<?php echo htmlspecialchars($row['ID_Compra']); ?>">
                                <input type="submit" value="Eliminar">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No se encontraron compras registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table

