<?php
include 'Conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {
    $nombre_metodo = trim($_POST['nombre_metodo']);
    
    if ($_POST['accion'] == 'agregar' && !empty($nombre_metodo)) {
        $stmt = $conexion->prepare("INSERT INTO tipopago (NombreMetodo) VALUES (?)");
        $stmt->bind_param("s", $nombre_metodo);
    } elseif ($_POST['accion'] == 'editar' && !empty($_POST['id_TipoPago']) && is_numeric($_POST['id_TipoPago'])) {
        $id_tipo = $_POST['id_TipoPago'];
        $stmt = $conexion->prepare("UPDATE tipopago SET NombreMetodo = ? WHERE id_TipoPago = ?");
        $stmt->bind_param("si", $nombre_metodo, $id_tipo);
    }
    
    if (isset($stmt) && $stmt->execute()) {
        header("Location: Index.php?mensaje=Operación realizada con éxito");
        exit();
    } else {
        echo "Error en la operación: " . ($stmt->error ?? 'Datos inválidos.');
    }
    if (isset($stmt)) $stmt->close();
}

$result = $conexion->query("SELECT * FROM tipopago");
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Métodos de Pago</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Gestionar Métodos de Pago</h1>
    <form method="POST">
        <input type="hidden" name="accion" value="agregar">
        <label for="nombre_metodo">Nombre del Método de Pago:</label>
        <input type="text" name="nombre_metodo" required>
        <button type="submit">Guardar</button>
    </form>
    <br>
    <h2>Lista de Métodos de Pago</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Metodo</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['ID_TipoPago']; ?></td>
            <td><?php echo htmlspecialchars($row['NombreMetodo']); ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="id_TipoPago" value="<?php echo $row['ID_TipoPago']; ?>">
                    <input type="text" name="nombre_metodo" value="<?php echo htmlspecialchars($row['NombreMetodo']); ?>" required>
                    <button type="submit">Editar</button>
                </form>
                <form action="Eliminar.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este método de pago?');">
                    <input type="hidden" name="ID_TipoPago" value="<?php echo $row['ID_TipoPago']; ?>">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
