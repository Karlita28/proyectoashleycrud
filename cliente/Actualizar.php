<?php
include 'Conexion.php'; 

if (isset($_GET['id_cliente'])) {
    $id_cliente = $_GET['id_cliente'];

    $sql = "SELECT * FROM cliente WHERE id_cliente = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $cliente = $result->fetch_assoc();
    } else {
        echo "Cliente no encontrado.";
        exit();
    }
} else {
    echo "ID de cliente no proporcionado.";
    exit();
}

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

        $sql_update = "UPDATE cliente
                       SET nombre = ?, correo = ?, telefono = ?, direccion_facturacion = ?, direccion_envio = ?, condiciones_pago = ?, tipo_cliente = ?, estado = ?
                       WHERE id_cliente = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("ssssssssi", $nombre, $correo, $telefono, $direccion_facturacion, $direccion_envio, $condiciones_pago, $tipo_cliente, $estado, $id_cliente);

        if ($stmt_update->execute()) {
            header("Location: Index.php?mensaje=Cliente actualizado con éxito");
            exit();
        } else {
            echo "Error: " . $stmt_update->error;
        }

        $stmt_update->close();
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
    <title>Actualizar Cliente</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h1>Actualizar Cliente</h1>

    <form action="Actualizar.php?id_cliente=<?php echo $id_cliente; ?>" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>

        <label for="correo">Correo Electrónico:</label>
        <input type="email" name="correo" value="<?php echo htmlspecialchars($cliente['correo']); ?>" required>

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>" required>

        <label for="direccion_facturacion">Dirección de Facturación:</label>
        <input type="text" name="direccion_facturacion" value="<?php echo htmlspecialchars($cliente['direccion_facturacion']); ?>" required>

        <label for="direccion_envio">Dirección de Envío:</label>
        <input type="text" name="direccion_envio" value="<?php echo htmlspecialchars($cliente['direccion_envio']); ?>" required>

        <label for="condiciones_pago">Condiciones de Pago:</label>
        <input type="text" name="condiciones_pago" value="<?php echo htmlspecialchars($cliente['condiciones_pago']); ?>" required>

        <label for="tipo_cliente">Tipo de Cliente:</label>
        <select name="tipo_cliente" required>
            <option value="Mayorista" <?php echo ($cliente['tipo_cliente'] == 'Mayorista') ? 'selected' : ''; ?>>Mayorista</option>
            <option value="Minorista" <?php echo ($cliente['tipo_cliente'] == 'Minorista') ? 'selected' : ''; ?>>Minorista</option>
            <option value="Corporativo" <?php echo ($cliente['tipo_cliente'] == 'Corporativo') ? 'selected' : ''; ?>>Corporativo</option>
        </select>

        <label for="estado">Ciudad:</label>
        <select name="estado" required>
            <option value="Santo Domingo" <?php echo ($cliente['estado'] == 'Santo Domingo') ? 'selected' : ''; ?>>Santo Domingo</option>
            <option value="Santiago" <?php echo ($cliente['estado'] == 'Santiago') ? 'selected' : ''; ?>>Santiago</option>
            <option value="La Vega" <?php echo ($cliente['estado'] == 'La Vega') ? 'selected' : ''; ?>>La Vega</option>
            <option value="Puerto Plata" <?php echo ($cliente['estado'] == 'Puerto Plata') ? 'selected' : ''; ?>>Puerto Plata</option>
            <option value="San Francisco de Macorís" <?php echo ($cliente['estado'] == 'San Francisco de Macorís') ? 'selected' : ''; ?>>San Francisco de Macorís</option>
            <option value="Bonao" <?php echo ($cliente['estado'] == 'Bonao') ? 'selected' : ''; ?>>Bonao</option>
            <option value="Higüey" <?php echo ($cliente['estado'] == 'Higüey') ? 'selected' : ''; ?>>Higüey</option>
            <option value="Barahona" <?php echo ($cliente['estado'] == 'Barahona') ? 'selected' : ''; ?>>Barahona</option>
            <option value="San Cristóbal" <?php echo ($cliente['estado'] == 'San Cristóbal') ? 'selected' : ''; ?>>San Cristóbal</option>
        </select>

        <br><br>
        <button type="submit">Actualizar Cliente</button>
    </form>
</body>
</html>
