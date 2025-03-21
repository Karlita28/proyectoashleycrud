<?php
include 'Proveedores/Conexion.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!$conexion) {
    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = intval($_GET["id"]);  

    $stmt = $conexion->prepare("DELETE FROM proveedor WHERE id_proveedor = ?");

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: Index.php?mensaje=Proveedor eliminado correctamente");
        exit();
    } else {
        echo "Error al eliminar: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID inválido o no recibido.";
}

$result = $conexion->query("SELECT * FROM proveedor");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proveedores</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: rgb(255, 231, 255);
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            display: inline-block;
            background: linear-gradient(45deg, rgb(240, 115, 245), #6a1b9a);
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        a:hover {
            background: linear-gradient(45deg, #6a1b9a, #9b4dca);
        }
        p {
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <h1>Listado de Proveedores</h1>

    <?php
    if (isset($_GET['mensaje'])) {
        echo "<p style='color: green;'>" . $_GET['mensaje'] . "</p>";
    }
    ?>

    <a href="Insertar.php">Agregar Nuevo Proveedor</a><br><br>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Empresa</th>
                <th>Dirección</th>
                <th>Tiempo de Entrega Promedio (días)</th>
                <th>Condiciones de Pago</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id_proveedor"] . "</td>";
                    echo "<td>" . $row["nombre_empresa"] . "</td>";
                    echo "<td>" . $row["direccion"] . "</td>";
                    echo "<td>" . $row["tiempo_entrega_promedio"] . "</td>";
                    echo "<td>" . $row["condiciones_pago"] . "</td>";
                    echo "<td>" . $row["estado"] . "</td>";
                    echo "<td>
                            <a href='Actualizar.php?id=" . $row["id_proveedor"] . "'>Actualizar</a> | 
                            <a href='?id=" . $row["id_proveedor"] . "' onclick='return confirm(\"¿Estás seguro de que quieres eliminar este proveedor?\");'>Eliminar</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No hay proveedores registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>

<?php
$conexion->close();
?>
