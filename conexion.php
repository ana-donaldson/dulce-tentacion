<?php
$servidor = "localhost";     
$usuario = "root";          
$contraseña = "";            
$base_datos = "proyecto_postres";  

$conn = new mysqli($servidor, $usuario, $contraseña, $base_datos);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");  
?>