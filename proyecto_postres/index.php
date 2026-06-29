<?php
session_start();
require_once 'conexion.php';
$dif_map = ['Fácil' => 'simple', 'fácil' => 'simple', 'Media' => 'moderada', 'media' => 'moderada', 'Difícil' => 'complicada', 'difícil' => 'complicada'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DulceTentación — Recetario de Repostería Artesanal</title>
  <meta name="description" content="Aprendé a hacer los mejores postres caseros. Recetas paso a paso de verano e invierno.">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<!-- HERO -->
<section class="hero">
  <div class="hero-inner">
    <div class="hero-badge">✦ Recetario Artesanal Argentino ✦</div>
    <h1>Dulce<em>Tentación</em></h1>
    <p>Aprendé a hacer los mejores postres caseros. Seguí las recetas paso a paso y compartí tu experiencia con la comunidad.</p>
    <div class="hero-btns">
      <a href="recetas.php" class="btn">Ver Recetas</a>
      <a href="ruleta.php" class="btn btn-outline">🎲 Ruleta</a>
    </div>
  </div>
</section>

<div class="ornament">✦ ✦ ✦</div>

<!-- ESTACIONES -->
<section>
  <div class="season-sep verano">
    <span class="season-label verano">☀️ Postres de Verano</span>
  </div>
  <div class="section" style="padding-top:0;">
    <div class="recetas-grid">
      <?php
      $sql_verano = "SELECT id, titulo, descripcion, tiempo_preparacion, dificultad, imagen_url FROM recetas WHERE categoria_id = 2 ORDER BY fecha_creacion DESC LIMIT 5";
      $res_verano = $conn->query($sql_verano);
      if($res_verano && $res_verano->num_rows > 0) {
          while($receta = $res_verano->fetch_assoc()) {
              $dif_clase = $dif_map[$receta['dificultad']] ?? 'simple';
              echo '<a href="receta_detalle.php?id=' . $receta['id'] . '" class="receta">';
              if($receta['imagen_url'] && file_exists($receta['imagen_url'])) {
                  echo '<img src="' . htmlspecialchars($receta['imagen_url']) . '" alt="' . htmlspecialchars($receta['titulo']) . '">';
              } else {
                  echo '<div class="receta-sin-img">🍦</div>';
              }
              echo '<div class="receta-body">';
              echo '<div class="receta-meta">Verano · ⏱ ' . intval($receta['tiempo_preparacion']) . ' min</div>';
              echo '<h3>' . htmlspecialchars($receta['titulo']) . '</h3>';
              echo '<p>' . substr(htmlspecialchars($receta['descripcion']), 0, 80) . '...</p>';
              echo '<div class="receta-footer">';
              echo '<span class="dif ' . $dif_clase . '">' . htmlspecialchars($receta['dificultad']) . '</span>';
              echo '</div>'; // receta-footer
              echo '</div></a>';
          }
      } else {
          echo '<p style="color:rgba(62,36,16,.5);">No hay recetas de verano aún.</p>';
      }
      ?>
    </div>
    <div style="text-align:center; margin-top:1.8rem;">
      <a href="recetas.php?categoria=2" class="btn btn-outline">Ver todas las recetas de verano</a>
    </div>
  </div>

  <div class="season-sep invierno">
    <span class="season-label invierno">❄️ Postres de Invierno</span>
  </div>
  <div class="section" style="padding-top:0;">
    <div class="recetas-grid">
      <?php
      $sql_invierno = "SELECT id, titulo, descripcion, tiempo_preparacion, dificultad, imagen_url FROM recetas WHERE categoria_id = 1 ORDER BY fecha_creacion DESC LIMIT 5";
      $res_invierno = $conn->query($sql_invierno);
      if($res_invierno && $res_invierno->num_rows > 0) {
          while($receta = $res_invierno->fetch_assoc()) {
              $dif_clase = $dif_map[$receta['dificultad']] ?? 'simple';
              echo '<a href="receta_detalle.php?id=' . $receta['id'] . '" class="receta">';
              if($receta['imagen_url'] && file_exists($receta['imagen_url'])) {
                  echo '<img src="' . htmlspecialchars($receta['imagen_url']) . '" alt="' . htmlspecialchars($receta['titulo']) . '">';
              } else {
                  echo '<div class="receta-sin-img">🍫</div>';
              }
              echo '<div class="receta-body">';
              echo '<div class="receta-meta">Invierno · ⏱ ' . intval($receta['tiempo_preparacion']) . ' min</div>';
              echo '<h3>' . htmlspecialchars($receta['titulo']) . '</h3>';
              echo '<p>' . substr(htmlspecialchars($receta['descripcion']), 0, 80) . '...</p>';
              echo '<div class="receta-footer">';
              echo '<span class="dif ' . $dif_clase . '">' . htmlspecialchars($receta['dificultad']) . '</span>';
              echo '</div>'; // receta-footer
              echo '</div></a>';
          }
      } else {
          echo '<p style="color:rgba(62,36,16,.5);">No hay recetas de invierno aún.</p>';
      }
      ?>
    </div>
    <div style="text-align:center; margin-top:1.8rem;">
      <a href="recetas.php?categoria=1" class="btn btn-outline">Ver todas las recetas de invierno</a>
    </div>
  </div>

<div class="ornament">✦ ✦ ✦</div>

<!-- ÚLTIMAS RECETAS DE LA BD -->
<section class="section">
  <div class="sec-head">
    <h2>Últimas recetas subidas</h2>
    <p>Las más recientes de nuestra comunidad</p>
  </div>
  <div class="recetas-grid">
    <?php
    $sql = "SELECT id, titulo, descripcion, tiempo_preparacion, dificultad, imagen_url, votos_total, votos_count FROM recetas ORDER BY fecha_creacion DESC LIMIT 5";
    $resultado = $conn->query($sql);

    if($resultado && $resultado->num_rows > 0) {
        while($receta = $resultado->fetch_assoc()) {
            $dif_clase = $dif_map[$receta['dificultad']] ?? 'simple';

            echo '<a href="receta_detalle.php?id=' . $receta['id'] . '" class="receta">';

            if($receta['imagen_url'] && file_exists($receta['imagen_url'])) {
                echo '<img src="' . htmlspecialchars($receta['imagen_url']) . '" alt="' . htmlspecialchars($receta['titulo']) . '">';
            } else {
                echo '<div class="receta-sin-img">🍰</div>';
            }

            echo '<div class="receta-body">';
            echo '<div class="receta-meta">⏱ ' . intval($receta['tiempo_preparacion']) . ' min</div>';
            echo '<h3>' . htmlspecialchars($receta['titulo']) . '</h3>';
            echo '<p>' . substr(htmlspecialchars($receta['descripcion']), 0, 80) . '...</p>';
            echo '<div class="receta-footer">';

            if($receta['votos_count'] > 0) {
                $prom = round($receta['votos_total'] / $receta['votos_count'], 1);
                echo '<span class="estrellas">' . str_repeat('★', floor($prom)) . str_repeat('☆', 5 - floor($prom)) . ' ' . $prom . '</span>';
            }

            echo '<span class="dif ' . $dif_clase . '">' . htmlspecialchars($receta['dificultad']) . '</span>';
            echo '</div>'; // receta-footer
            echo '</div></a>';
        }
    } else {
        echo '<p style="color:rgba(62,36,16,.5);">No hay recetas aún. ¡Sé el primero en subir una!</p>';
    }
    ?>
  </div>
  <div style="text-align:center; margin-top:1.8rem;">
    <a href="recetas.php" class="btn btn-outline">Ver más recetas →</a>
  </div>
</section>

<div class="ornament">✦ ✦ ✦</div>

<!-- CATEGORÍAS -->
<section class="section">
  <div class="sec-head">
    <h2>Postres para cada estación</h2>
    <p>Encontrá la receta perfecta según el clima</p>
  </div>
  <div style="display:flex; gap:1.2rem; justify-content:center; flex-wrap:wrap;">
    <a href="recetas.php?categoria=1" class="receta" style="max-width:260px; text-decoration:none; text-align:center;">
      <div class="receta-sin-img">❄️</div>
      <div class="receta-body">
        <h3>Postres de Invierno</h3>
        <p>Brownies, tartas calientes, alfajores...</p>
        <span class="btn btn-outline" style="font-size:.78rem;padding:.4rem 1rem;">Explorar →</span>
      </div>
    </a>
    <a href="recetas.php?categoria=2" class="receta" style="max-width:260px; text-decoration:none; text-align:center;">
      <div class="receta-sin-img">☀️</div>
      <div class="receta-body">
        <h3>Postres de Verano</h3>
        <p>Helados, mousses, cheesecakes fríos...</p>
        <span class="btn btn-outline" style="font-size:.78rem;padding:.4rem 1rem;">Explorar →</span>
      </div>
    </a>
    <a href="ruleta.php" class="receta" style="max-width:260px; text-decoration:none; text-align:center;">
      <div class="receta-sin-img">🎲</div>
      <div class="receta-body">
        <h3>Ruleta de Postres</h3>
        <p>¿No sabés qué hacer? Dejá que la suerte decida.</p>
        <span class="btn" style="font-size:.78rem;padding:.4rem 1rem;">Girar ruleta →</span>
      </div>
    </a>
  </div>
</section>

<footer>
  <a href="index.php" class="logo" style="font-size:1.3rem;">Dulce<span>Tentación</span></a>
  <p>Recetario de repostería artesanal · Argentina</p>
  <p>© 2026 — Todos los derechos reservados</p>
</footer>

</body>
</html>
