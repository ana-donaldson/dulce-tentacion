<?php
session_start();
require 'conexion.php';
require 'funciones.php';

$receta_ruleta = null;
$mensaje_ruleta = '';

// Si se giró la ruleta desde el index
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['girar_ruleta'])) {
    $receta_ruleta = ruletaAleatoria();
    
    //Guardar en historial si está logueado
  if (isset($_SESSION['usuario_id']) && $receta_ruleta) {
    guardarHistorialRuleta($_SESSION['usuario_id'], $receta_ruleta['id']);
    $mensaje_ruleta = "🎉 ¡La ruleta eligió un postre para vos!";
 }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>🍰 dulce tentacion</title>
    <style>
        body { font-family: Arial; margin: 0; padding: 0; background: #fef8f0; }
        header { background: #8b5a2b; color: white; padding: 1rem; }
        nav ul { list-style: none; display: flex; gap: 1rem; padding: 0; }
        nav a { color: white; text-decoration: none; }
        .hero { background: #f5c6a0; text-align: center; padding: 3rem; }
        .boton-principal { background: #8b5a2b; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 5px; }
        .categorias-destacadas { padding: 2rem; text-align: center; }
        .cards-categorias { display: flex; justify-content: center; gap: 2rem; }
        .card-categoria { background: white; padding: 1rem; border-radius: 10px; width: 200px; }
        .ultimas-recetas { padding: 2rem; }
        .grid-recetas { display: flex; gap: 1rem; flex-wrap: wrap; }
        .tarjeta-receta { border: 1px solid #ddd; padding: 1rem; width: 250px; background: white; border-radius: 10px; }
        footer { background: #2c1a0e; color: white; text-align: center; padding: 1rem; }
    </style>
</head>
<body>
<header>
    <h1>🍰 dulce tentacion</h1>
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="recetas.php">Recetas</a></li>
            <?php if(isset($_SESSION['usuario_id'])): ?> 
                <li><a href="perfil.php">👤 <?php echo $_SESSION['nombre_usuario']; ?></a></li>
                <li><a href="subir_receta.php">➕ Subir receta</a></li>
                <li><a href="logout.php">🚪 Salir</a></li>
            <?php else: ?> 
                <li><a href="login.php">🔐 Iniciar sesión</a></li>
                <li><a href="registro.php">📝 Registrarse</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'bienvenido' && isset($_SESSION['usuario_id'])): ?>
    <div style="background: #d4edda; color: #155724; padding: 10px; text-align: center; margin: 10px auto; max-width: 90%; border-radius: 5px;">
        ✅ ¡Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!
    </div>
<?php endif; ?>

<section class="ruleta-destacada">
    <h2>🎡 ¿No sabés qué postre elegir?</h2>
    
    <form method="POST">
        <button type="submit" name="girar_ruleta" class="btn-girar">🎲 GIRAR RULETA 🎲</button>
    </form>
    <?php if($mensaje_ruleta): ?>
        <div class="mensaje"><?php echo $mensaje_ruleta; ?></div>
    <?php endif; ?>
    <?php if($receta_ruleta): ?>
        <div class="receta">
            <h3><?php echo htmlspecialchars($receta_ruleta['titulo']); ?></h3>
            <a href="receta_detalle.php?id=<?php echo $receta_ruleta['id']; ?>" class="boton-principal">Ver receta →</a>
        </div>
    <?php else: ?>
        <div class="receta" style="background: #f0f0f0;">
            <p>Presioná el botón para que la ruleta elija un postre</p>
        </div>
    <?php endif; ?>
</section>

<section class="categorias-destacadas">
    <h2>Postres para cada estación</h2>
    <div class="cards-categorias">
        <div class="card-categoria">
            <h3>❄️ Invierno</h3>
            <p>Brownies, tartas calientes, churros...</p>
            <a href="recetas.php?categoria=1">Ver más →</a>
        </div>
        <div class="card-categoria">
            <h3>☀️ Verano</h3>
            <p>Helados, tartas, gelatinas...</p>
            <a href="recetas.php?categoria=2">Ver más →</a>
        </div>
    </div>
</section>

<section class="ultimas-recetas">
    <h2> Novedad (ultimas recetas subidas)</h2>
    <div class="grid-recetas">
        <?php
        require_once 'conexion.php';   
        $sql = "SELECT id, titulo, descripcion, tiempo_preparacion, dificultad, imagen_url, votos_total, votos_count FROM recetas ORDER BY fecha_creacion DESC LIMIT 6";
        $resultado = $conn->query($sql);
        
        if($resultado->num_rows > 0) {
            while($receta = $resultado->fetch_assoc()) {
                echo '<div class="tarjeta-receta">';
                
                // Mostrar imagen
                if($receta['imagen_url'] && file_exists($receta['imagen_url'])) {
                    echo '<img src="' . $receta['imagen_url'] . '" style="width:100%; height:150px; object-fit:cover;">';
                } else {
                    echo '<div style="height:150px; background:#ddd; text-align:center; line-height:150px;">🍰</div>';
                }                
                echo '<h3>' . htmlspecialchars($receta['titulo']) . '</h3>';
                echo '<p>' . substr(htmlspecialchars($receta['descripcion']), 0, 80) . '...</p>';
                echo '<p>⏰ ' . $receta['tiempo_preparacion'] . ' min | ' . $receta['dificultad'] . '</p>';
                
                // Mostrar estrellas
                if($receta['votos_count'] > 0) {
                    $promedio = round($receta['votos_total'] / $receta['votos_count'], 1);
                    $estrellas = str_repeat('⭐', floor($promedio));
                    echo '<div class="estrellas">' . $estrellas . ' ' . $promedio . '</div>';
                } else {
                    echo '<div class="estrellas">Sin votos</div>';
                }  
                echo '<a href="receta_detalle.php?id=' . $receta['id'] . '">Ver receta →</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No hay recetas aún. ¡Sé el primero en subir una!</p>';
        }
        ?>
    </div>
</section>
</body>
</html>
