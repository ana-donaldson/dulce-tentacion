<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id = intval($_POST['receta_id']);
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $ingredientes = $conn->real_escape_string($_POST['ingredientes']);
    $instrucciones = $conn->real_escape_string($_POST['instrucciones']);
    
    //ver si la receta pertenece al usuario o es admin
    $check = $conn->query("SELECT usuario_id FROM recetas WHERE id = $id");
    $receta = $check->fetch_assoc();
    
    $es_dueño = ($receta['usuario_id'] == $_SESSION['usuario_id']);
    $es_admin = ($_SESSION['rol'] == 'admin');
    
    if ($es_dueño || $es_admin) {
        $sql = "UPDATE recetas SET 
                titulo = '$titulo',
                descripcion = '$descripcion',
                ingredientes = '$ingredientes',
                instrucciones = '$instrucciones'
                WHERE id = $id";
        
        if ($conn->query($sql)) {
            header("Location: receta_detalle.php?id=$id");
            exit;
        } else {
            echo "Error al actualizar: " . $conn->error;
        }
    } else {
        echo "No tenés permiso para editar esta receta.";
    }
} else {
    header('Location: index.php');
    exit;
}
?>