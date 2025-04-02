<?php
function mostrarProductos() {
    include 'conexion.php'; // Incluimos la conexión aquí para asegurarla
    
    // Verificar conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $sql = "SELECT * FROM almacen ORDER BY nombre";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $clase_stock = ($row['stock_actual'] < $row['stock_minimo']) ? 'bajo-stock' : '';
            
            echo "<tr class='$clase_stock'>
                    <td>".htmlspecialchars($row['codigo'])."</td>
                    <td>".htmlspecialchars($row['nombre'])."</td>
                    <td>".htmlspecialchars($row['ubicacion'])."</td>
                    <td>".htmlspecialchars($row['stock_actual'])."</td>
                    <td>".htmlspecialchars($row['stock_minimo'])."</td>
                    <td>".htmlspecialchars($row['stock_maximo'])."</td>
                    <td>".htmlspecialchars($row['unidad_medida'])."</td>
                    <td>".number_format($row['costo'], 2)."</td>
                    <td>".number_format($row['precio_venta'], 2)."</td>
                    <td>
                        <a href='Actualizar.php?id=".$row['id_producto']."' class='btn btn-sm btn-warning'>Editar</a>
                        <form action='Eliminar.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='id' value='".$row['id_producto']."'>
                            <button type='submit' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Estás seguro de eliminar este producto?\")'>Eliminar</button>
                        </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='10' class='text-center'>No hay productos registrados</td></tr>";
    }

    $conexion->close();
}
?>