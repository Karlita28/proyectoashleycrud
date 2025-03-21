<?php
include 'Conexion.php'; // Incluye la conexión a la base de datos

// Obtén todos los detalles de compra
$sql = "SELECT * FROM detallecompra";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    // Muestra los detalles de compra
    while ($row = $result->fetch_assoc()) {
        echo "ID Compra: " . $row['ID_Compra'] . " - ID Producto: " . $row['ID_Producto'] . " - Cantidad: " . $row['Cantidad'] . " - Costo Unitario: " . $row['CostoUnitario'] . " - Costo Total: " . $row['CostoTotal'] . "<br>";
    }
} else {
    echo "No hay detalles de compra disponibles.";
}

$conexion->close();
?>
