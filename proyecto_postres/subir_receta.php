<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit; }
$mensaje = '';
$error = '';
$receta_subida = false; //para saber si se subio (ayuda a css a
                       //mostrar el mensaje de exito)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['volver_a_subir'])) {
    
    //si no llega o viene vacio, usa facil por descarte 
    if (!isset($_POST['dificultad']) || trim($_POST['dificultad']) == '') {
        $dificultad = 'Fácil'; 
        } else {
        $dificultad = $_POST['dificultad'];    }
    $imagen_url = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $carpeta_destino = 'img/';   
        if (!file_exists($carpeta_destino)) {
            mkdir($carpeta_destino, 0777, true); }
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombre_archivo = time() . '_' . rand(1000, 9999) . '.' . $extension;
        $ruta_completa = $carpeta_destino . $nombre_archivo;
        
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        
        if (in_array($_FILES['imagen']['type'], $tipos_permitidos)) {
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_completa)) {
                $imagen_url = $ruta_completa;
            } else {
                $error = "Error al subir la imagen";    }
        } else {
            $error = "Solo se permite JPG, PNG o GIF"; }
    }
    if (empty($error)) {
        $sql = "INSERT INTO recetas (titulo, descripcion, ingredientes, instrucciones, 
                tiempo_preparacion, dificultad, categoria_id, usuario_id, imagen_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssiis",
            $_POST['titulo'],
            $_POST['descripcion'],
            $_POST['ingredientes'],
            $_POST['instrucciones'],
            $_POST['tiempo_preparacion'],
            $dificultad,      
            $_POST['categoria_id'],
            $_SESSION['usuario_id'],
            $imagen_url        );
        if ($stmt->execute()) {        
            $mensaje = "✅ ¡Receta subida correctamente!";
            $receta_subida = true; //marca que se subio cuando es true(para css)
        } else {
            $error = "❌ Error al guardar: " . $stmt->error; }
    }
}
$categorias = $conn->query("SELECT * FROM categorias");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Subir receta — DulceTentación</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="page-hero">
  <h1>➕ Subir nueva receta</h1>
  <p>Compartí tu postre favorito con la comunidad</p>
</div>

<section class="section" style="max-width:640px;margin:0 auto;">

  <?php if($mensaje): ?>
  <div class="alerta alerta-ok">
    <?php echo $mensaje; ?>
    <div style="margin-top: 1rem; display: flex; gap: 0.8rem; justify-content: center;">
      <a href="subir_receta.php" class="btn" style="background: var(--caramelo);">➕ Subir otra receta</a>
      <a href="index.php" class="btn" style="background: #6c757d;">🏠 Volver al inicio</a>
    </div>
  </div>
<?php endif; ?>

<?php if($error): ?>
  <div class="alerta alerta-error">❌ <?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="form-box" style="max-width:100%; <?php echo $receta_subida ? 'display:none;' : ''; ?>">
  <h2>Nueva receta</h2>
  <form method="POST" enctype="multipart/form-data">

  <div class="form-box" style="max-width:100%;">
    <h2>Nueva receta</h2>
    <form method="POST" enctype="multipart/form-data">

      <div class="form-group">
        <label>Título *</label>
        <input type="text" name="titulo" placeholder="Ej: Brownie de chocolate con nueces" required>
      </div>

      <div class="form-group">
        <label>Descripción breve</label>
        <textarea name="descripcion" rows="2" placeholder="Una descripción corta y apetitosa..."></textarea>
      </div>

      <div class="form-group">
        <label>Ingredientes * <small style="color:rgba(62,36,16,.4);font-size:.75rem;">(en párrafos o lista)</small></label>
        <textarea name="ingredientes" rows="5" placeholder="Para el caramelo: 1 taza de azúcar, 1/4 taza de agua.&#10;Para el flan: 1 lata de leche condensada..." required></textarea>
      </div>

      <div class="form-group">
        <label>Instrucciones * <small style="color:rgba(245,237,214,.4);font-size:.75rem;">(un paso por línea)</small></label>
        <textarea name="instrucciones" rows="6" placeholder="Derretí el chocolate con la manteca a baño María.&#10;Batí los huevos con el azúcar..." required></textarea>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
        <div class="form-group">
          <label>Tiempo (min)</label>
          <input type="number" name="tiempo_preparacion" value="30" min="1">
        </div>
        <div class="form-group">
          <label>Dificultad</label>
          <select name="dificultad">
            <option>Fácil</option>
            <option>Media</option>
            <option>Difícil</option>
          </select>
        </div>
        <div class="form-group">
          <label>Categoría</label>
          <select name="categoria_id">
            <?php while($cat = $categorias->fetch_assoc()): ?>
              <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></option>
            <?php endwhile; ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label>Imagen del postre</label>
        <input type="file" name="imagen" accept="image/*" style="color:var(--crema);">
      </div>

      <button type="submit" class="btn" style="width:100%;">📤 Subir receta</button>
    </form>
  </div>

  <p style="margin-top:1rem;"><a href="index.php" class="volver">← Volver al inicio</a></p>

</section>
<footer>
  <a href="index.php" class="logo" style="font-size:1.3rem;">Dulce<span>Tentación</span></a>
  <p>© 2026 — DulceTentación</p>
</footer>
</body>
</html>
