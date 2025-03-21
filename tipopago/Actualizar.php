<?php
include 'Conexion.php'; 

if (isset($_GET['id_TipoPago']) && is_numeric($_GET['id_TipoPago'])) {
    $id_tipo = $_GET['id_TipoPago'];
    
    $stmt = $conexion->prepare("SELECT * FROM tipopago WHERE id_TipoPao = ?");
    $stmt->bind_param("i", $id_tipo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $tipo_pago = $result->fetch_assoc();
    } else {
        die("Método de pago no encontrado.");
    }
    $stmt->close();
} else {
    die("ID no proporcionado o inválido.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nombre_metodo'])) {
    $nombre_metodo = $_POST['nombre_metodo'];
    
    $stmt_update = $conexion->prepare("UPDATE tipopago SET NombreMetodo = ? WHERE id_tipo = ?");
    $stmt_update->bind_param("si", $nombre_metodo, $id_tipo);
    
    if ($stmt_update->execute()) {
        header("Location: Index.php?mensaje=Método de pago actualizado con éxito");
        exit();
    } else {
        echo "Error al actualizar: " . $stmt_update->error;
    }
    $stmt_update->close();
}
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Método de Pago</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Actualizar Método de Pago</h1>
    <form method="POST">
        <label for="nombre_metodo">Nombre del Método de Pago:</label>
        <input type="text" name="nombre_metodo" value="<?php echo htmlspecialchars($tipo_pago['NombreMetodo']); ?>" required>
        <br><br>
        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
