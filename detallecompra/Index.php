<?php
include 'Conexion.php'; // Asegúrate de que el archivo de conexión esté correctamente incluido

// Obtener todos los detalles de compra registrados
$sql = "SELECT * FROM detallecompra"; // Cambié el nombre de la tabla para que sea consistente con tu código
$result = $conexion->query($sql); // Ejecutar consulta

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Detalles de Compra</title>
    <link rel="stylesheet" href="style.css"> <!-- Si tienes un archivo CSS para el estilo -->
</head>
<body>
    <h1>Gestión de Detalles de Compra</h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID Compra</th>
                <th>ID Producto</th>
                <th>Cantidad</th>
                <th>Costo Unitario</th>
                <th>Costo Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['ID_Compra']; ?></td>
                        <td><?php echo $row['ID_Producto']; ?></td>
                        <td><?php echo $row['Cantidad']; ?></td>
                        <td><?php echo $row['CostoUnitario']; ?></td>
                        <td><?php echo $row['CostoTotal']; ?></td>
                        <td>
                            <!-- Botones para actualizar y eliminar -->
                            <form action="Actualizar.php" method="GET" style="display:inline;">
                                <input type="hidden" name="id_DetalleCompra" value="<?php echo $row['ID_DetalleCompra']; ?>">
                                <button type="submit">Editar</button>
                            </form>
                            <form action="Eliminar.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id_detallecompra" value="<?php echo $row['ID_DetalleCompra']; ?>">
                                <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar este detalle de compra?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No se encontraron detalles de compra.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <a href="Agregar.php">Agregar Nuevo Detalle de Compra</a> <!-- Puedes crear esta página para agregar nuevos detalles de compra -->
    
</body>
</html>

<?php
$conexion->close(); // Recuerda cerrar la conexión cuando hayas terminado
?>
