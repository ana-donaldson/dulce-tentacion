<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit; }
$mensaje = '';
$error = '';
$receta_subida = false; //para saber si se subio (ayuda a css a
                       //mostrar el mensaje de exito)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['volver_a_subir'])) {
    
    //si no llega o viene vacio, usa facil por descarte 
    if (!isset($_POST['dificultad']) || trim($_POST['dificultad']) == '') {
        $dificultad = 'Fácil'; 
        } else {
        $dificultad = $_POST['dificultad'];    }
    $imagen_url = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $carpeta_destino = 'img/';   
        if (!file_exists($carpeta_destino)) {
            mkdir($carpeta_destino, 0777, true); }
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombre_archivo = time() . '_' . rand(1000, 9999) . '.' . $extension;
        $ruta_completa = $carpeta_destino . $nombre_archivo;
        
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        
        if (in_array($_FILES['imagen']['type'], $tipos_permitidos)) {
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_completa)) {
                $imagen_url = $ruta_completa;
            } else {
                $error = "Error al subir la imagen";    }
        } else {
            $error = "Solo se permite JPG, PNG o GIF"; }
    }
    if (empty($error)) {
        $sql = "INSERT INTO recetas (titulo, descripcion, ingredientes, instrucciones, 
                tiempo_preparacion, dificultad, categoria_id, usuario_id, imagen_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssiis",
            $_POST['titulo'],
            $_POST['descripcion'],
            $_POST['ingredientes'],
            $_POST['instrucciones'],
            $_POST['tiempo_preparacion'],
            $dificultad,      
            $_POST['categoria_id'],
            $_SESSION['usuario_id'],
            $imagen_url        );
        if ($stmt->execute()) {        
            $mensaje = "✅ ¡Receta subida correctamente!";
            $receta_subida = true; //marca que se subio cuando es true(para css)
        } else {
            $error = "❌ Error al guardar: " . $stmt->error; }
    }
}
$categorias = $conn->query("SELECT * FROM categorias");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Subir receta</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #fef8f0; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        h1 { color: #8b5a2b; }
        input, select, textarea { width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #8b5a2b; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .mensaje { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; text-align: center; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .opciones { text-align: center; margin-top: 20px; }
        .opciones a { display: inline-block; margin: 0 10px; padding: 10px 20px; background: #8b5a2b; color: white; text-decoration: none; border-radius: 5px; }
        .opciones a.volver { background: #6c757d; }
        .formulario { display: <?php echo $receta_subida ? 'none' : 'block'; ?>; }
    </style>
</head>
<body>
<div class="container">
    <h1>📝 Subir nueva receta</h1>
    <?php if($mensaje): ?>
        <div class="mensaje">
            <?php echo $mensaje; ?>
            <div class="opciones">
                <a href="subir_receta.php">➕ Subir otra receta</a>
                <a href="index.php" class="volver">🏠 Volver al inicio</a>
            </div>
        </div>
    <?php endif; ?>  
    <?php if($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <div class="formulario">
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
                <option value="Fácil" selected>Fácil</option>
                <option value="Media">Media</option>
                <option value="Difícil">Difícil</option>
            </select>
            <label>Categoría</label>
            <select name="categoria_id">
                <?php while($cat = $categorias->fetch_assoc()): ?>
                //en lugar de que las categorias sean opciones fijas
                //si agregamos o borramos una, siempre se va a mostrar
                //lo que exista en la tabla. ej, primavera o otoño
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nombre']; ?></option>
                <?php endwhile; ?>
            </select>     
            <label>Imagen del postre</label>
            <input type="file" name="imagen" accept="image/*">
            <button type="submit">📤 Subir receta</button>
        </form>
        <p style="text-align: center;"><a href="index.php">← Volver al inicio</a></p>
    </div>
</div>
</body>
</html>
