<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
$usuario_id = $_SESSION['usuario_id'];
$mensaje = '';
$error = '';

$sql = "SELECT * FROM usuarios WHERE id = $usuario_id";
$usuario = $conn->query($sql)->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    //si se quiere cambiar el email
    if (isset($_POST['cambiar_email'])) {
        $nuevo_email = $conn->real_escape_string($_POST['email']);
        
        // VALIDACIÓN 1: Formato de email
        if (!filter_var($nuevo_email, FILTER_VALIDATE_EMAIL)) {
            $error = "❌ El email no es válido. Usá un formato como: nombre@ejemplo.com";
        }
        // VALIDACIÓN 2: Dominio permitido
        else {
            $dominios_permitidos = ['gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com'];
            $partes = explode('@', $nuevo_email);
            $dominio = strtolower(end($partes));
            
            if (!in_array($dominio, $dominios_permitidos)) {
                $error = "❌ El email debe tener un formato valido: gmail.com, hotmail.com, yahoo.com o outlook.com";
            }
        } 
        // VALIDACIÓN 3: que el email no sea el mismo que el actual
        if (empty($error) && $nuevo_email == $usuario['email']) {
            $error = "❌ El nuevo email es igual al actual";
        }
        // VALIDACIÓN 4: que el email no exista ya en otro usuario
        elseif (empty($error)) {
            $check = $conn->query("SELECT id FROM usuarios WHERE email = '$nuevo_email' AND id != $usuario_id");
            
            if ($check->num_rows > 0) {
                $error = "❌ Ese email ya está registrado por otro usuario";
            } else {
                $conn->query("UPDATE usuarios SET email = '$nuevo_email' WHERE id = $usuario_id");
                $mensaje = "✅ Email actualizado correctamente";
                $usuario['email'] = $nuevo_email;
            }
        }
    }
    //cambio de contraseña
if (isset($_POST['cambiar_password'])) {
    $nueva_password = $_POST['nueva_password'];
    $confirmar_password = $_POST['confirmar_password'];
    
    // Validación 1: que coincidan
    if ($nueva_password != $confirmar_password) {
        $error = "❌ Las contraseñas no coinciden";
    }
    // Validación 2: longitud mínima
    elseif (strlen($nueva_password) < 4) {
        $error = "❌ La contraseña debe tener al menos 4 caracteres";
    }
    // Validación 3: al menos 1 mayúscula
    elseif (!preg_match('/[A-Z]/', $nueva_password)) {
        $error = "❌ La contraseña debe contener al menos una letra mayúscula (A-Z)";
    }
    // Validación 4: al menos 1 número
    elseif (!preg_match('/[0-9]/', $nueva_password)) {
        $error = "❌ La contraseña debe contener al menos un número (0-9)";
    }
    // Validación 5: al menos 1 carácter especial
    elseif (!preg_match('/[^a-zA-Z0-9]/', $nueva_password)) {
        $error = "❌ La contraseña debe contener al menos un carácter especial (!@#$%^&* etc.)";
    }
    // Si pasa todas las validaciones
    else {
        $hash = password_hash($nueva_password, PASSWORD_DEFAULT);
        $conn->query("UPDATE usuarios SET contraseña = '$hash' WHERE id = $usuario_id");
        $mensaje = "✅ Contraseña actualizada correctamente";
    }
}
    //auto eliminacion de cuenta 
    if (isset($_POST['eliminar_cuenta'])) {
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
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Perfil — DulceTentación</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="page-hero">
  <h1>✏️ Editar Perfil</h1>
  <p><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></p>
</div>

<section class="section" style="max-width:580px;margin:0 auto;">

  <?php if($mensaje): ?>
    <div class="alerta alerta-ok"><?php echo $mensaje; ?></div>
  <?php endif; ?>
  <?php if($error): ?>
    <div class="alerta alerta-error"><?php echo $error; ?></div>
  <?php endif; ?>

  <!-- Cambiar email -->
  <div class="perfil-card">
    <h2>📧 Cambiar Email</h2>
    <form method="POST">
      <div class="form-group">
        <label>Email actual</label>
        <input type="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled style="opacity:.5;">
      </div>
      <div class="form-group">
        <label>Nuevo email</label>
        <input type="email" name="email" placeholder="nuevo@email.com" required>
      </div>
      <button type="submit" name="cambiar_email" class="btn" style="font-size:.82rem;padding:.5rem 1.2rem;">Actualizar Email</button>
    </form>
  </div>

  <!-- Cambiar contraseña -->
  <div class="perfil-card">
    <h2>🔒 Cambiar Contraseña</h2>
    <form method="POST">
      <div class="form-group">
        <label>Nueva contraseña <small style="color:rgba(245,237,214,.4);">(mínimo 4 caracteres)</small></label>
        <input type="password" name="nueva_password" placeholder="••••••••" required>
      </div>
      <div class="form-group">
        <label>Confirmar contraseña</label>
        <input type="password" name="confirmar_password" placeholder="••••••••" required>
      </div>
      <button type="submit" name="cambiar_password" class="btn" style="font-size:.82rem;padding:.5rem 1.2rem;">Actualizar Contraseña</button>
    </form>
  </div>

  <!-- Eliminar cuenta -->
  <div class="perfil-card" style="border-color:rgba(220,80,80,.25);">
    <h2 style="color:#e88a8a;">⚠️ Eliminar cuenta</h2>
    <p style="color:rgba(245,237,214,.55);margin-bottom:1.2rem;font-size:.92rem;">
      Esta acción es irreversible. Se borrarán TODOS tus datos: recetas, comentarios, etc.
    </p>
    <form method="POST" onsubmit="return confirm('¿Estás SEGURO de que querés eliminar tu cuenta? No se puede deshacer.');">
      <button type="submit" name="eliminar_cuenta" class="btn btn-danger" style="font-size:.82rem;padding:.5rem 1.2rem;">🗑️ Eliminar mi cuenta</button>
    </form>
  </div>

  <p><a href="perfil.php" class="volver">← Volver a mi perfil</a></p>

</section>

<footer>
  <a href="index.php" class="logo" style="font-size:1.3rem;">Dulce<span>Tentación</span></a>
  <p>© 2026 — DulceTentación</p>
</footer>

</body>
</html>
