<?php
session_start();
require_once 'conexion.php';
require_once 'funciones.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$receta_id = intval($_POST['receta_id']);
$puntuacion = intval($_POST['puntuacion']);
$contenido = trim($_POST['contenido']); // trim() saca espacios al principio y final

$error = null;

// Validar comentario
if (empty($contenido)) {
    $error = "vacio";
}

// Validar puntuación
if ($puntuacion < 1 || $puntuacion > 5) {
    $error = "voto_invalido";
}

if ($error) {
    header("Location: receta_detalle.php?id=$receta_id&error=$error");
    exit;
}

// 1. Guardar el comentario
if (guardarComentario($usuario_id, $receta_id, $contenido)) {
    // 2. Guardar o actualizar el voto
    $check = $conn->query("SELECT * FROM valoraciones WHERE usuario_id = $usuario_id AND receta_id = $receta_id");
    
    if ($check->num_rows == 0) {
        // NUEVO VOTO
        $conn->query("INSERT INTO valoraciones (usuario_id, receta_id, puntuacion) 
                      VALUES ($usuario_id, $receta_id, $puntuacion)");
        $mensaje = "ok";
    } else {
        // ACTUALIZAR VOTO
        $conn->query("UPDATE valoraciones 
                      SET puntuacion = $puntuacion, fecha = NOW() 
                      WHERE usuario_id = $usuario_id AND receta_id = $receta_id");
        $mensaje = "actualizado";
    }
    
    // 3. Actualizar los totales en la tabla recetas
    $conn->query("UPDATE recetas SET 
                  votos_total = (SELECT SUM(puntuacion) FROM valoraciones WHERE receta_id = $receta_id),
                  votos_count = (SELECT COUNT(*) FROM valoraciones WHERE receta_id = $receta_id)
                  WHERE id = $receta_id");
    
    header("Location: receta_detalle.php?id=$receta_id&mensaje=$mensaje");
} else {
    header("Location: receta_detalle.php?id=$receta_id&error=db");
}
exit;
?>