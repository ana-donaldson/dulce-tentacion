<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header('Location: index.php');
    exit;
}

$total_usuarios   = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'];
$total_recetas    = $conn->query("SELECT COUNT(*) as total FROM recetas")->fetch_assoc()['total'];
$total_comentarios = $conn->query("SELECT COUNT(*) as total FROM comentarios")->fetch_assoc()['total'];

$mensaje = '';
if (isset($_GET['eliminar_receta'])) {
    $id = intval($_GET['eliminar_receta']);
    if ($conn->query("DELETE FROM recetas WHERE id = $id")) {
        $mensaje = "✅ Receta eliminada";
    }
}
if (isset($_GET['eliminar_usuario'])) {
    $id = intval($_GET['eliminar_usuario']);
    if ($conn->query("DELETE FROM usuarios WHERE id = $id AND rol != 'admin'")) {
        $mensaje = "✅ Usuario eliminado";
    }
}
if (isset($_GET['eliminar_comentario'])) {
    $id = intval($_GET['eliminar_comentario']);
    if ($conn->query("DELETE FROM comentarios WHERE id = $id")) {
        $mensaje = "✅ Comentario eliminado";
    }
}

$usuarios    = $conn->query("SELECT * FROM usuarios ORDER BY fecha_registro DESC");
$recetas     = $conn->query("SELECT r.*, u.nombre_usuario FROM recetas r JOIN usuarios u ON r.usuario_id = u.id ORDER BY r.fecha_creacion DESC");
$comentarios = $conn->query("SELECT c.*, u.nombre_usuario, r.titulo as receta_titulo
                             FROM comentarios c
                             JOIN usuarios u ON c.usuario_id = u.id
                             JOIN recetas r ON c.receta_id = r.id
                             ORDER BY c.fecha DESC LIMIT 20");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Admin — DulceTentación</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="admin-container">

  <div class="page-hero" style="padding-left:0;padding-right:0;margin-bottom:2rem;">
    <h1>🔧 Panel de Administración</h1>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></p>
  </div>

  <?php if($mensaje): ?>
    <div class="alerta alerta-ok"><?php echo $mensaje; ?></div>
  <?php endif; ?>

  <!-- ESTADÍSTICAS -->
  <div class="admin-stats">
    <div class="stat-card">
      <h3><?php echo $total_usuarios; ?></h3>
      <p>👥 Usuarios</p>
    </div>
    <div class="stat-card">
      <h3><?php echo $total_recetas; ?></h3>
      <p>📖 Recetas</p>
    </div>
    <div class="stat-card">
      <h3><?php echo $total_comentarios; ?></h3>
      <p>💬 Comentarios</p>
    </div>
  </div>

  <!-- USUARIOS -->
  <div class="admin-section">
    <h2>👥 Usuarios registrados</h2>
    <div style="overflow-x:auto;">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Fecha</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while($u = $usuarios->fetch_assoc()): ?>
          <tr>
            <td style="color:rgba(245,237,214,.4);"><?php echo $u['id']; ?></td>
            <td><?php echo htmlspecialchars($u['nombre_usuario']); ?></td>
            <td style="color:rgba(245,237,214,.6);"><?php echo htmlspecialchars($u['email']); ?></td>
            <td>
              <?php if($u['rol'] == 'admin'): ?>
                <span class="admin-badge">👑 Admin</span>
              <?php else: ?>
                <span style="color:rgba(245,237,214,.5);font-size:.82rem;">Usuario</span>
              <?php endif; ?>
            </td>
            <td style="color:rgba(245,237,214,.4);font-size:.82rem;"><?php echo $u['fecha_registro']; ?></td>
            <td>
              <?php if($u['rol'] != 'admin'): ?>
                <a href="?eliminar_usuario=<?php echo $u['id']; ?>"
                   class="link-danger"
                   onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
              <?php else: ?>
                <span style="color:rgba(245,237,214,.2);font-size:.82rem;">—</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- RECETAS -->
  <div class="admin-section">
    <h2>📖 Todas las recetas</h2>
    <div style="overflow-x:auto;">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Dificultad</th>
            <th>Fecha</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while($r = $recetas->fetch_assoc()): ?>
          <tr>
            <td style="color:rgba(245,237,214,.4);"><?php echo $r['id']; ?></td>
            <td><?php echo htmlspecialchars($r['titulo']); ?></td>
            <td style="color:rgba(245,237,214,.6);"><?php echo htmlspecialchars($r['nombre_usuario']); ?></td>
            <td>
              <?php
              $dif_map = ['Fácil' => 'simple', 'fácil' => 'simple', 'Media' => 'moderada', 'media' => 'moderada', 'Difícil' => 'complicada', 'difícil' => 'complicada'];
              $dc = $dif_map[$r['dificultad']] ?? 'simple';
              echo '<span class="dif ' . $dc . '">' . htmlspecialchars($r['dificultad']) . '</span>';
              ?>
            </td>
            <td style="color:rgba(245,237,214,.4);font-size:.82rem;"><?php echo $r['fecha_creacion']; ?></td>
            <td style="display:flex;gap:.6rem;flex-wrap:wrap;">
              <a href="receta_detalle.php?id=<?php echo $r['id']; ?>" class="link-ok">Ver</a>
              <a href="?eliminar_receta=<?php echo $r['id']; ?>"
                 class="link-danger"
                 onclick="return confirm('¿Eliminar esta receta?')">Eliminar</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- COMENTARIOS -->
  <div class="admin-section">
    <h2>💬 Últimos comentarios</h2>
    <?php if($comentarios->num_rows > 0): ?>
      <div style="overflow-x:auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Usuario</th>
              <th>Receta</th>
              <th>Comentario</th>
              <th>Fecha</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php while($c = $comentarios->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($c['nombre_usuario']); ?></td>
              <td style="color:rgba(245,237,214,.6);"><?php echo htmlspecialchars($c['receta_titulo']); ?></td>
              <td style="color:rgba(245,237,214,.7);"><?php echo substr(htmlspecialchars($c['contenido']), 0, 60); ?>...</td>
              <td style="color:rgba(245,237,214,.4);font-size:.82rem;"><?php echo $c['fecha']; ?></td>
              <td>
                <a href="?eliminar_comentario=<?php echo $c['id']; ?>"
                   class="link-danger"
                   onclick="return confirm('¿Eliminar este comentario?')">Eliminar</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p style="color:rgba(245,237,214,.4);">No hay comentarios aún.</p>
    <?php endif; ?>
  </div>

  <p><a href="index.php" class="volver">← Volver al inicio</a></p>

</div>

<footer>
  <a href="index.php" class="logo" style="font-size:1.3rem;">Dulce<span>Tentación</span></a>
  <p>Recetario de repostería artesanal · Argentina</p>
  <p>© 2026 — Todos los derechos reservados</p>
</footer>

</body>
</html>
