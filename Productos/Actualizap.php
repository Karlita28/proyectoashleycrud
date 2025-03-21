<?php
include 'Productos/Conexionp.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET["id_producto"]) && is_numeric($_GET["id_producto"])) {
    $id = $_GET["id_producto"];

    $sql = "SELECT * FROM producto WHERE id_producto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit();
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_producto"])) {
    $id = $_POST["id_producto"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $stock_minimo = intval($_POST["stock_minimo"]);
    $stock_actual = intval($_POST["stock_actual"]);
    $id_categoria = intval($_POST["id_categoria"]);
    $costo_adquisicion = floatval($_POST["costo_adquisicion"]);
    $precio_unitario = floatval($_POST["precio_unitario"]);

    
    $sql = "UPDATE producto SET 
            nombre = ?, descripcion = ?, stock_minimo = ?, stock_actual = ?, 
            id_categoria = ?, costo_adquisicion = ?, precio_unitario = ? 
            WHERE id_producto = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssiiiidd", $nombre, $descripcion, $stock_minimo, $stock_actual, $id_categoria, $costo_adquisicion, $precio_unitario, $id);


    if ($stmt->execute()) {
        
        header("Location: Index.php?mensaje=Producto actualizado correctamente");
        exit();
    } else {
        echo "Error al actualizar el producto: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Producto</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #4A148C, #7B1FA2);
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        input {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        button {
            background: linear-gradient(45deg, #9C27B0, #6A1B9A);
            color: white;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s ease;
        }
        button:hover {
            background: linear-gradient(45deg, #6A1B9A, #9C27B0);
        }
        a {
            display: block;
            color: white;
            text-decoration: none;
            margin-top: 15px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Actualizar Producto</h1>
    <form action="Actualizar.php?id_producto=<?= $producto['id_producto'] ?>" method="POST">
        <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">

        <input type="text" name="nombre" value="<?= $producto['nombre'] ?>" placeholder="Nombre del producto" required>
        
        <textarea name="descripcion" placeholder="Descripción" required><?= $producto['descripcion'] ?></textarea>

        <input type="number" name="stock_minimo" value="<?= $producto['stock_minimo'] ?>" placeholder="Stock Mínimo" required>

        <input type="number" name="stock_actual" value="<?= $producto['stock_actual'] ?>" placeholder="Stock Actual" required>

        <input type="number" name="id_categoria" value="<?= $producto['id_categoria'] ?>" placeholder="ID Categoría" required>

        <input type="number" step="0.01" name="costo_adquisicion" value="<?= $producto['costo_adquisicion'] ?>" placeholder="Costo de Adquisición" required>

        <input type="number" step="0.01" name="precio_unitario" value="<?= $producto['precio_unitario'] ?>" placeholder="Precio Unitario" required>

        <button type="submit">Actualizar Producto</button>
    </form>

    <a href="Indexp.php">Volver al listado de productos</a>
</div>

</body>
</html>
