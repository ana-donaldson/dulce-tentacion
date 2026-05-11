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
    $receta = $check->fetch_assoc();
    
    if ($receta && $receta['usuario_id'] == $_SESSION['usuario_id']) {
        $conn->query("DELETE FROM recetas WHERE id = $id_eliminar");
    }
    
    header('Location: perfil.php');
    exit;
     }
$usuario_id = $_SESSION['usuario_id'];
$nombre_usuario = $_SESSION['nombre_usuario'];

// Obtener datos del usuario
$sql_usuario = "SELECT * FROM usuarios WHERE id = $usuario_id";
$usuario = $conn->query($sql_usuario)->fetch_assoc();

// Obtener recetas del usuario
$sql_recetas = "SELECT * FROM recetas WHERE usuario_id = $usuario_id ORDER BY fecha_creacion DESC";
$mis_recetas = $conn->query($sql_recetas);

// Obtener comentarios del usuario
$sql_comentarios = "SELECT c.*, r.titulo as receta_titulo 
                    FROM comentarios c
                    JOIN recetas r ON c.receta_id = r.id
                    WHERE c.usuario_id = $usuario_id 
                    ORDER BY c.fecha DESC LIMIT 10";
$mis_comentarios = $conn->query($sql_comentarios);

// Obtener historial de ruleta
$sql_ruleta = "SELECT rh.*, r.titulo 
               FROM ruleta_historial rh
               JOIN recetas r ON rh.receta_id = r.id
               WHERE rh.usuario_id = $usuario_id 
               ORDER BY rh.fecha_seleccion DESC LIMIT 10";
$historial_ruleta = $conn->query($sql_ruleta);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Postesión</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #fef8f0; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        h1 { color: #8b5a2b; }
        .seccion { margin: 30px 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }
        .seccion h2 { margin-top: 0; }
        .receta { border-bottom: 1px solid #eee; padding: 10px 0; }
        .receta:last-child { border-bottom: none; }
        .admin-badge { background: #ff9800; color: white; padding: 3px 8px; border-radius: 5px; font-size: 12px; }
        .btn { background: #8b5a2b; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px; }
        .btn-eliminar { background: #c62828; }
        .btn-editar { background: #2e7d32; }
    </style>
</head>
<body>

<div class="container">
    <h1>👤 Mi Perfil</h1>
    
    <div class="seccion">
        <h2>Información personal</h2>
        <p><strong>Usuario:</strong> <?php echo $usuario['nombre_usuario']; ?></p>
        <p><strong>Email:</strong> <?php echo $usuario['email']; ?></p>
        <p><strong>Rol:</strong> 
            <?php if($usuario['rol'] == 'admin'): ?>
                <span class="admin-badge">Administrador</span>
            <?php else: ?>
                Usuario
            <?php endif; ?>
        </p>
        <p><strong>Fecha de registro:</strong> <?php echo $usuario['fecha_registro']; ?></p>
        <a href="editar_perfil.php" class="btn">✏️ Editar perfil</a>
    </div>
    
    <div class="seccion">
        <h2>📝 Mis recetas (<?php echo $mis_recetas->num_rows; ?>)</h2>
        <?php if($mis_recetas->num_rows > 0): ?>
            <?php while($receta = $mis_recetas->fetch_assoc()): ?>
                <div class="receta">
                    <strong><?php echo htmlspecialchars($receta['titulo']); ?></strong>
                    
                    <p><?php echo substr(htmlspecialchars($receta['descripcion']), 0, 100); ?>...</p>
                    <a href="receta_detalle.php?id=<?php echo $receta['id']; ?>" class="btn">Ver</a>
                    <a href="receta_detalle.php?id=<?php echo $receta['id']; ?>" class="btn btn-editar">✏️ Editar</a>
                    <a href="perfil.php?eliminar=<?php echo $receta['id']; ?>" class="btn btn-eliminar" onclick="return confirm('¿Eliminar esta receta?')">🗑️ Eliminar</a>
                </div> 
            <?php endwhile; ?>
        <?php else: ?>
            <p>No has subido ninguna receta aún.</p>
            <a href="subir_receta.php">➕ Subir mi primera receta</a>
        <?php endif; ?>
    </div>
    
    <div class="seccion">
        <h2>💬 Mis comentarios</h2>
        <?php if($mis_comentarios->num_rows > 0): ?>
            <?php while($comentario = $mis_comentarios->fetch_assoc()): ?>
                <div class="receta">
                    <p><strong>En: <?php echo htmlspecialchars($comentario['receta_titulo']); ?></strong></p>
                    <p><?php echo htmlspecialchars($comentario['contenido']); ?></p>
                    <small><?php echo $comentario['fecha']; ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No has comentado ninguna receta aún.</p>
        <?php endif; ?>
    </div>
    <div class="seccion">
        <h2>🎡 Últimas recetas que te tocaron</h2>
        <?php if($historial_ruleta->num_rows > 0): ?>
            <?php while($item = $historial_ruleta->fetch_assoc()): ?>
                <div class="receta">
                    <a href="receta_detalle.php?id=<?php echo $item['receta_id']; ?>">
                        <?php echo htmlspecialchars($item['titulo']); ?>
                    </a>
                    <small> - <?php echo $item['fecha_seleccion']; ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Todavía no usaste la ruleta. <a href="ruleta.php">¡Probala ahora!</a></p>
        <?php endif; ?>
    </div>
    
    <?php if($_SESSION['rol'] == 'admin'): ?>
    <div class="seccion">
        <h2>🔧 Panel de Administración</h2>
        <p>Tienes permisos especiales como administrador.</p>
        <a href="admin.php" class="btn">Ir al panel de admin →</a>
    </div>
    <?php endif; ?>
    
    <p><a href="index.php">← Volver al inicio</a></p>
</div>

</body>
</html>