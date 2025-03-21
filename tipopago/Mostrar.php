<?php
include 'Conexion.php'; 

$result = $conexion->query("SELECT * FROM tipopago");

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Métodos de Pago</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Lista de Métodos de Pago</h1>
    <a href="Index.php">Agregar Nuevo Método</a>
    <br><br>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Metodo</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id_tipo']; ?></td>
            <td><?php echo htmlspecialchars($row['NombreMetodo']); ?></td>
            <td>
                <a href="Actualizar.php?id_tipo=<?php echo $row['id_tipo']; ?>">Editar</a> |
                <form action="Eliminar.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este método de pago?');">
                    <input type="hidden" name="id_tipo" value="<?php echo $row['id_tipo']; ?>">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
