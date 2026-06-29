<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);
    $check = $conn->query("SELECT usuario_id FROM recetas WHERE id = $id_eliminar");
    $receta_check = $check->fetch_assoc();
    if ($receta_check && $receta_check['usuario_id'] == $_SESSION['usuario_id']) {
        $conn->query("DELETE FROM recetas WHERE id = $id_eliminar");
    }
    header('Location: perfil.php');
    exit;
}

$usuario_id  = $_SESSION['usuario_id'];
$nombre_usuario = $_SESSION['nombre_usuario'];
// obtener datos del usuario

$sql_usuario = "SELECT * FROM usuarios WHERE id = $usuario_id";
$usuario = $conn->query($sql_usuario)->fetch_assoc();

// obtener recetas del usuario
$sql_recetas = "SELECT * FROM recetas WHERE usuario_id = $usuario_id ORDER BY fecha_creacion DESC";
$mis_recetas = $conn->query($sql_recetas);

// obtener comentarios del usuario
$sql_comentarios = "SELECT c.*, r.titulo as receta_titulo
                    FROM comentarios c
                    JOIN recetas r ON c.receta_id = r.id
                    WHERE c.usuario_id = $usuario_id
                    ORDER BY c.fecha DESC LIMIT 10";
$mis_comentarios = $conn->query($sql_comentarios);
//obtener historial de ruleta
$sql_ruleta = "SELECT rh.*, r.titulo
               FROM ruleta_historial rh
               JOIN recetas r ON rh.receta_id = r.id
               WHERE rh.usuario_id = $usuario_id
               ORDER BY rh.fecha_seleccion DESC LIMIT 10";
$historial_ruleta = $conn->query($sql_ruleta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Perfil — DulceTentación</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="perfil-container">

  <div class="page-hero" style="padding-left:0;padding-right:0;margin-bottom:2rem;">
    <h1>👤 Mi Perfil</h1>
    <p>Bienvenido/a, <?php echo htmlspecialchars($nombre_usuario); ?></p>
  </div>

  <!-- Información personal -->
  <div class="perfil-card">
    <h2>Información personal</h2>
    <div class="perfil-info-row">
      <strong>Usuario:</strong> <?php echo htmlspecialchars($usuario['nombre_usuario']); ?>
    </div>
    <div class="perfil-info-row">
      <strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?>
    </div>
    <div class="perfil-info-row">
      <strong>Rol:</strong>
      <?php if($usuario['rol'] == 'admin'): ?>
        <span class="admin-badge">👑 Administrador</span>
      <?php else: ?>
        <span style="color:rgba(245,237,214,.6);">Usuario</span>
      <?php endif; ?>
    </div>
    <div class="perfil-info-row">
      <strong>Fecha de registro:</strong>
      <span style="color:rgba(245,237,214,.6);"><?php echo $usuario['fecha_registro']; ?></span>
    </div>
    <div style="margin-top:1.2rem;">
      <a href="editar_perfil.php" class="btn btn-outline" style="font-size:.82rem;padding:.45rem 1rem;">✏️ Editar perfil</a>
    </div>
  </div>

  <!-- Mis recetas -->
  <div class="perfil-card">
    <h2>📝 Mis recetas (<?php echo $mis_recetas->num_rows; ?>)</h2>

    <?php if($mis_recetas->num_rows > 0): ?>
      <?php while($receta = $mis_recetas->fetch_assoc()): ?>
        <div class="receta-item">
          <div>
            <div class="receta-item-titulo"><?php echo htmlspecialchars($receta['titulo']); ?></div>
            <div class="receta-item-desc"><?php echo substr(htmlspecialchars($receta['descripcion']), 0, 80); ?>...</div>
          </div>
          <div class="btn-group">
            <a href="receta_detalle.php?id=<?php echo $receta['id']; ?>" class="btn btn-outline" style="font-size:.78rem;padding:.38rem .9rem;">Ver</a>
            <a href="receta_detalle.php?id=<?php echo $receta['id']; ?>" class="btn" style="font-size:.78rem;padding:.38rem .9rem;">✏️ Editar</a>
            <a href="perfil.php?eliminar=<?php echo $receta['id']; ?>" class="btn btn-danger" style="font-size:.78rem;padding:.38rem .9rem;" onclick="return confirm('¿Eliminar esta receta?')">🗑️</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="color:rgba(245,237,214,.5);">No subiste ninguna receta aún.</p>
      <a href="subir_receta.php" class="btn" style="margin-top:.8rem;font-size:.82rem;padding:.45rem 1rem;">➕ Subir mi primera receta</a>
    <?php endif; ?>
  </div>

  <div class="perfil-card">
    <h2>💬 Mis comentarios</h2>
    <?php if($mis_comentarios->num_rows > 0): ?>
      <?php while($comentario = $mis_comentarios->fetch_assoc()): ?>
        <div class="receta-item" style="flex-direction:column;align-items:flex-start;">
          <div style="font-size:.78rem;color:var(--caramelo);text-transform:uppercase;letter-spacing:1px;margin-bottom:.3rem;">
            En: <?php echo htmlspecialchars($comentario['receta_titulo']); ?>
          </div>
          <div style="color:rgba(245,237,214,.8);font-size:.92rem;"><?php echo htmlspecialchars($comentario['contenido']); ?></div>
          <div style="font-size:.78rem;color:rgba(245,237,214,.35);margin-top:.3rem;"><?php echo $comentario['fecha']; ?></div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="color:rgba(245,237,214,.5);">No comentaste ninguna receta aún.</p>
    <?php endif; ?>
  </div>

  <div class="perfil-card">
    <h2>🎲 Últimas ruletas</h2>
    <?php if($historial_ruleta->num_rows > 0): ?>
      <?php while($item = $historial_ruleta->fetch_assoc()): ?>
        <div class="receta-item">
          <a href="receta_detalle.php?id=<?php echo $item['receta_id']; ?>" style="color:var(--crema);text-decoration:none;font-size:.92rem;">
            🍰 <?php echo htmlspecialchars($item['titulo']); ?>
          </a>
          <span style="font-size:.78rem;color:rgba(245,237,214,.35);"><?php echo $item['fecha_seleccion']; ?></span>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="color:rgba(245,237,214,.5);">Todavía no usaste la ruleta. <a href="ruleta.php" style="color:var(--caramelo);">¡Probala ahora!</a></p>
    <?php endif; ?>
  </div>

  <?php if($_SESSION['rol'] == 'admin'): ?>
    <div class="perfil-card" style="border-color:rgba(235,206,121,.35);">
      <h2>🔧 Panel de Administración</h2>
      <p style="color:rgba(245,237,214,.6);margin-bottom:1rem;">Tenés permisos especiales como administrador.</p>
      <a href="admin.php" class="btn">Ir al panel de admin →</a>
    </div>
  <?php endif; ?>

  <p style="margin-top:1rem;"><a href="index.php" class="volver">← Volver al inicio</a></p>

</div>

<footer>
  <a href="index.php" class="logo" style="font-size:1.3rem;">Dulce<span>Tentación</span></a>
  <p>Recetario de repostería artesanal · Argentina</p>
  <p>© 2026 — Todos los derechos reservados</p>
</footer>

</body>
</html>
