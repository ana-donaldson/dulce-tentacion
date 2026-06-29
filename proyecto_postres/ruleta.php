<?php
session_start();
require 'funciones.php';
require 'conexion.php';

$receta = ruletaAleatoria();
if (isset($_SESSION['usuario_id']) && $receta) {
    $usuario_id = $_SESSION['usuario_id'];
    $receta_id = $receta['id'];
    $sql = "INSERT INTO ruleta_historial (usuario_id, receta_id, fecha_seleccion)
            VALUES ($usuario_id, $receta_id, NOW())";
    $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ruleta de Postres — DulceTentación</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="ruleta-container">

  <div class="page-hero" style="padding-left:0;padding-right:0;">
    <h1>🎲 Ruleta de Postres</h1>
    <p>¿No sabés qué postre hacer hoy? Dejá que la suerte decida</p>
  </div>

  <?php if($receta): ?>
    <div class="ruleta-resultado">
      <h2>🍰 La ruleta eligió</h2>
      <?php if($receta['imagen_url'] && file_exists($receta['imagen_url'])): ?>
        <img src="<?php echo htmlspecialchars($receta['imagen_url']); ?>" alt="<?php echo htmlspecialchars($receta['titulo']); ?>" style="width: 100%; max-width: 400px; height: 220px; object-fit: cover; border-radius: 4px; margin: 0 auto 1.5rem; display: block; border: 1px solid var(--borde); box-shadow: 0 4px 15px rgba(62,36,16,.1);">
      <?php else: ?>
        <span class="ruleta-emoji">🍰</span>
      <?php endif; ?>
      <h3><?php echo htmlspecialchars($receta['titulo']); ?></h3>
      <div style="display:flex;gap:.8rem;justify-content:center;flex-wrap:wrap;">
        <a href="receta_detalle.php?id=<?php echo $receta['id']; ?>" class="btn">
          Ver receta completa →
        </a>
        <a href="ruleta.php" class="btn btn-outline">🎲 Girar de nuevo</a>
      </div>
    </div>
  <?php else: ?>
    <div class="ruleta-resultado">
      <p style="color:rgba(62,36,16,.5);">No hay recetas disponibles en la base de datos.</p>
      <a href="ruleta.php" class="btn" style="margin-top:1rem;">Intentar de nuevo</a>
    </div>
  <?php endif; ?>

  <div style="text-align:center;margin-top:1rem;">
    <a href="recetas.php" class="volver">← Ver todas las recetas</a>
  </div>

</div>

<footer>
  <a href="index.php" class="logo" style="font-size:1.3rem;">Dulce<span>Tentación</span></a>
  <p>Recetario de repostería artesanal · Argentina</p>
  <p>© 2026 — Todos los derechos reservados</p>
</footer>
</body>
</html>