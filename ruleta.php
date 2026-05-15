<?php
session_start();
require_once 'funciones.php';
require_once 'conexion.php';  

$receta = ruletaAleatoria();  
if (isset($_SESSION['usuario_id']) && $receta) {
    $usuario_id = $_SESSION['usuario_id'];
    $receta_id = $receta['id'];
    
    //se inserta en la tabla ruleta_historial
    $sql = "INSERT INTO ruleta_historial (usuario_id, receta_id, fecha_seleccion) 
            VALUES ($usuario_id, $receta_id, NOW())";
    $conn->query($sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ruleta de Postres</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <section class="ruleta-container">
        <h1>🎡 ¿Qué postre comemos hoy?</h1>
        
        <div class="ruleta-visual">
            <div class="ruleta-girando">(animacion de ruleta)</div>
        </div>
        <div class="resultado">
            <h2>🍰 La ruleta eligió:</h2>
            <div class="receta-elegida">
                <h3><?php echo htmlspecialchars($receta['titulo']); ?></h3>
                <a href="receta_detalle.php?id=<?php echo $receta['id']; ?>" class="boton">
                    Ver receta completa →
                </a>
            </div>
        </div>
        <a href="ruleta.php" class="boton-girar">🎲 Girar de nuevo</a>
    </section>
</body>
</html>