<?php
$servidor = "localhost";
$usuario = "root"; 
$clave = "1234"; 
$bd = "suministrossa"; 

$conexion = new mysqli($host, $usuario, $clave, $bd);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>