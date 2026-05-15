<?php
session_start();
require_once 'conexion.php';

// verificar que sea admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header('Location: index.php');
    exit;
}

$total_usuarios = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'];
$total_recetas = $conn->query("SELECT COUNT(*) as total FROM recetas")->fetch_assoc()['total'];
$total_comentarios = $conn->query("SELECT COUNT(*) as total FROM comentarios")->fetch_assoc()['total'];

//para eliminar contenido
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

//obtener datos para mostrar
$usuarios = $conn->query("SELECT * FROM usuarios ORDER BY fecha_registro DESC");
$recetas = $conn->query("SELECT r.*, u.nombre_usuario FROM recetas r JOIN usuarios u ON r.usuario_id = u.id ORDER BY r.fecha_creacion DESC");
$comentarios = $conn->query("SELECT c.*, u.nombre_usuario, r.titulo as receta_titulo 
                             FROM comentarios c 
                             JOIN usuarios u ON c.usuario_id = u.id 
                             JOIN recetas r ON c.receta_id = r.id 
                             ORDER BY c.fecha DESC LIMIT 20");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Postesion</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #fef8f0; }
        .container { max-width: 1200px; margin: auto; }
        .header { background: #8b5a2b; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .stats { display: flex; gap: 20px; margin-bottom: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; flex: 1; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stat-card h3 { margin: 0; font-size: 2rem; color: #8b5a2b; }
        .seccion { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .seccion h2 { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5c6a0; }
        .btn-eliminar { color: red; text-decoration: none; }
        .btn-verde { color: green; text-decoration: none; }
        .mensaje { background: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🔧 Panel de Administración</h1>
        <p>Bienvenido, <?php echo $_SESSION['nombre_usuario']; ?></p>
        <a href="index.php" style="color: white;">← Volver al inicio</a>
    </div>

    <?php if($mensaje): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <div class="stats">
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

    <div class="seccion">
        <h2>👥 Usuarios registrados</h2>
        <table>
            <tr><th>ID</th><th>Usuario</th><th>Email</th><th>Rol</th><th>Fecha</th><th>Acciones</th></tr>
            <?php while($u = $usuarios->fetch_assoc()): ?>
            <tr>
                <td><?php echo $u['id']; ?></td>
                <td><?php echo $u['nombre_usuario']; ?></td>
                <td><?php echo $u['email']; ?></td>
                <td><?php echo $u['rol'] == 'admin' ? '👑 Admin' : '👤 Usuario'; ?></td>
                <td><?php echo $u['fecha_registro']; ?></td>
                <td>
                    <?php if($u['rol'] != 'admin'): ?>
                        <a href="?eliminar_usuario=<?php echo $u['id']; ?>" class="btn-eliminar" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                    <?php else: ?>
                        <span>—</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="seccion">
        <h2>📖 Todas las recetas</h2>
        <table>
            <tr><th>ID</th><th>Título</th><th>Autor</th><th>Fecha</th><th>Acciones</th></tr>
            <?php while($r = $recetas->fetch_assoc()): ?>
            <tr>
            <td><?php echo $r['id']; ?></td>
            <td><?php echo htmlspecialchars($r['titulo']); ?></td>
            <td><?php echo $r['nombre_usuario']; ?></td>
            <td><?php echo $r['fecha_creacion']; ?></td>
            <td>
                <a href="receta_detalle.php?id=<?php echo $r['id']; ?>" class="btn-verde">Ver</a>
                <a href="?eliminar_receta=<?php echo $r['id']; ?>" class="btn-eliminar" onclick="return confirm('¿Eliminar esta receta?')">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="seccion">
        <h2>💬 Últimos comentarios</h2>
        <?php if($comentarios->num_rows > 0): ?>
            <table>
            <tr><th>Usuario</th><th>Receta</th><th>Comentario</th><th>Fecha</th><th>Acción</th></tr>
            <?php while($c = $comentarios->fetch_assoc()): ?>
            <tr>
                <td><?php echo $c['nombre_usuario']; ?></td>
                <td><?php echo htmlspecialchars($c['receta_titulo']); ?></td>
                <td><?php echo substr(htmlspecialchars($c['contenido']), 0, 50); ?>...</td>
                <td><?php echo $c['fecha']; ?></td>
                <td><a href="?eliminar_comentario=<?php echo $c['id']; ?>" class="btn-eliminar" onclick="return confirm('¿Eliminar este comentario?')">Eliminar</a></td>
            </tr>
            <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No hay comentarios aún.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>