<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $ingredientes = $conn->real_escape_string($_POST['ingredientes']);
    $instrucciones = $conn->real_escape_string($_POST['instrucciones']);
    $tiempo = $_POST['tiempo_preparacion'];
    $dificultad = $_POST['dificultad'];
    $categoria_id = $_POST['categoria_id'];
    $usuario_id = $_SESSION['usuario_id'];
    
    // imagen
    $imagen_url = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $carpeta_destino = 'img/';
        
        //nombre unico para la imagen
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombre_archivo = time() . '_' . rand(1000, 9999) . '.' . $extension;
        $ruta_completa = $carpeta_destino . $nombre_archivo;
        
        // Tipos de imagen permitidos
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        
        if (in_array($_FILES['imagen']['type'], $tipos_permitidos)) {
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_completa)) {
                $imagen_url = $ruta_completa;
            } else {
                $error = "Error al subir la imagen";
            }
        } else {
            $error = "Solo se permiten JPG, PNG o GIF";
        }
    }
    
    // guardar en BD (con o sin imagen)
    $sql = "INSERT INTO recetas (titulo, descripcion, ingredientes, instrucciones, 
            tiempo_preparacion, dificultad, categoria_id, usuario_id, imagen_url) 
            VALUES ('$titulo', '$descripcion', '$ingredientes', '$instrucciones', 
                    '$tiempo', '$dificultad', '$categoria_id', '$usuario_id', '$imagen_url')";
    
    if ($conn->query($sql)) {
        $mensaje = "✅ Receta subida correctamente";
    } else {
        $error = "❌ Error: " . $conn->error;
    }
}

$categorias = $conn->query("SELECT * FROM categorias");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Subir receta</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        form { max-width: 600px; margin: auto; }
        input, select, textarea { width: 100%; padding: 8px; margin: 5px 0 15px; }
        button { background: #8b5a2b; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .mensaje { background: #d4edda; padding: 10px; margin: 10px 0; }
        .error { background: #f8d7da; padding: 10px; margin: 10px 0; }
        .preview { max-width: 200px; margin-top: 10px; }
    </style>
</head>
<body>

<h1>📝 Subir nueva receta</h1>

<?php if($mensaje): ?>
    <div class="mensaje"><?php echo $mensaje; ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Título *</label>
    <input type="text" name="titulo" required>
    
    <label>Descripción</label>
    <textarea name="descripcion" rows="2"></textarea>
    
    <label>Ingredientes *</label>
    <textarea name="ingredientes" rows="4" required></textarea>
    
    <label>Instrucciones *</label>
    <textarea name="instrucciones" rows="5" required></textarea>
    
    <label>Tiempo (minutos)</label>
    <input type="number" name="tiempo_preparacion" value="30">
    
    <label>Dificultad</label>
    <select name="dificultad">
        <option>Fácil</option>
        <option>Media</option>
        <option>Difícil</option>
    </select>
    
    <label>Categoría</label>
    <select name="categoria_id">
        <?php while($cat = $categorias->fetch_assoc()): ?>
            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nombre']; ?></option>
        <?php endwhile; ?>
    </select>
    
    <label>Imagen del postre</label>
    <input type="file" name="imagen" accept="image/*">
    
    <button type="submit">📤 Subir receta</button>
</form>

<p><a href="index.php">← Volver al inicio</a></p>

</body>
</html>