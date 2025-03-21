<?php
$servidor = "localhost";
$usuario = "root";     
$contrasena = "1234";    
$baseDeDatos = "suministrossa";

$conexion = new mysqli($servidor, $usuario, $contrasena, $baseDeDatos);

if ($conexion->connect_error) {
    die("Error en la conexión a la base de datos: " . $conexion->connect_error);
}
?>
