<?php
require 'conexion.php';
require 'funciones.php';
session_start();

$id = $_GET['id']; 

if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'ok') {
    echo '<p style="color: green;">✅ ¡Gracias por tu comentario y estrella!</p>';
}
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'actualizado') {
    echo '<p style="color: blue;">🔄 Actualizaste tu comentario y voto correctamente</p>';
}
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'comentario_editado') {
    echo '<p style="color: green;">✅ Comentario editado correctamente</p>';
}
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'comentario_eliminado') {
    echo '<p style="color: green;">✅ Comentario eliminado correctamente</p>';
}
if (isset($_GET['error']) && $_GET['error'] == 'vacio') {
    echo '<p style="color: red;">❌ El comentario no puede estar vacío</p>';
}
if (isset($_GET['error']) && $_GET['error'] == 'voto_invalido') {
    echo '<p style="color: red;">❌ La puntuación debe ser entre 1 y 5 estrellas</p>';
}
if (isset($_GET['error']) && $_GET['error'] == 'db') {
    echo '<p style="color: red;">❌ Error al guardar. Intentá de nuevo.</p>';
}
if (isset($_GET['error']) && $_GET['error'] == 'no_permiso') {
    echo '<p style="color: red;">❌ No tenés permiso para hacer eso</p>';
}
if (isset($_GET['error']) && $_GET['error'] == 'comentario_vacio') {
    echo '<p style="color: red;">❌ El comentario no puede estar vacío</p>';
}
// trae los datos de UNA receta (incluyendo votos_total y votos_count)
$sql = "SELECT r.*, u.nombre_usuario 
        FROM recetas r
        JOIN usuarios u ON r.usuario_id = u.id
        WHERE r.id = $id";
$receta = $conn->query($sql)->fetch_assoc();
if (!$receta) {
    echo "<p style='color: red;'>❌ Error: No se encontró la receta solicitada.</p>";
    echo "<p><a href='index.php'>Volver al inicio</a></p>";
    exit;
}
// trae los comentarios
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
        <div class="comentario" id="comentario-<?php echo $com['id']; ?>">
            <?php 
            $es_dueno_comentario = ($_SESSION['usuario_id'] ?? 0) == $com['usuario_id'];
            $es_admin_comentario = ($_SESSION['rol'] ?? '') == 'admin';     
            // Modo edición (oculto por defecto)
            if($es_dueno_comentario || $es_admin_comentario): ?>
                <div id="editar-comentario-<?php echo $com['id']; ?>" style="display: none;">
                    <form method="POST" action="eliminar_editar_comentario.php" style="margin: 10px 0;">
                        <textarea name="nuevo_contenido" rows="3" style="width: 100%;"><?php echo htmlspecialchars($com['contenido']); ?></textarea>
                        <input type="hidden" name="comentario_id" value="<?php echo $com['id']; ?>">
                         <input type="hidden" name="editar" value="1">
                         <input type="hidden" name="receta_id" value="<?php echo $id; ?>">
                        <button type="submit" class="btn-guardar">💾 Guardar cambios</button>
                        <button type="button" class="btn-cancelar" onclick="cancelarEdicion(<?php echo $com['id']; ?>)">❌ Cancelar</button>
                    </form>
                </div>
            <?php endif; ?>        
            <!-- Vista normal -->
            <div id="ver-comentario-<?php echo $com['id']; ?>">
                <p><strong><?php echo htmlspecialchars($com['nombre_usuario']); ?>:</strong> 
                   <?php echo nl2br(htmlspecialchars($com['contenido'])); ?></p>
                <small><?php echo $com['fecha']; ?></small>
                
                <?php if($es_dueno_comentario || $es_admin_comentario): ?>
                    <div style="margin-top: 5px;">
                        <button onclick="mostrarEdicion(<?php echo $com['id']; ?>)" class="btn-editar">✏️ Editar</button>
                        <a href="eliminar_editar_comentario.php?eliminar=<?php echo $com['id']; ?>&receta_id=<?php echo $id; ?>" 
                           onclick="return confirm('¿Eliminar este comentario?')" 
                           class="btn-eliminar">🗑️ Eliminar</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
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
    <?php if(isset($_SESSION['usuario_id'])): ?>
        <div class="form-comentario">
            <form method="POST" action="comentario_estrella.php">
                <h3>💬 Dejá tu comentario y puntuación</h3>            
                <label>⭐ Tu puntuación (1 a 5 estrellas):</label>
                <select name="puntuacion" required>
                    <option value="">Seleccioná...</option>
                    <option value="1">⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                </select>
                <label>💬 Tu comentario:</label>
                <textarea name="contenido" rows="3" placeholder="Escribí acá tu comentario..." required></textarea>
                <input type="hidden" name="receta_id" value="<?php echo $id; ?>">
                <button type="submit" class="btn-guardar">📤 Enviar comentario y puntuación</button>
            </form>
        </div>
    <?php else: ?>
        <p><a href="login.php">Iniciá sesión</a> para comentar y votar</p>
    <?php endif; ?>

    <?php
    $es_dueño = ($_SESSION['usuario_id'] ?? 0) == $receta['usuario_id'];
    $es_admin = ($_SESSION['rol'] ?? '') == 'admin';

    if($es_dueño || $es_admin): ?>
        <button onclick="document.getElementById('form-editar').style.display='block'">✏️ Editar receta</button>
        
        <div id="form-editar" style="display:none; margin-top:20px; padding:15px; border:1px solid #ddd; border-radius: 10px;">
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
                
                <label>Dificultad:</label>
        <select name="dificultad" required>
            <option value="Fácil" <?php echo ($receta['dificultad'] == 'Fácil') ? 'selected' : ''; ?>>Fácil</option>
            <option value="Media" <?php echo ($receta['dificultad'] == 'Media') ? 'selected' : ''; ?>>Media</option>
            <option value="Difícil" <?php echo ($receta['dificultad'] == 'Difícil') ? 'selected' : ''; ?>>Difícil</option>
        </select>


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
                
                <button type="submit" class="btn-guardar">Guardar cambios</button>
            </form>
        </div>
    <?php endif; ?>
    <script>
    function mostrarEdicion(comentarioId) {
        document.getElementById('ver-comentario-' + comentarioId).style.display = 'none';
        document.getElementById('editar-comentario-' + comentarioId).style.display = 'block';
    }
    function cancelarEdicion(comentarioId) {
        document.getElementById('ver-comentario-' + comentarioId).style.display = 'block';
        document.getElementById('editar-comentario-' + comentarioId).style.display = 'none';
    }
    </script>
</body>
</html>
