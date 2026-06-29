<?php
require_once 'conexion.php';

/* no se usa creo
function obtenerRecetas($categoria_id = null) {
    global $conn;
    $sql = "SELECT r.*, c.nombre as categoria 
            FROM recetas r 
            LEFT JOIN categorias c ON r.categoria_id = c.id";
    if ($categoria_id) {
        $sql .= " WHERE r.categoria_id = " . intval($categoria_id);
    }
    $sql .= " ORDER BY r.fecha_creacion DESC";
    return $conn->query($sql);
} */

function obtenerRecetaPorId($id) {
    global $conn;
    $sql = "SELECT r.*, c.nombre as categoria, u.nombre_usuario 
            FROM recetas r 
            LEFT JOIN categorias c ON r.categoria_id = c.id
            LEFT JOIN usuarios u ON r.usuario_id = u.id
            WHERE r.id = " . intval($id);
    return $conn->query($sql)->fetch_assoc();
}

function guardarComentario($usuario_id, $receta_id, $contenido) {
    global $conn;
    $contenido = $conn->real_escape_string($contenido);
    $sql = "INSERT INTO comentarios (usuario_id, receta_id, contenido) 
            VALUES ($usuario_id, $receta_id, '$contenido')";
    return $conn->query($sql);
}

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
function guardarHistorialRuleta($usuario_id, $receta_id) {
    global $conn;
    $sql = "INSERT INTO ruleta_historial (usuario_id, receta_id) 
            VALUES ($usuario_id, $receta_id)";
    return $conn->query($sql);
}
?>
