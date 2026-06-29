<?php
require_once 'conexion.php';
require 'funciones.php';
session_start();

$id = intval($_GET['id']);

// Mensajes para comentarios y estrellas
$estrella_msg = '';
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'estrella_ok') {
    $estrella_msg = 'ok';
}
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'estrella_actualizada') {
    $estrella_msg = 'actualizada';
}
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'ok') {
    $estrella_msg = 'comentario_ok';
}
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'actualizado') {
    $estrella_msg = 'comentario_actualizado';
}
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'comentario_editado') {
    $estrella_msg = 'comentario_editado';
}
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'comentario_eliminado') {
    $estrella_msg = 'comentario_eliminado';
}
if (isset($_GET['error']) && $_GET['error'] == 'vacio') {
    $estrella_msg = 'error_vacio';
}
if (isset($_GET['error']) && $_GET['error'] == 'voto_invalido') {
    $estrella_msg = 'error_voto_invalido';
}
if (isset($_GET['error']) && $_GET['error'] == 'db') {
    $estrella_msg = 'error_db';
}
if (isset($_GET['error']) && $_GET['error'] == 'no_permiso') {
    $estrella_msg = 'error_no_permiso';
}
if (isset($_GET['error']) && $_GET['error'] == 'comentario_vacio') {
    $estrella_msg = 'error_comentario_vacio';
}

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

$comentarios = obtenerComentarios($id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($receta['titulo']); ?> — DulceTentación</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="detalle-container">

  <a href="recetas.php" class="volver">← Volver a recetas</a>

  <!-- Mensajes de alerta -->
  <?php if($estrella_msg == 'ok'): ?>
    <div class="alerta alerta-ok">✅ ¡Gracias por tu comentario y calificación!</div>
  <?php elseif($estrella_msg == 'actualizada'): ?>
    <div class="alerta alerta-info">🔄 Actualizaste tu comentario y voto correctamente</div>
  <?php elseif($estrella_msg == 'comentario_ok'): ?>
    <div class="alerta alerta-ok">✅ ¡Gracias por tu comentario y calificación!</div>
  <?php elseif($estrella_msg == 'comentario_actualizado'): ?>
    <div class="alerta alerta-info">🔄 Actualizaste tu comentario y voto correctamente</div>
  <?php elseif($estrella_msg == 'comentario_editado'): ?>
    <div class="alerta alerta-ok">✅ Comentario editado correctamente</div>
  <?php elseif($estrella_msg == 'comentario_eliminado'): ?>
    <div class="alerta alerta-ok">✅ Comentario eliminado correctamente</div>
  <?php elseif($estrella_msg == 'error_vacio'): ?>
    <div class="alerta alerta-error">❌ El comentario no puede estar vacío</div>
  <?php elseif($estrella_msg == 'error_voto_invalido'): ?>
    <div class="alerta alerta-error">❌ La puntuación debe ser entre 1 y 5 estrellas</div>
  <?php elseif($estrella_msg == 'error_db'): ?>
    <div class="alerta alerta-error">❌ Error al guardar. Intentá de nuevo.</div>
  <?php elseif($estrella_msg == 'error_no_permiso'): ?>
    <div class="alerta alerta-error">❌ No tenés permiso para hacer eso</div>
  <?php elseif($estrella_msg == 'error_comentario_vacio'): ?>
    <div class="alerta alerta-error">❌ El comentario no puede estar vacío</div>
  <?php endif; ?>

  <!-- Imagen -->
  <?php if($receta['imagen_url'] && file_exists($receta['imagen_url'])): ?>
    <img class="detalle-img" src="<?php echo htmlspecialchars($receta['imagen_url']); ?>" alt="<?php echo htmlspecialchars($receta['titulo']); ?>">
  <?php else: ?>
    <div class="detalle-sin-img">🍰</div>
  <?php endif; ?>

  <div class="detalle-body">
    <h1><?php echo htmlspecialchars($receta['titulo']); ?></h1>

    <!-- Meta -->
    <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem;font-size:.85rem;color:rgba(245,237,214,.55);">
      <span>⏱ <?php echo intval($receta['tiempo_preparacion']); ?> min</span>
      <span>👤 Por <?php echo htmlspecialchars($receta['nombre_usuario']); ?></span>
      <?php
      if($receta['votos_count'] > 0) {
          $promedio = round($receta['votos_total'] / $receta['votos_count'], 1);
          $dif_map = ['Fácil' => 'simple', 'fácil' => 'simple', 'Media' => 'moderada', 'media' => 'moderada', 'Difícil' => 'complicada', 'difícil' => 'complicada'];
          $dif_clase = $dif_map[$receta['dificultad']] ?? 'simple';
          echo '<span>⭐ ' . $promedio . '/5 (' . $receta['votos_count'] . ' votos)</span>';
      }
      ?>
      <span class="dif <?php $dif_map = ['Fácil' => 'simple', 'fácil' => 'simple', 'Media' => 'moderada', 'media' => 'moderada', 'Difícil' => 'complicada', 'difícil' => 'complicada']; echo $dif_map[$receta['dificultad']] ?? 'simple'; ?>"><?php echo htmlspecialchars($receta['dificultad']); ?></span>
    </div>

    <!-- Ingredientes + Instrucciones -->
    <div class="detalle-cols">
      <div>
        <div class="detalle-col-title">Ingredientes</div>
        <div class="ingredientes-container" style="display: flex; flex-direction: column; gap: 0.8rem; line-height: 1.65; font-size: 0.95rem; font-weight: 300;">
          <?php
          $lineas = explode("\n", trim($receta['ingredientes']));
          foreach($lineas as $linea) {
              $linea = trim($linea);
              if($linea) {
                  echo '<div class="ingr-item" style="color: rgba(62,36,16,.85);">' . htmlspecialchars($linea) . '</div>';
              }
          }
          ?>
        </div>
      </div>
      <div>
        <div class="detalle-col-title">Instrucciones</div>
        <ol class="pasos-list">
          <?php
          $pasos = explode("\n", trim($receta['instrucciones']));
          $n = 1;
          foreach($pasos as $paso) {
              $paso = trim($paso);
              if($paso) {
                  echo '<li class="paso"><div class="paso-num">' . $n++ . '</div><div class="paso-txt">' . htmlspecialchars($paso) . '</div></li>';
              }
          }
          ?>
        </ol>
      </div>
    </div>

    <!-- Votar y Comentar (un solo formulario) -->
    <?php if(isset($_SESSION['usuario_id'])): ?>
      <div style="margin-bottom:1.5rem;">
        <p style="font-size:.82rem;color:rgba(245,237,214,.5);margin-bottom:.5rem;text-transform:uppercase;letter-spacing:1px;">Tu puntuación y comentario</p>
        <form method="POST" action="comentario_estrella.php" style="display:flex;flex-direction:column;gap:.8rem;max-width:500px;">
          <div style="display:flex;gap:.6rem;align-items:center;">
            <select name="puntuacion" class="vote-stars" style="width:auto;padding:.4rem .6rem;background:rgba(0,0,0,.35);border:1px solid var(--borde);color:var(--crema);border-radius:2px;" required>
              <option value="">Seleccioná...</option>
              <option value="5">★★★★★</option>
              <option value="4">★★★★☆</option>
              <option value="3">★★★☆☆</option>
              <option value="2">★★☆☆☆</option>
              <option value="1">★☆☆☆☆</option>
            </select>
          </div>
          <textarea name="contenido" rows="3" placeholder="Escribí acá tu comentario..." style="width:100%;padding:.6rem;background:rgba(0,0,0,.25);border:1px solid var(--borde);color:var(--crema);border-radius:2px;" required></textarea>
          <input type="hidden" name="receta_id" value="<?php echo $receta['id']; ?>">
          <button type="submit" class="btn" style="align-self:flex-start;">📤 Enviar comentario y calificación</button>
        </form>
      </div>
    <?php else: ?>
      <p style="margin-bottom:1.5rem;font-size:.9rem;color:rgba(245,237,214,.5);">
        <a href="login.php" style="color:var(--caramelo);">Iniciá sesión</a> para calificar y comentar esta receta.
      </p>
    <?php endif; ?>

    <!-- Editar (dueño o admin) -->
    <?php
    $es_dueño = ($_SESSION['usuario_id'] ?? 0) == $receta['usuario_id'];
    $es_admin = ($_SESSION['rol'] ?? '') == 'admin';
    if($es_dueño || $es_admin):
    ?>
      <button class="btn btn-outline" style="font-size:.78rem;padding:.4rem 1rem;" onclick="document.getElementById('form-editar').style.display='block'; this.style.display='none';">✏️ Editar receta</button>

      <div id="form-editar" class="edit-panel" style="display:none; margin-top:20px; padding:15px; border:1px solid #ddd; border-radius: 10px;">
        <h3>Editar receta</h3>
        <form method="POST" action="actualizar_receta.php" enctype="multipart/form-data">
          <input type="hidden" name="receta_id" value="<?php echo $receta['id']; ?>">
          <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($receta['titulo']); ?>" required>
          </div>
          <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="2"><?php echo htmlspecialchars($receta['descripcion']); ?></textarea>
          </div>
          <div class="form-group">
            <label>Ingredientes (redactalos en párrafos o lista)</label>
            <textarea name="ingredientes" rows="5" required><?php echo htmlspecialchars($receta['ingredientes']); ?></textarea>
          </div>
          <div class="form-group">
            <label>Instrucciones (un paso por línea)</label>
            <textarea name="instrucciones" rows="6" required><?php echo htmlspecialchars($receta['instrucciones']); ?></textarea>
          </div>
          <div class="form-group">
            <label>Cambiar imagen (opcional)</label>
            <?php if($receta['imagen_url'] && file_exists($receta['imagen_url'])): ?>
              <img src="<?php echo htmlspecialchars($receta['imagen_url']); ?>" style="width:100px;display:block;margin-bottom:.5rem;border-radius:2px;">
            <?php endif; ?>
            <input type="file" name="imagen" accept="image/*">
          </div>
          <button type="submit" class="btn">Guardar cambios</button>
        </form>
      </div>
    <?php endif; ?>

    <!-- Comentarios con edición/eliminación -->
    <div class="comentarios-section">
      <h2>Comentarios</h2>

      <?php while($com = $comentarios->fetch_assoc()): ?>
        <?php 
        $es_dueno_comentario = ($_SESSION['usuario_id'] ?? 0) == $com['usuario_id'];
        $es_admin_comentario = ($_SESSION['rol'] ?? '') == 'admin';     
        ?>
        <div class="comentario" id="comentario-<?php echo $com['id']; ?>">
          <?php if($es_dueno_comentario || $es_admin_comentario): ?>
            <!-- Modo edición (oculto por defecto) -->
            <div id="editar-comentario-<?php echo $com['id']; ?>" style="display: none; margin: 10px 0;">
              <form method="POST" action="eliminar_editar_comentario.php">
                <textarea name="nuevo_contenido" rows="3" style="width:100%;padding:.6rem;background:rgba(0,0,0,.25);border:1px solid var(--borde);color:var(--crema);border-radius:2px;"><?php echo htmlspecialchars($com['contenido']); ?></textarea>
                <input type="hidden" name="comentario_id" value="<?php echo $com['id']; ?>">
                <input type="hidden" name="editar" value="1">
                <input type="hidden" name="receta_id" value="<?php echo $id; ?>">
                <button type="submit" class="btn" style="font-size:.78rem;padding:.4rem 1rem;">💾 Guardar cambios</button>
                <button type="button" class="btn" style="font-size:.78rem;padding:.4rem 1rem;background:transparent;border:1px solid var(--borde);" onclick="cancelarEdicion(<?php echo $com['id']; ?>)">❌ Cancelar</button>
              </form>
            </div>
          <?php endif; ?>        
          
          <div id="ver-comentario-<?php echo $com['id']; ?>">
            <div class="c-autor"><?php echo htmlspecialchars($com['nombre_usuario']); ?></div>
            <p class="c-text"><?php echo nl2br(htmlspecialchars($com['contenido'])); ?></p>
            <small style="color:rgba(245,237,214,.45);font-size:.75rem;"><?php echo $com['fecha']; ?></small>
            
            <?php if($es_dueno_comentario || $es_admin_comentario): ?>
              <div style="margin-top: 5px; display:flex; gap:5px;">
                <button onclick="mostrarEdicion(<?php echo $com['id']; ?>)" class="btn" style="font-size:.7rem;padding:.2rem .6rem;">✏️ Editar</button>
                <a href="eliminar_editar_comentario.php?eliminar=<?php echo $com['id']; ?>&receta_id=<?php echo $id; ?>" 
                   onclick="return confirm('¿Eliminar este comentario?')" 
                   class="btn" style="font-size:.7rem;padding:.2rem .6rem;background:transparent;border:1px solid #ff6b6b;color:#ff6b6b;">🗑️ Eliminar</a>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

  </div><!-- /detalle-body -->
</div><!-- /detalle-container -->
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

<footer>
  <a href="index.php" class="logo" style="font-size:1.3rem;">Dulce<span>Tentación</span></a>
  <p>Recetario de repostería artesanal · Argentina</p>
  <p>© 2026 — Todos los derechos reservados</p>
</footer>
</body>
</html>
