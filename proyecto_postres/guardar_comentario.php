<?php
session_start();
require_once 'funciones.php';

// verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

//verificar que lleguen los datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $receta_id = intval($_POST['receta_id']);
    $contenido = trim($_POST['contenido']);
    
    if (!empty($contenido)) {
        if (guardarComentario($usuario_id, $receta_id, $contenido)) {
            //volver a la receta
            header("Location: receta_detalle.php?id=$receta_id&mensaje=ok");
        } else {
            header("Location: receta_detalle.php?id=$receta_id&error=db");
        }
    } else {
        header("Location: receta_detalle.php?id=$receta_id&error=vacio");
    }
} else {
    header('Location: index.php');
}
exit;
?>