<?php
session_start();
require_once 'conexion.php';

// Solo usuarios logueados pueden votar
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$receta_id = intval($_POST['receta_id']);
$puntuacion = intval($_POST['puntuacion']);

// que la puntuación sea válida (1-5)
if ($puntuacion < 1 || $puntuacion > 5) {
    header("Location: receta_detalle.php?id=$receta_id&error=voto_invalido");
    exit;
}
// ver si el usuario ya votó esta receta
$check = $conn->query("SELECT * FROM valoraciones WHERE usuario_id = $usuario_id AND receta_id = $receta_id");

if ($check->num_rows == 0) {
    // NUEVO VOTO: insertar
    $conn->query("INSERT INTO valoraciones (usuario_id, receta_id, puntuacion) 
                  VALUES ($usuario_id, $receta_id, $puntuacion)");
    $mensaje = "estrella_ok";
} else {
    // YA VOTO: actualizar el voto existente
    $conn->query("UPDATE valoraciones 
                  SET puntuacion = $puntuacion, fecha = NOW() 
                  WHERE usuario_id = $usuario_id AND receta_id = $receta_id");
    $mensaje = "estrella_actualizada";
}

// actualizar votos_total y votos_count en la tabla recetas
$conn->query("UPDATE recetas SET 
              votos_total = (SELECT SUM(puntuacion) FROM valoraciones WHERE receta_id = $receta_id),
              votos_count = (SELECT COUNT(*) FROM valoraciones WHERE receta_id = $receta_id)
              WHERE id = $receta_id");

header("Location: receta_detalle.php?id=$receta_id&mensaje=$mensaje");
exit;
?>