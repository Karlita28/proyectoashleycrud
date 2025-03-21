<?php
include 'Conexion.php'; 
$sql = "SELECT * FROM cliente"; 
$result = $conexion->query($sql); 

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h1>Gestión de Clientes</h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID Cliente</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Dirección de Facturación</th>
                <th>Dirección de Envío</th>
                <th>Condiciones de Pago</th>
                <th>Tipo de Cliente</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_cliente']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['correo']; ?></td>
                        <td><?php echo $row['telefono']; ?></td>
                        <td><?php echo $row['direccion_facturacion']; ?></td>
                        <td><?php echo $row['direccion_envio']; ?></td>
                        <td><?php echo $row['condiciones_pago']; ?></td>
                        <td><?php echo $row['tipo_cliente']; ?></td>
                        <td><?php echo $row['estado']; ?></td>
                        <td>
                            <a href="Actualizar.php?id_cliente=<?php echo $row['id_cliente']; ?>">Editar</a> | 
                            <form action="Eliminar.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id_cliente" value="<?php echo $row['id_cliente']; ?>">
                                <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No se encontraron clientes.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <a href="Agregar.php">Agregar Nuevo Cliente</a> 
    
</body>
</html>

<?php
$conexion->close(); 
?>
