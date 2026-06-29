<nav>
  <a href="index.php" class="logo">Dulce<span>Tentación</span></a>
  <ul>
    <li><a href="index.php">Inicio</a></li>
    <li><a href="recetas.php">Recetas</a></li>
    <li><a href="ruleta.php">Ruleta</a></li>
    <?php if(isset($_SESSION['usuario_id'])): ?>
      <li><a href="perfil.php">👤 <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></a></li>
      <li><a href="subir_receta.php" class="btn" style="padding:.4rem 1rem;font-size:.78rem;">+ Subir receta</a></li>
      <li><a href="logout.php">Salir</a></li>
    <?php else: ?>
      <li><a href="login.php">Ingresar</a></li>
      <li><a href="registro.php" class="btn" style="padding:.4rem 1rem;font-size:.78rem;">Registrarse</a></li>
    <?php endif; ?>
  </ul>
</nav>
