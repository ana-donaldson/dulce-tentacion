<?php
require_once 'conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $rol = 'usuario';
    
    $sql = "INSERT INTO usuarios (nombre_usuario, email, contraseña, rol) 
            VALUES ('$nombre_usuario', '$email', '$contraseña', '$rol')";
    
    if ($conn->query($sql)) {
        $mensaje = "✅ Usuario registrado correctamente. <a href='login.php'>Iniciar sesión</a>";
    } else {
        $mensaje = "❌ Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        input { display: block; margin: 10px 0; padding: 8px; width: 250px; }
        button { padding: 10px 20px; }
        .mensaje { margin: 20px 0; padding: 10px; background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>📝 Registro de usuario</h1>
    
    <?php if($mensaje): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="contraseña" placeholder="Contraseña" required>
        <button type="submit">Registrarse</button>
    </form>
</body>
</html>