<?php
function mostrarVentas() {
    include 'Conexion.php'; // Incluimos la conexión aquí para asegurarla
    
    // Verificar conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $sql = "SELECT * FROM venta ORDER BY fecha_venta DESC";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Clase CSS condicional basada en el estado (puedes personalizar esto)
            $clase_estado = ($row['estado'] == 'Pendiente') ? 'pendiente' : '';
            
            echo "<tr class='$clase_estado'>
                    <td>".htmlspecialchars($row['id_venta'])."</td>
                    <td>".htmlspecialchars($row['fecha_venta'])."</td>
                    <td>".htmlspecialchars($row['fact_code'])."</td>
                    <td>".htmlspecialchars($row['id_cliente'])."</td>
                    <td>".htmlspecialchars($row['metodo_pago'])."</td>
                    <td>".htmlspecialchars($row['estado'])."</td>
                      <td>".htmlspecialchars($row['monto_total'])."</td>
                    <td>
                        <a href='Actualizar.php?id=".$row['id_venta']."' class='btn btn-sm btn-warning'>Editar</a>
                        <form action='Eliminar.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='id' value='".$row['id_venta']."'>
                            <button type='submit' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Estás seguro de eliminar esta venta?\")'>Eliminar</button>
                        </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7' class='text-center'>No hay ventas registradas</td></tr>";
    }

    $conexion->close();
}
?>