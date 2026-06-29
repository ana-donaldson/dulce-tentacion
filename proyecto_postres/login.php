<?php
session_start();
require 'conexion.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contraseña = $_POST['contraseña'];

    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$nombre_usuario'";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($contraseña, $usuario['contraseña'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
            $_SESSION['rol'] = $usuario['rol'];
            header('Location: index.php?mensaje=bienvenido');
    exit;
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión — DulceTentación</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="page-hero">
  <h1>Ingresar</h1>
  <p>Accedé a tu cuenta para comentar y subir recetas</p>
</div>

<section class="section" style="max-width:520px;margin:0 auto;">
  <div class="form-box">
    <h2>🔐 Iniciar sesión</h2>

    <?php if($error): ?>
      <div class="alerta alerta-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Nombre de usuario</label>
        <input type="text" name="nombre_usuario" placeholder="Tu usuario" required>
      </div>
      <div class="form-group">
        <label>Contraseña</label>
        <input type="password" name="contraseña" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn" style="width:100%;margin-top:.3rem;">Entrar</button>
    </form>
    <p style="text-align:center;margin-top:1.2rem;font-size:.9rem;color:rgba(245,237,214,.5);">
      ¿No tenés cuenta? <a href="registro.php" style="color:var(--caramelo);">Registrate acá</a>
    </p>
  </div>
</section>
<footer>
  <a href="index.php" class="logo" style="font-size:1.3rem;">Dulce<span>Tentación</span></a>
  <p>© 2026 — DulceTentación</p>
</footer>
</body>
</html>
