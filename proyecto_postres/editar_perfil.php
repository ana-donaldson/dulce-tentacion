<?php
//nuevo poner en github
session_start();
require_once 'conexion.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$mensaje = '';
$error = '';

// Obtener datos actuales del usuario
$sql = "SELECT * FROM usuarios WHERE id = $usuario_id";
$usuario = $conn->query($sql)->fetch_assoc();

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Si se quiere cambiar el email
    if (isset($_POST['cambiar_email'])) {
        $nuevo_email = $conn->real_escape_string($_POST['email']);
        
        // Verificar que el email no exista ya
        $check = $conn->query("SELECT id FROM usuarios WHERE email = '$nuevo_email' AND id != $usuario_id");
        
        if ($check->num_rows > 0) {
            $error = "❌ Ese email ya está registrado por otro usuario";
        } else {
            $conn->query("UPDATE usuarios SET email = '$nuevo_email' WHERE id = $usuario_id");
            $mensaje = "✅ Email actualizado correctamente";
            $usuario['email'] = $nuevo_email; // Actualizar para mostrar
        }
    }
    // Si se quiere cambiar la contraseña
    if (isset($_POST['cambiar_password'])) {
        $nueva_password = $_POST['nueva_password'];
        $confirmar_password = $_POST['confirmar_password'];
        
        if ($nueva_password == $confirmar_password && strlen($nueva_password) >= 4) {
            $hash = password_hash($nueva_password, PASSWORD_DEFAULT);
            $conn->query("UPDATE usuarios SET contraseña = '$hash' WHERE id = $usuario_id");
            $mensaje = "✅ Contraseña actualizada correctamente";
        } else {
            $error = "❌ Las contraseñas no coinciden o son muy cortas (mínimo 4 caracteres)";
        }
    }  
    // Si se quiere eliminar la cuenta
    if (isset($_POST['eliminar_cuenta'])) {
        // Verificar que no sea el único admin
        if ($usuario['rol'] == 'admin') {
            $admins = $conn->query("SELECT id FROM usuarios WHERE rol = 'admin'");
            if ($admins->num_rows <= 1) {
                $error = "❌ No podés eliminar la única cuenta de administrador";
            } else {
                $conn->query("DELETE FROM usuarios WHERE id = $usuario_id");
                session_destroy();
                header('Location: index.php?mensaje=cuenta_eliminada');
                exit;
            }
        } else {
            $conn->query("DELETE FROM usuarios WHERE id = $usuario_id");
            session_destroy();
            header('Location: index.php?mensaje=cuenta_eliminada');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - Postesión</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #fef8f0; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        h1 { color: #8b5a2b; }
        .seccion { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }
        label { display: block; margin: 10px 0 5px; }
        input { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #8b5a2b; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-rojo { background: #c62828; }
        .mensaje { background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 10px 0; }
        hr { margin: 20px 0; }
    </style>
</head>
<body>
<div class="container">
    <h1>✏️ Editar Perfil</h1>
    <p>Usuario: <strong><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></strong></p>
    
    <?php if($mensaje): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    
    <?php if($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <!-- Cambiar Email -->
    <div class="seccion">
        <h2>📧 Cambiar Email</h2>
        <form method="POST">
            <label>Email actual:</label>
            <input type="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled>
            <label>Nuevo email:</label>
            <input type="email" name="email" required>
            <button type="submit" name="cambiar_email">Actualizar Email</button>
        </form>
    </div>    
    <!-- Cambiar Contraseña -->
    <div class="seccion">
        <h2>🔒 Cambiar Contraseña</h2>
        <form method="POST">
            <label>Nueva contraseña (mínimo 4 caracteres):</label>
            <input type="password" name="nueva_password" required>
            <label>Confirmar contraseña:</label>
            <input type="password" name="confirmar_password" required>
            <button type="submit" name="cambiar_password">Actualizar Contraseña</button>
        </form>
    </div>
    <!-- Eliminar Cuenta -->
    <div class="seccion">
        <h2>⚠️ borrar cuenta</h2>
        <p>Esta acción es irreversible. Se borrarán TODOS tus datos (recetas, comentarios, etc.)</p>
        <form method="POST" onsubmit="return confirm('¿Estás SEGURO de que querés eliminar tu cuenta? No se puede deshacer.');">
            <button type="submit" name="eliminar_cuenta" class="btn-rojo">🗑️ Eliminar mi cuenta</button>
        </form>
    </div>
    <p><a href="perfil.php">← Volver a mi perfil</a></p>
</div>
</body>
</html>