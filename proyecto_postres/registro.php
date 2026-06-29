<?php
require 'conexion.php';
$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];
// Validacion 1: Formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ El email no es válido. Usá un formato como: nombre@ejemplo.com";
    }
    // Validacion 2: Dominio permitido
    else {
        $dominios_permitidos = ['gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com'];
        $partes = explode('@', $email);
        $dominio = strtolower(end($partes));
        
        if (!in_array($dominio, $dominios_permitidos)) {
            $error = "❌ El email debe tener un formato valido: gmail.com, hotmail.com, yahoo.com o outlook.com";
        }
    }
    // Validacion 3: Contraseña mínima
    if (empty($error) && strlen($contraseña) < 4) {
        $error = "❌ La contraseña debe tener al menos 4 caracteres";
    }
    //validacion 4: por lo menos 1 mayuscula, 1 numero y 1 caracter especial
   if (empty($error)) {
    if (!preg_match('/[A-Z]/', $contraseña)) {
        $error = "❌ La contraseña debe contener al menos una letra mayúscula (A-Z)";
    } 
    elseif (!preg_match('/[0-9]/', $contraseña)) {
        $error = "❌ La contraseña debe contener al menos un número (0-9)";
    }
    elseif (!preg_match('/[^a-zA-Z0-9]/', $contraseña)) {
        $error = "❌ La contraseña debe contener al menos un carácter especial (!@#$%^&* etc.)";
    } }
    if (empty($error)) {
        // Validacion 5: Verificar si el nombre de usuario ya existe
        $check = $conn->query("SELECT id FROM usuarios WHERE nombre_usuario = '$nombre_usuario'");
        if ($check->num_rows > 0) {
            $error = "❌ El nombre de usuario '$nombre_usuario' ya está en uso. Elegí otro.";
        } else {
            // Validación 6: Verificar si el email ya existe
            $check_email = $conn->query("SELECT id FROM usuarios WHERE email = '$email'");
            if ($check_email->num_rows > 0) {
                $error = "❌ El email '$email' ya está registrado. Usá otro o <a href='login.php'>iniciá sesión</a>.";
            } else {
                // Si paso todos los filtros, se registra
                $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);
                $rol = 'usuario';
                
                //PREPARED STATEMENTS
                $sql = "INSERT INTO usuarios (nombre_usuario, email, contraseña, rol) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $nombre_usuario, $email, $contraseña_hash, $rol);
                
                if ($stmt->execute()) {
                    $mensaje = "✅ Usuario registrado correctamente. <a href='login.php'>Iniciar sesión</a>";
                } else {
                    $error = "❌ Error al registrar: " . $stmt->error;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrarse — DulceTentación</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php session_start(); include 'header.php'; ?>

<div class="page-hero">
  <h1>Crear cuenta</h1>
  <p>Unite a la comunidad de repostería artesanal</p>
</div>

<section class="section" style="max-width:520px;margin:0 auto;">
  <div class="form-box">
    <h2>📝 Registro</h2>

   <?php if($mensaje): ?>
    <div class="alerta alerta-ok"><?php echo $mensaje; ?></div>
<?php elseif($error): ?>
    <div class="alerta alerta-error"><?php echo $error; ?></div>
<?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Nombre de usuario</label>
        <input type="text" name="nombre_usuario" placeholder="Ej: anacocinera" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="tu@email.com" required>
      </div>
      <div class="form-group">
        <label>Contraseña</label>
        <input type="password" name="contraseña" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn" style="width:100%;margin-top:.3rem;">Crear cuenta</button>
    </form>

    <p style="text-align:center;margin-top:1.2rem;font-size:.9rem;color:rgba(245,237,214,.5);">
      ¿Ya tenés cuenta? <a href="login.php" style="color:var(--caramelo);">Ingresá acá</a>
    </p>
  </div>
</section>

<footer>
  <a href="index.php" class="logo" style="font-size:1.3rem;">Dulce<span>Tentación</span></a>
  <p>© 2026 — DulceTentación</p>
</footer>

</body>
</html>
