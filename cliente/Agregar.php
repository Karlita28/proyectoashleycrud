<?php
include 'Conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion_facturacion = $_POST['direccion_facturacion'];
    $direccion_envio = $_POST['direccion_envio'];
    $condiciones_pago = $_POST['condiciones_pago'];
    $tipo_cliente = $_POST['tipo_cliente'];
    $estado = $_POST['estado'];

    if (!empty($nombre) && !empty($correo) && !empty($telefono) && !empty($direccion_facturacion) && !empty($direccion_envio) && !empty($condiciones_pago) && !empty($tipo_cliente) && !empty($estado)) {

        $sql = "INSERT INTO cliente (nombre, correo, telefono, direccion_facturacion, direccion_envio, condiciones_pago, tipo_cliente, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssss", $nombre, $correo, $telefono, $direccion_facturacion, $direccion_envio, $condiciones_pago, $tipo_cliente, $estado);

        
        if ($stmt->execute()) {
            header("Location: Index.php?mensaje=Cliente agregado con éxito");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, complete todos los campos.";
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Cliente</title>
    <link rel="stylesheet" href="style.css"> <!-- Si tienes un archivo CSS -->
</head>
<body>
    <h1>Agregar Nuevo Cliente</h1>
    <form action="Agregar.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>

        <label for="correo">Correo Electrónico:</label>
        <input type="email" name="correo" required>

        <label for="telefono">Teléfono:</label>
        <input type="tel" name="telefono" required>

        <label for="direccion_facturacion">Dirección de Facturación:</label>
        <input type="text" name="direccion_facturacion" required>

        <label for="direccion_envio">Dirección de Envío:</label>
        <input type="text" name="direccion_envio" required>

        <label for="condiciones_pago">Condiciones de Pago:</label>
        <input type="text" name="condiciones_pago" required>

        <label for="tipo_cliente">Tipo de Cliente:</label>
        <select name="tipo_cliente" required>
            <option value="Mayorista">Mayorista</option>
            <option value="Minorista">Minorista</option>
            <option value="Corporativo">Corporativo</option>
        </select>

        <label for="estado">Estado:</label>
        <select name="estado" required>
            <option value="Santo Domingo">Santo Domingo</option>
            <option value="Santiago">Santiago</option>
            <option value="La Vega">La Vega</option>
            <option value="Punta Cana">Punta Cana</option>
            <option value="San Cristóbal">San Cristóbal</option>
        </select>

        <button type="submit">Guardar Cliente</button>
    </form>

    <br>
    <a href="Index.php">Volver a la lista de clientes</a>
</body>
</html>
