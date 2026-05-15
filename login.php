<?php
session_start();
require_once 'conexion.php';
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
            
            header('Location: index.php');
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
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        input { display: block; margin: 10px 0; padding: 8px; width: 250px; }
        button { padding: 10px 20px; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>🔐 Iniciar sesión</h1>    
    <?php if($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="nombre_usuario" placeholder="Usuario" required>
        <input type="password" name="contraseña" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
    <p>¿No tenés cuenta? <a href="registro.php">Registrate</a></p>
</body>
</html>