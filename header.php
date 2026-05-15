
<header>
    <h1>🍰 Postesión</h1>
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="recetas.php">Recetas</a></li>
            <li><a href="ruleta.php">Ruleta</a></li>
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <li><a href="perfil.php">👤 <?php echo $_SESSION['nombre_usuario']; ?></a></li>
                <li><a href="subir_receta.php">➕ Subir receta</a></li>
                <li><a href="logout.php">🚪 Salir</a></li>
            <?php else: ?>
                <li><a href="login.php">🔐 Iniciar sesión</a></li>
                <li><a href="registro.php">📝 Registrarse</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>