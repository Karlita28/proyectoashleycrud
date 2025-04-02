<?php
function mostrarVentas() {
    include 'conexion.php';
    
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Consulta optimizada con manejo de errores
    $sql = "SELECT 
                v.id_venta,
                DATE_FORMAT(v.fecha_venta, '%d/%m/%Y') as fecha_formateada,
                v.fact_code as factura,
                IFNULL(c.nombre, 'Cliente no encontrado') as cliente,
                v.metodo_pago as tipo_pago,
                v.monto_total as total,
                v.estado,
                v.id_cliente
            FROM venta v
            LEFT JOIN cliente c ON v.id_cliente = c.id_cliente
            ORDER BY v.fecha_venta DESC";

    $result = $conexion->query($sql);

    if (!$result) {
        die("Error en la consulta: " . $conexion->error);
    }

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $clase_estado = '';
            switch($row['estado']) {
                case 'Pendiente': $clase_estado = 'estado-pendiente'; break;
                case 'Pagada': $clase_estado = 'estado-pagada'; break;
                case 'Cancelada': $clase_estado = 'estado-cancelada'; break;
                default: $clase_estado = '';
            }
            
            echo "<tr class='$clase_estado'>
                    <td>".htmlspecialchars($row['id_venta'])."</td>
                    <td>".htmlspecialchars($row['fecha_formateada'])."</td>
                    <td>".htmlspecialchars($row['factura'])."</td>
                    <td>".htmlspecialchars($row['cliente'])." (ID: ".$row['id_cliente'].")</td>
                    <td>".htmlspecialchars($row['tipo_pago'])."</td>
                    <td class='text-end'>S/ ".number_format($row['total'], 2)."</td>
                    <td class='text-center'>".htmlspecialchars($row['estado'])."</td>
                    <td class='text-center'>
                        <a href='Actualizar.php?id=".$row['id_venta']."' class='btn-accion btn-editar' title='Editar'>
                            <i class='fas fa-edit'></i>
                        </a>
                        <form action='eliminar.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='id' value='".$row['id_venta']."'>
                            <button type='submit' class='btn-accion btn-eliminar' title='Eliminar' onclick='return confirm(\"¿Estás seguro de eliminar esta venta?\")'>
                                <i class='fas fa-trash-alt'></i>
                            </button>
                        </form>
                        <a href='Detalle.php?id=".$row['id_venta']."' class='btn-accion btn-detalle' title='Ver detalle'>
                            <i class='fas fa-eye'></i>
                        </a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='8' class='text-center py-4'>No se encontraron ventas registradas</td></tr>";
    }

    $conexion->close();
}
?>