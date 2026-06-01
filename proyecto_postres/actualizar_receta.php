<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit; }
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['receta_id']);
    //verificar permisos
    $check = $conn->query("SELECT usuario_id FROM recetas WHERE id = $id");
    $receta = $check->fetch_assoc();
    
    $es_dueño = ($receta['usuario_id'] == $_SESSION['usuario_id']);
    $es_admin = ($_SESSION['rol'] == 'admin');
    
    if ($es_dueño || $es_admin) {
            //procesa imagen (si hay)
        $imagen_url = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $carpeta_destino = 'img/'; 
            if (!file_exists($carpeta_destino)) {
                mkdir($carpeta_destino, 0777, true);
            }        
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombre_archivo = time() . '_' . rand(1000, 9999) . '.' . $extension;
            $ruta_completa = $carpeta_destino . $nombre_archivo;
            
            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            
            if (in_array($_FILES['imagen']['type'], $tipos_permitidos)) {
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_completa)) {
                    $imagen_url = $ruta_completa;
                }
            }
        }
        //preparar consulta SQL 
        if ($imagen_url) {
            // Con imagen nueva
            $sql = "UPDATE recetas SET 
                    titulo = ?,
                    descripcion = ?,
                    ingredientes = ?,
                    instrucciones = ?,
                    imagen_url = ?
                    WHERE id = ?";          
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", 
                $_POST['titulo'], 
                $_POST['descripcion'], 
                $_POST['ingredientes'], 
                $_POST['instrucciones'], 
                $imagen_url,
                $id );
        } else {
            //sin imagen nueva (solo datos)
            $sql = "UPDATE recetas SET 
                    titulo = ?,
                    descripcion = ?,
                    ingredientes = ?,
                    instrucciones = ?
                    WHERE id = ?";   
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", 
                $_POST['titulo'], 
                $_POST['descripcion'], 
                $_POST['ingredientes'], 
                $_POST['instrucciones'], 
                $id
            );
        }
        //ejecutar
        if ($stmt->execute()) {
            header("Location: receta_detalle.php?id=$id");
            exit;
        } else {
            echo "Error al actualizar: " . $stmt->error;
        }
    } else {
        echo "No tenés permiso para editar esta receta.";
    }
} else {
    header('Location: index.php');
    exit;
}
?>
