Dulce Tentación

Plataforma web de recetas de postres con ruleta aleatoria para decidir qué postre hacer.
descripcion: es una aplicación web diseñada para ayudar a los usuarios a decidir qué postre preparar, resolviendo la indecisión cotidiana a través de una ruleta aleatoria.
Los usuarios pueden registrarse, iniciar sesión, subir sus propias recetas con imágenes, comentar y puntuar las recetas de otros usuarios con un sistema de estrellas.

Objetivo: Facilitar la elección de postres mediante una ruleta interactiva, fomentando la participación de la comunidad a través de recetas, comentarios y valoraciones.

Funcionalidades principales:

-  Sistema de autenticación (registro, login, logout)
-  CRUD completo de recetas (crear, leer, actualizar, eliminar)
-  Comentarios con edición y eliminación
-  Sistema de valoración por estrellas (1 a 5)
-  Ruleta de postres aleatorios con historial por usuario
-  Filtro por categorías (Invierno/Verano)
- Panel de administración (gestionar usuarios, recetas y comentarios)
- Perfil de usuario con recetas, comentarios e historial de ruleta

Tecnologías utilizadas

PHP 8.2: Lógica del servidor, procesamiento de formularios, manejo de sesiones 
MySQL: Base de datos (8 tablas relacionadas) 
HTML5: Estructura de las páginas 
CSS: Estilos visuales y diseño responsive
JavaScript: Interacciones en el frontend (edición de comentarios)
XAMPP: Entorno de desarrollo local 
GitHub: Control de versiones y trabajo colaborativo 


Estructura de la base de datos
`usuarios` Almacena usuarios registrados (id, nombre, email, contraseña hash, rol) 
`categorias` Categorías (Invierno/Verano) 
`recetas` Recetas de postres (título, descripción, ingredientes, instrucciones, imagen, votos) 
`comentarios` Comentarios de usuarios en recetas 
`valoraciones` Puntuaciones por estrellas (1 a 5) por usuario y receta 
`ruleta_historial` Historial de selecciones de la ruleta 

Archivo de exportación: dulce_tentacion(2).sql

## 📁 Estructura del proyecto
