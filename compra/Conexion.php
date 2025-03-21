<?php
$servidor = "localhost";
$usuario = "root"; 
$clave = "1234"; 
$bd = "suministrossa"; 

// Crear la conexión
$conn = new mysqli($servidor, $usuario, $clave, $bd);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer codificación UTF-8
$conn->set_charset("utf8mb4");
?>