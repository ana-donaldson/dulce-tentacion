<?php
require 'conexion.php';
$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];
    
    // Validación 1: Formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ El email no es válido. Usá un formato como: nombre@ejemplo.com";
    }
    // Validación 2: Dominio permitido
    else {
        $dominios_permitidos = ['gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com'];
        $partes = explode('@', $email);
        $dominio = strtolower(end($partes));
        
        if (!in_array($dominio, $dominios_permitidos)) {
            $error = "❌ El email debe tener un formato valido: gmail.com, hotmail.com, yahoo.com o outlook.com";
        }
    }
    
    // Validación 3: Contraseña mínima
    if (empty($error) && strlen($contraseña) < 4) {
        $error = "❌ La contraseña debe tener al menos 4 caracteres";
    }
    
    if (empty($error)) {
        // Validación 4: Verificar si el nombre de usuario ya existe
        $check = $conn->query("SELECT id FROM usuarios WHERE nombre_usuario = '$nombre_usuario'");
        if ($check->num_rows > 0) {
            $error = "❌ El nombre de usuario '$nombre_usuario' ya está en uso. Elegí otro.";
        } else {
            // Validación 5: Verificar si el email ya existe
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
<html>
<head>
    <title>Registro</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        input { display: block; margin: 10px 0; padding: 8px; width: 250px; }
        button { padding: 10px 20px; }
        .mensaje { margin: 20px 0; padding: 10px; background: #d4edda; color: #155724; border-radius: 5px; }
        .error { margin: 20px 0; padding: 10px; background: #f8d7da; color: #721c24; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>📝 Registro de usuario</h1>    
    <?php if($mensaje): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>    
    <form method="POST">
        <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
        <input type="email" name="email" placeholder="Email (ejemplo@correo.com)" required>
        <input type="password" name="contraseña" placeholder="Contraseña (mínimo 4 caracteres)" required>
        <button type="submit">Registrarse</button>
    </form>
    <p>¿Ya tenés cuenta? <a href="login.php">Iniciar sesión</a></p>
</body>
</html>
