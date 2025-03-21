<?php
include 'Proveedores/Conexion.php';
$sql = "SELECT * FROM proveedor";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>
            <td>{$fila['id_proveedor']}</td>
            <td>{$fila['nombre_empresa']}</td>
            <td>{$fila['direccion']}</td>
            <td>{$fila['tiempo_entrega_promedio']}</td>
            <td>{$fila['condiciones_pago']}</td>
            <td>{$fila['estado']}</td>
            <td>
                <a href='Actualizar.php?id={$fila['id_proveedor']}'>Editar</a> |
                <a href='Eliminar.php?id={$fila['id_proveedor']}'>Eliminar</a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No hay proveedores registrados.</td></tr>";
}
?>
