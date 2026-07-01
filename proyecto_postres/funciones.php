<?php
require_once 'conexion.php';
    //usado en comentario_estrella.php
function guardarComentario($usuario_id, $receta_id, $contenido) {
    global $conn;
    $contenido = $conn->real_escape_string($contenido);
    $sql = "INSERT INTO comentarios (usuario_id, receta_id, contenido) 
            VALUES ($usuario_id, $receta_id, '$contenido')";
    return $conn->query($sql);
}
//usado en receta_detalle.php
function obtenerComentarios($receta_id) {
    global $conn;
    $sql = "SELECT c.*, u.nombre_usuario 
            FROM comentarios c
            JOIN usuarios u ON c.usuario_id = u.id
            WHERE c.receta_id = " . intval($receta_id) . "
            ORDER BY c.fecha DESC";
    return $conn->query($sql);
}

function ruletaAleatoria() {
    global $conn;
    $sql = "SELECT id, imagen_url, titulo FROM recetas ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

?>
