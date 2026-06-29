<?php
session_start();
require_once 'conexion.php';

$categoria = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;

if($categoria > 0) {
    $sql = "SELECT id, titulo, descripcion, tiempo_preparacion, dificultad, imagen_url, votos_total, votos_count
            FROM recetas
            WHERE categoria_id = $categoria
            ORDER BY fecha_creacion DESC";
} else {
    $sql = "SELECT id, titulo, descripcion, tiempo_preparacion, dificultad, imagen_url, votos_total, votos_count
            FROM recetas
            ORDER BY fecha_creacion DESC";
}
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todas las Recetas — DulceTentación</title>
  <meta name="description" content="Explorá todas las recetas de repostería artesanal de DulceTentación.">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="page-hero">
  <h1>📖 Todas las recetas</h1>
  <p>Explorá nuestra colección de postres artesanales</p>
</div>

<section class="section">

  <!-- FILTROS -->
  <div class="filtros">
    <a href="recetas.php" <?php if($categoria == 0) echo 'class="activo"'; ?>>Todas</a>
    <a href="recetas.php?categoria=1" <?php if($categoria == 1) echo 'class="activo"'; ?>>❄️ Invierno</a>
    <a href="recetas.php?categoria=2" <?php if($categoria == 2) echo 'class="activo"'; ?>>☀️ Verano</a>
  </div>

  <!-- GRID -->
  <div class="recetas-grid">
    <?php
    $dif_map = ['Fácil' => 'simple', 'fácil' => 'simple', 'Media' => 'moderada', 'media' => 'moderada', 'Difícil' => 'complicada', 'difícil' => 'complicada'];

    if($resultado->num_rows > 0) {
        while($receta = $resultado->fetch_assoc()) {
            $dif_clase = $dif_map[$receta['dificultad']] ?? 'simple';
            echo '<a href="receta_detalle.php?id=' . $receta['id'] . '" class="receta">';

            // Mostrar estrellas
if($receta['votos_count'] > 0) {
    $promedio = round($receta['votos_total'] / $receta['votos_count'], 1);
    $estrellas = str_repeat('⭐', floor($promedio));
    echo '<div style="text-align: center; margin: 4px 0;">' . $estrellas . ' ' . $promedio . '</div>';
} else {
    echo '<div style="text-align: center; margin: 4px 0; color: #999; font-size: 0.9rem;">Sin votos</div>';
}

            if(isset($receta['imagen_url']) && !empty($receta['imagen_url']) && file_exists($receta['imagen_url'])) {
                echo '<img src="' . htmlspecialchars($receta['imagen_url']) . '" alt="' . htmlspecialchars($receta['titulo']) . '">';
            } else {
                echo '<div class="receta-sin-img">🍰</div>';
            }

            echo '<div class="receta-body">';
            echo '<div class="receta-meta">⏱ ' . intval($receta['tiempo_preparacion']) . ' min</div>';
            echo '<h3>' . htmlspecialchars($receta['titulo']) . '</h3>';
            echo '<p>' . substr(htmlspecialchars($receta['descripcion']), 0, 90) . '...</p>';
            echo '<div class="receta-footer">';
            echo '<span class="dif ' . $dif_clase . '">' . htmlspecialchars($receta['dificultad']) . '</span>';
            echo '</div>';
            echo '</div></a>';
        }
    } else {
        echo '<p style="color:rgba(62,36,16,.5);">No hay recetas disponibles.</p>';
    }
    ?>
  </div>

</section>

<footer>
  <a href="index.php" class="logo" style="font-size:1.3rem;">Dulce<span>Tentación</span></a>
  <p>Recetario de repostería artesanal · Argentina</p>
  <p>© 2026 — Todos los derechos reservados</p>
</footer>

</body>
</html>
