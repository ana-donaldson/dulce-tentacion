<?php
session_start();
require 'conexion.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
$usuario_id = $_SESSION['usuario_id'];

if (isset($_GET['eliminar'])) {
    $comentario_id = intval($_GET['eliminar']);
    $receta_id = intval($_GET['receta_id']);
    
    // Verificar si el usuario es dueño del comentario o admin
    $check = $conn->query("SELECT usuario_id FROM comentarios WHERE id = $comentario_id");
    
    if (!$check) {
        header("Location: receta_detalle.php?id=$receta_id&error=db");
        exit;
    }
    
    $comentario = $check->fetch_assoc();
    
    if (!$comentario) {
        header("Location: receta_detalle.php?id=$receta_id&error=no_permiso");
        exit;
    }
    
    $es_dueno = ($comentario['usuario_id'] == $usuario_id);
    $es_admin = ($_SESSION['rol'] == 'admin');
    
    if ($es_dueno || $es_admin) {
        $conn->query("DELETE FROM comentarios WHERE id = $comentario_id");
        header("Location: receta_detalle.php?id=$receta_id&mensaje=comentario_eliminado");
    } else {
        header("Location: receta_detalle.php?id=$receta_id&error=no_permiso");
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar'])) {
    $comentario_id = intval($_POST['comentario_id']);
    $receta_id = intval($_POST['receta_id']);  
    $nuevo_contenido = trim($_POST['nuevo_contenido']);
    
    if (empty($nuevo_contenido)) {
        header("Location: receta_detalle.php?id=$receta_id&error=comentario_vacio");
        exit;
    }
    // Verificar si el usuario es dueño del comentario o admin
    $check = $conn->query("SELECT usuario_id FROM comentarios WHERE id = $comentario_id");
    $comentario = $check->fetch_assoc();
    
    if (!$comentario) {
        header("Location: receta_detalle.php?id=$receta_id&error=no_permiso");
        exit;
    }
    
    $es_dueno = ($comentario['usuario_id'] == $usuario_id);
    $es_admin = ($_SESSION['rol'] == 'admin');
    
    if ($es_dueno || $es_admin) {
        $nuevo_contenido = $conn->real_escape_string($nuevo_contenido);
        $conn->query("UPDATE comentarios SET contenido = '$nuevo_contenido', fecha = NOW() WHERE id = $comentario_id");
        header("Location: receta_detalle.php?id=$receta_id&mensaje=comentario_editado");
    } else {
        header("Location: receta_detalle.php?id=$receta_id&error=no_permiso");
    }
    exit;
}

// Si no es ni eliminar ni editar, redirigir al inicio
header('Location: index.php');
exit;
?>