<?php
$servidor = "localhost";
$usuario = "root"; 
$clave = "1234"; 
$bd = "suministrossa"; 

$conexion = new mysqli($servidor, $usuario, $clave, $bd);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Establecer codificación UTF-8
$conexion->set_charset("utf8mb4");
?>
