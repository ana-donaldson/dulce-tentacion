<?php
require_once 'conexion.php';
session_start();

$id = $_GET['id']; // ejemplo: receta.php id=1

//trae los datos de una receta
$sql = "SELECT r.*, u.nombre_usuario 
        FROM recetas r
        JOIN usuarios u ON r.usuario_id = u.id
        WHERE r.id = $id";
$receta = $conn->query($sql)->fetch_assoc();

// trae los comentarios de esa receta
$comentarios = $conn->query("SELECT c.*, u.nombre_usuario 
                             FROM comentarios c
                             JOIN usuarios u ON c.usuario_id = u.id
                             WHERE c.receta_id = $id
                             ORDER BY c.fecha DESC");

//trae el puntaje promedio (todavia no lo implementamos)
//$promedio = $conn->query("SELECT AVG(puntuacion) as prom 
//                        FROM valoraciones 
//                        WHERE receta_id = $id")->fetch_assoc()['prom'];
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $receta['titulo']; ?></title>
</head>
<body>
    <?php include 'header.php'; ?>
    <h1><?php echo $receta['titulo']; ?></h1>
    
    <h2>Ingredientes</h2>
    <p><?php echo nl2br($receta['ingredientes']); ?></p>
    
    <h2>Instrucciones</h2>
    <p><?php echo nl2br($receta['instrucciones']); ?></p>
    
    <h2>Comentarios</h2>
    <?php while($com = $comentarios->fetch_assoc()): ?>
        <p><strong><?php echo $com['nombre_usuario']; ?>:</strong> <?php echo $com['contenido']; ?></p>
    <?php endwhile; ?>
    
    <?php if(isset($_SESSION['usuario_id'])): ?>
        <form method="POST" action="guardar_comentario.php">
            <textarea name="contenido" required></textarea>
            <input type="hidden" name="receta_id" value="<?php echo $id; ?>">
            <button>Comentar</button>
        </form>
    <?php endif; ?>

    <?php
    $es_dueño = ($_SESSION['usuario_id'] ?? 0) == $receta['usuario_id'];
$es_admin = ($_SESSION['rol'] ?? '') == 'admin';

if($es_dueño || $es_admin): ?>
    <button onclick="document.getElementById('form-editar').style.display='block'">✏️ Editar receta</button>
    
    <div id="form-editar" style="display:none; margin-top:20px; padding:15px; border:1px solid #ddd;">
        <h3>Editar receta</h3>
        <form method="POST" action="actualizar_receta.php">
            <input type="hidden" name="receta_id" value="<?php echo $receta['id']; ?>">
            <label>Título:</label>
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($receta['titulo']); ?>" required>
           <label>Descripción:</label>  
        <textarea name="descripcion" rows="2"><?php echo htmlspecialchars($receta['descripcion']); ?></textarea>
            <label>Ingredientes:</label>
            <textarea name="ingredientes" rows="4" required><?php echo htmlspecialchars($receta['ingredientes']); ?></textarea>
            <label>Instrucciones:</label>
            <textarea name="instrucciones" rows="5" required><?php echo htmlspecialchars($receta['instrucciones']); ?></textarea>
            <button type="submit">Guardar cambios</button>
        </form>
    </div>
<?php endif; ?>
</body>
</html>