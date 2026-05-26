<?php
require_once 'conexion.php';
require 'funciones.php';
session_start();

$id = $_GET['id']; // ejemplo: receta.php?id=1

if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'estrella_ok') {
    echo '<p style="color: green;">✅ ¡Gracias por tu estrella!</p>';
}
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'estrella_actualizada') {
    echo '<p style="color: blue;">🔄 Actualizaste tu voto correctamente</p>';
}
// trae los datos de UNA receta (incluyendo votos_total y votos_count)
$sql = "SELECT r.*, u.nombre_usuario 
        FROM recetas r
        JOIN usuarios u ON r.usuario_id = u.id
        WHERE r.id = $id";
$receta = $conn->query($sql)->fetch_assoc();

//(quite el obtener comentario por esto)
$comentarios = obtenerComentarios($id);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($receta['titulo']); ?></title>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <h1><?php echo htmlspecialchars($receta['titulo']); ?></h1>
    
    <h2>Ingredientes</h2>
    <p><?php echo nl2br(htmlspecialchars($receta['ingredientes'])); ?></p>
    
    <h2>Instrucciones</h2>
    <p><?php echo nl2br(htmlspecialchars($receta['instrucciones'])); ?></p>
    
    <h2>Comentarios</h2>
    <?php while($com = $comentarios->fetch_assoc()): ?>
        <p><strong><?php echo htmlspecialchars($com['nombre_usuario']); ?>:</strong> 
           <?php echo htmlspecialchars($com['contenido']); ?></p>
    <?php endwhile; ?>
    <?php if(isset($_SESSION['usuario_id'])): ?>
        <form method="POST" action="guardar_comentario.php">
            <textarea name="contenido" required></textarea>
            <input type="hidden" name="receta_id" value="<?php echo $id; ?>">
            <button>Comentar</button>
        </form>
    <?php endif; ?>
    <!-- Mostrar promedio actual -->
    <h3>⭐ Promedio: 
        <?php 
        if($receta['votos_count'] > 0) {
            $promedio = round($receta['votos_total'] / $receta['votos_count'], 1);
            echo $promedio . " / 5 (" . $receta['votos_count'] . " votos)";
        } else {
            echo "Sin votos aún";
        }
        ?>
    </h3>
    <!-- Formulario para votar (solo si está logueado) -->
    <?php if(isset($_SESSION['usuario_id'])): ?>
        <form method="POST" action="guardar_estrella.php" style="margin: 10px 0;">
            <label>⭐ Tu puntuación:</label>
            <select name="puntuacion">
                <option value="1">⭐</option>
                <option value="2">⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="5">⭐⭐⭐⭐⭐</option>
            </select>
            <input type="hidden" name="receta_id" value="<?php echo $receta['id']; ?>">
            <button type="submit">Votar</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Iniciá sesión</a> para votar</p>
    <?php endif; ?>
    <?php
    $es_dueño = ($_SESSION['usuario_id'] ?? 0) == $receta['usuario_id'];
    $es_admin = ($_SESSION['rol'] ?? '') == 'admin';

    if($es_dueño || $es_admin): ?>
        <button onclick="document.getElementById('form-editar').style.display='block'">✏️ Editar receta</button>
        
        <div id="form-editar" style="display:none; margin-top:20px; padding:15px; border:1px solid #ddd;">
            <h3>Editar receta</h3>
           <form method="POST" action="actualizar_receta.php" enctype="multipart/form-data">
                <input type="hidden" name="receta_id" value="<?php echo $receta['id']; ?>">
                <label>Título:</label>
                <input type="text" name="titulo" value="<?php echo htmlspecialchars($receta['titulo']); ?>" required>
                <label>Descripción:</label>  
                <textarea name="descripcion" rows="2"><?php echo htmlspecialchars($receta['descripcion']); ?></textarea>
                <label>Ingredientes:</label>
                <textarea name="ingredientes" rows="4" required><?php echo htmlspecialchars($receta['ingredientes']); ?></textarea>
                <label>Instrucciones:</label>
                <textarea name="instrucciones" rows="5" required><?php echo htmlspecialchars($receta['instrucciones']); ?></textarea>
                  <!-- 👇 NUEVO CAMPO DE IMAGEN PONER EN GITHUB-->
        <label>Imagen actual:</label>
        <?php if($receta['imagen_url'] && file_exists($receta['imagen_url'])): ?>
            <img src="<?php echo $receta['imagen_url']; ?>" style="width:100px; display:block; margin:10px 0;">
            <p><small>Imagen actual</small></p>
        <?php else: ?>
            <p><small>Sin imagen actual</small></p>
        <?php endif; ?>
        <label>Cambiar imagen (opcional):</label>
        <input type="file" name="imagen" accept="image/*">
        <small>Si no seleccionás una imagen, se mantiene la actual</small>
                <button type="submit">Guardar cambios</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html>
