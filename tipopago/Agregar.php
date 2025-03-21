<?php
include 'Conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nombre_metodo'])) {
    $nombre_metodo = trim($_POST['nombre_metodo']);
    
    $stmt = $conexion->prepare("INSERT INTO tipopago (NombreMetodo) VALUES (?)");
    $stmt->bind_param("s", $nombre_metodo);
    
    if ($stmt->execute()) {
        header("Location: Index.php?mensaje=Método de pago agregado con éxito");
        exit();
    } else {
        echo "Error al agregar: " . $stmt->error;
    }
    $stmt->close();
}
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Método de Pago</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Agregar Nuevo Método de Pago</h1>
    <form method="POST">
        <label for="nombre_metodo">Nombre del Método de Pago:</label>
        <input type="text" name="nombre_metodo" required>
        <br><br>
        <button type="submit">Guardar</button>
    </form>
    <br>
    <a href="Index.php">Volver a la lista de métodos de pago</a>
</body>
</html>
