<?php
include 'Productos/Conexionp.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f7fd;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #6a4c9c;
            margin-top: 30px;
        }

        form {
            max-width: 700px;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 14px;
            color: #6a4c9c;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            background-color: #6a4c9c;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button:hover {
            background-color: #5a3e8a;
        }

        p {
            text-align: center;
            color: green;
            font-weight: bold;
        }

        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #d1b3e2;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e6e6ff;
        }

        a {
            color: #6a4c9c;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>Gestión de Productos</h2>

    <form action="Agregarp.php" method="POST">
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" name="nombre" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" required></textarea>

        <label for="stock_minimo">Stock Mínimo:</label>
        <input type="number" name="stock_minimo" required>

        <label for="stock_actual">Stock Actual:</label>
        <input type="number" name="stock_actual" required>

        <label for="id_categoria">ID Categoría:</label>
        <input type="number" name="id_categoria" required>

        <label for="costo_adquisicion">Costo de Adquisición:</label>
        <input type="number" step="0.01" name="costo_adquisicion" required>

        <label for="precio_unitario">Precio Unitario:</label>
        <input type="number" step="0.01" name="precio_unitario" required>

        <button type="submit">Agregar Producto</button>
    </form>

    <?php if (isset($_GET['mensaje'])): ?>
        <p><?= htmlspecialchars($_GET['mensaje']) ?></p>
    <?php endif; ?>

    <h3>Lista de Productos</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Stock Mínimo</th>
                <th>Stock Actual</th>
                <th>ID Categoría</th>
                <th>Costo</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM producto";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                while ($producto = $resultado->fetch_assoc()) {
                    echo "<tr>
                            <td>{$producto['id_producto']}</td>
                            <td>{$producto['nombre']}</td>
                            <td>{$producto['descripcion']}</td>
                            <td>{$producto['stock_minimo']}</td>
                            <td>{$producto['stock_actual']}</td>
                            <td>{$producto['id_categoria']}</td>
                            <td>{$producto['costo_adquisicion']}</td>
                            <td>{$producto['precio_unitario']}</td>
                            <td>
                                <a href='Actualizarp.php?id_producto={$producto['id_producto']}'>Editar</a> |
                                <a href='Eliminarp.php?id_producto={$producto['id_producto']}' onclick='return confirm( eliminar este producto?")'>Eliminar</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No hay productos registrados.</td></tr>";
            }
            $conexion->close();
            ?>
        </tbody>
    </table>
</body>
</html>
