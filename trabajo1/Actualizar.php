<?php
include 'Proveedores/Conexion.php';

if (isset($_GET['id'])) {
    $id_proveedor = $_GET['id'];

    $stmt = $conexion->prepare("SELECT * FROM proveedor WHERE id_proveedor = ?");
    $stmt->bind_param("i", $id_proveedor);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $proveedor = $resultado->fetch_assoc();
    } else {
        die("Proveedor no encontrado");
    }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre_empresa"];
    $direccion = $_POST["direccion"];
    $tiempo_entrega = $_POST["tiempo_entrega_promedio"];
    $condiciones_pago = $_POST["condiciones_pago"];
    $estado = $_POST["estado"];
    $stmt = $conexion->prepare("UPDATE proveedores SET nombre_empresa = ?, direccion = ?, tiempo_entrega_promedio = ?, condiciones_pago = ?, estado = ? WHERE id_proveedor = ?");
    $stmt->bind_param("ssissi", $nombre, $direccion, $tiempo_entrega, $condiciones_pago, $estado, $id_proveedor);

    if ($stmt->execute()) {
        header("Location: Index.php?mensaje=Proveedor actualizado correctamente");
        exit();
    } else {
        echo "Error al actualizar proveedor: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Proveedor</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color:rgb(255, 231, 255);
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 30px;
        }
        form {
            max-width: 500px;
            margin: 50px auto;
            background-color: #232323;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(248, 213, 255, 0.1);
        }
        input, select, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="text"], input[type="number"] {
            background-color:rgb(255, 255, 255);
        }
        select {
            background-color:rgb(255, 255, 255);
        }
        button {
            background: linear-gradient(45deg,rgb(240, 115, 245), #6a1b9a);
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s ease;
        }
        button:hover {
            background: linear-gradient(45deg, #6a1b9a, #9b4dca);
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            color: #333;
        }
        a:hover {
            color: #6a1b9a;
        }
    </style>
</head>
<body>

    <h1>Actualizar Proveedor</h1>

    <form action="Actualizar.php?id=<?php echo $id_proveedor; ?>" method="POST">
        <input type="text" name="nombre_empresa" value="<?php echo $proveedor['nombre_empresa']; ?>" placeholder="Nombre de la empresa" required><br>
        <input type="text" name="direccion" value="<?php echo $proveedor['direccion']; ?>" placeholder="Dirección" required><br>
        <input type="number" name="tiempo_entrega_promedio" value="<?php echo $proveedor['tiempo_entrega_promedio']; ?>" placeholder="Tiempo de entrega (días)" required><br>
        <input type="text" name="condiciones_pago" value="<?php echo $proveedor['condiciones_pago']; ?>" placeholder="Condiciones de pago" required><br>
        <select name="estado" required>
            <option value="Activo" <?php if ($proveedor['estado'] == 'Activo') echo 'selected'; ?>>Activo</option>
            <option value="Inactivo" <?php if ($proveedor['estado'] == 'Inactivo') echo 'selected'; ?>>Inactivo</option>
        </select><br>
        <button type="submit">Actualizar Proveedor</button>
    </form>

    <a href="Index.php">Volver al listado de proveedores</a>

</body>
</html>
