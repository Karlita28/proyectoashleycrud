<?php
$servidor = "localhost";  
$usuario = "root";        
$clave = "1234";          
$bd = "suministrossa";    

$conexion = new mysqli($servidor, $usuario, $clave, $bd);

if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if (!$conexion->set_charset("utf8mb4")) {
    die("Error al establecer la codificación UTF-8: " . $conexion->error);
}
?>
