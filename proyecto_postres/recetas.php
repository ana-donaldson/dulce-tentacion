<?php
session_start();
require 'conexion.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Todas las recetas </title>
    <style>
        body { font-family: Arial; margin: 0; background: #fef8f0; }
        header { background: #8b5a2b; color: white; padding: 1rem; }
        nav ul { list-style: none; display: flex; gap: 1rem; padding: 0; }
        nav a { color: white; text-decoration: none; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        h1 { color: #8b5a2b; }
        .filtros { margin: 20px 0; display: flex; gap: 10px; flex-wrap: wrap; }
        .filtros a { background: #ddd; padding: 8px 15px; text-decoration: none; border-radius: 20px; color: #333; }
        .filtros a.activo { background: #8b5a2b; color: white; }
        .grid-recetas { display: flex; gap: 20px; flex-wrap: wrap; justify-content: flex-start; }
        .tarjeta-receta { width: 280px; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .tarjeta-receta img { width: 100%; height: 160px; object-fit: cover; }
        .sin-imagen { height: 160px; background: #ddd; text-align: center; line-height: 160px; font-size: 3rem; }
        .info { padding: 15px; }
        .info h3 { margin: 0 0 10px; }
        .meta { font-size: 0.9rem; color: #666; margin: 10px 0; }
        .estrellas { font-size: 0.9rem; margin: 5px 0; color: #f5a623; }
        .ver-receta { background: #8b5a2b; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; display: inline-block; }
        footer { background: #2c1a0e; color: white; text-align: center; padding: 1rem; margin-top: 30px; }
    </style>
</head>
<body>
<header>
    <h1>🍰 Dulce tentacion</h1>
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="recetas.php">Recetas</a></li>
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <li><a href="perfil.php">👤 <?php echo $_SESSION['nombre_usuario']; ?></a></li>
                <li><a href="subir_receta.php">Subir receta</a></li>
                <li><a href="logout.php">Salir</a></li>
            <?php else: ?>
                <li><a href="login.php">Iniciar sesión</a></li>
                <li><a href="registro.php">Registrarse</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<div class="container">
    <h1>📖 Todas las recetas</h1>
    <div class="filtros">
        <a href="recetas.php">Todas</a>
        <a href="recetas.php?categoria=1">❄️ Invierno</a>
        <a href="recetas.php?categoria=2">☀️ Verano</a>
    </div>
    <div class="grid-recetas">
        <?php
        // consulta con o sin filtro
        if (isset($_GET['categoria'])) {
            $categoria = intval($_GET['categoria']);
        } else {
            $categoria = 0;
        }
        
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

        if($resultado->num_rows > 0) {
            while($receta = $resultado->fetch_assoc()) {
                echo '<div class="tarjeta-receta">';
                
                if(isset($receta['imagen_url']) && !empty($receta['imagen_url']) && file_exists($receta['imagen_url'])) {
                    echo '<img src="' . $receta['imagen_url'] . '" alt="' . htmlspecialchars($receta['titulo']) . '">';
                } else {
                    echo '<div class="sin-imagen">🍰 </div>';
                }        
                echo '<div class="info">';
                echo '<h3>' . htmlspecialchars($receta['titulo']) . '</h3>';
                echo '<p>' . substr(htmlspecialchars($receta['descripcion']), 0, 80) . '...</p>';
                echo '<div class="meta">⏰ ' . $receta['tiempo_preparacion'] . ' min | ' . $receta['dificultad'] . '</div>';       
                //mostrar estrellas
                if($receta['votos_count'] > 0) {
                    $promedio = round($receta['votos_total'] / $receta['votos_count'], 1);
                    $estrellas = str_repeat('⭐', floor($promedio));
                    echo '<div class="estrellas">' . $estrellas . ' ' . $promedio . '</div>';
                } else {
                    echo '<div class="estrellas">Sin votos</div>';
                }
                echo '<a href="receta_detalle.php?id=' . $receta['id'] . '" class="ver-receta">Ver receta →</a>';
                echo '</div></div>';
            }
        } else {
            echo '<p>No hay recetas disponibles.</p>';
        }
        ?>
    </div>
</div>
</body>
</html>
