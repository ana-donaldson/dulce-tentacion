-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-05-2026 a las 00:16:19
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_postres`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Invierno', 'Postres calientes y reconfortantes ideales para el frío: brownies, tartas horneadas, fondue de chocolate, churros...'),
(2, 'Verano', 'Postres frescos y ligeros perfectos para el calor: helados, mousses, gelatinas, frutas, cheesecake frío...');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL,
  `receta_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `contenido`, `fecha`, `usuario_id`, `receta_id`) VALUES
(1, 'muy rico, recomendado!', '2026-05-05 01:35:26', 4, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

CREATE TABLE `recetas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ingredientes` text NOT NULL,
  `instrucciones` text NOT NULL,
  `tiempo_preparacion` int(11) DEFAULT NULL,
  `dificultad` enum('Fácil','Media','Difícil') DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `votos_total` int(11) DEFAULT 0,
  `votos_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recetas`
--

INSERT INTO `recetas` (`id`, `titulo`, `descripcion`, `ingredientes`, `instrucciones`, `tiempo_preparacion`, `dificultad`, `imagen_url`, `categoria_id`, `usuario_id`, `fecha_creacion`, `votos_total`, `votos_count`) VALUES
(3, 'Flan casero ', 'flan casero, postre cremoso, perfecto para cualquier ocasion.', 'para el caramelo:1 taza de azúcar, 1/4 taza de agua. \r\n\r\nPara el flan: 1 lata de leche condensada (395g), 1 lata de leche evaporada (360ml), 3 huevos grandes, 1 cucharadita de esencia de vainilla', '1. Preparar el caramelo: En una olla pequeña, mezclar el azúcar y el agua. Calentar a fuego medio sin revolver hasta que tome color ámbar.\r\n2. Verter el caramelo en un molde para flan, inclinándolo para que cubra el fondo y las paredes.\r\n3. Precalentar el horno a 180°C.\r\n4. En la licuadora, mezclar la leche condensada, leche evaporada, huevos y vainilla. Licuar por 2 minutos.\r\n5. Verter la mezcla en el molde acaramelado.\r\n6. Tapar el molde con papel aluminio.\r\n7. Colocar el molde dentro de una bandeja más grande con agua (baño María).\r\n8. Hornear por 50-60 minutos o hasta que al insertar un cuchillo salga limpio.\r\n9. Dejar enfriar, refrigerar por 4 horas, desmoldar y servir.\',', 60, 'Media', 'img/flan.jpg', 1, 3, '2026-05-04 17:40:50', 0, 0),
(4, 'Brownie de chocolate ', 'El clásico brownie súper chocolatoso, crocante por fuera y suave por dentro.', '200g de chocolate negro (70% cacao), 150g de manteca, 3 huevos, 200g de azúcar, 100g de harina, 1 cucharadita de esencia de vainilla, 50g de nueces (opcional)\'', '1. Precalentar el horno a 180°C.\r\n2. Derretir el chocolate con la manteca a baño María o en microondas.\r\n3. En un bol, batir los huevos con el azúcar hasta que estén espumosos.\r\n4. Agregar la mezcla de chocolate derretido y la vainilla. Mezclar.\r\n5. Incorporar la harina tamizada y las nueces si usas.\r\n6. Verter en un molde enmantecado (20x20cm).\r\n7. Hornear por 25-30 minutos.\r\n8. Dejar enfriar antes de cortar. El centro debe quedar húmedo.\',', 35, 'Fácil', 'img/1777942112_2442.jpg', 1, 4, '2026-05-05 00:48:32', 0, 0),
(5, 'Helado de vainilla casero ', 'Helado cremoso y fácil de hacer sin necesidad de máquina heladora.', '500ml de nata para montar, 1 lata de leche condensada, 1 cucharada de esencia de vainilla', '1. Batir la nata hasta que esté firme.\r\n2. Agregar la leche condensada y la vainilla.\r\n3. Mezclar suavemente.\r\n4. Congelar por 6 horas.\'', 15, 'Fácil', 'img/1777943296_4941.jpg', 2, 4, '2026-05-05 01:08:16', 0, 0),
(6, 'Cheescake de frutilla', 'Postre fresco y cremoso. Ideal para el verano porque no necesita horno.', 'Base:200g de galletitas, 100g de manteca derretida\r\n\r\nRelleno:500g de queso crema, 1 taza de azúcar, 200ml de crema de leche, 1 sobre de gelatina sin sabor,1 cucharadita de esencia de vainilla\r\n\r\nCobertura:300g de frutillas, 4 cucharadas de azúcar', '1. Triturar las galletitas y mezclar con manteca derretida.\r\n2. Presionar en un molde desmontable. Llevar a la heladera.\r\n3. Batir queso crema con azúcar.\r\n4. Hidratar la gelatina en agua, derretir y agregar.\r\n5. Agregar la crema de leche y mezclar.\r\n6. Verter sobre la base y refrigerar 4 horas.\r\n7. Para la cobertura: licuar frutillas con azúcar.\r\n8. Verter sobre el cheesecake y enfriar 2 horas más.', 40, 'Media', 'img/1777943664_7673.jpg', 2, 4, '2026-05-05 01:14:24', 0, 0),
(7, 'Tarta de manzana ', 'Tarta casera con manzanas caramelizadas. Perfecta para la merienda con mate o te.', 'Masa:200g de harina, 100g de manteca, 50g de azúcar, 1 huevo, 1 cucharadita de polvo de hornear\r\n\r\nRelleno:4 manzanas (rojas o verdes), 50g de azúcar,1 cucharadita de canela, Azúcar impalpable para decorar', '1. Mezclar harina, manteca, azúcar, huevo y polvo de hornear.\r\n2. Formar un bollo y refrigerar 30 minutos.\r\n3. Pelar y cortar manzanas en láminas finas.\r\n4. Estirar la masa y colocarla en un molde.\r\n5. Distribuir las manzanas en círculos superpuestos.\r\n6. Espolvorear azúcar y canela.\r\n7. Hornear 35-40 minutos a 180°C.\r\n8. Decorar con azúcar impalpable antes de servir.', 50, 'Fácil', 'img/1777944103_9583.jpg', 1, 4, '2026-05-05 01:21:43', 0, 0),
(8, 'Pavlova (merengue con frutas)', 'Postre elegante de merengue crocante por fuera y suave por dentro, coronado con crema y frutas.', 'Merengue:4 claras de huevo,200g de azúcar,1 cucharadita de maicena,1 cucharadita de vinagre blanco\r\n\r\nRelleno:200ml de crema de leche,2 cucharadas de azúcar impalpable,300g de frutillas,Hojas de menta', '1. Batir las claras a punto nieve.\r\n2. Agregar el azúcar de a poco, batiendo hasta que quede brillante.\r\n3. Incorporar la maicena y el vinagre.\r\n4. Formar un círculo de merengue en placa con papel manteca.\r\n5. Hornear 1 hora a 120°C. Apagar el horno y dejar enfriar adentro.\r\n6. Batir la crema con el azúcar.\r\n7. Cubrir el merengue con la crema.\r\n8. Decorar con frutillas y menta.', 80, 'Difícil', 'img/1777944466_9022.jpg', 2, 4, '2026-05-05 01:27:46', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ruleta_historial`
--

CREATE TABLE `ruleta_historial` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `receta_id` int(11) DEFAULT NULL,
  `fecha_seleccion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ruleta_historial`
--

INSERT INTO `ruleta_historial` (`id`, `usuario_id`, `receta_id`, `fecha_seleccion`) VALUES
(1, 4, 4, '2026-05-10 21:31:13'),
(2, 4, 4, '2026-05-10 22:18:57'),
(3, 4, 8, '2026-05-10 22:19:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `rol` enum('usuario','admin') DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `email`, `contraseña`, `fecha_registro`, `rol`) VALUES
(3, 'ana', 'anadonaldson700@gmail.com', '$2y$10$wrFVnEPJigf03PqBSt.areXPjb/c1uYskKouj4DOXg5HQGVydUzsi', '2026-05-03 20:59:49', 'usuario'),
(4, 'admin', 'admin@gmail.com', '$2y$10$HYUo5Gp.vnzg3ZwDA/D5TuQm/j.XjO/TEbZJyWoyCZ57hRgSRfixi', '2026-05-05 00:33:33', 'admin'),
(5, 'usuario', 'usuario@gmail.com', '$2y$10$DvtJ4WxLOp8698uiUYItiOvucgbkR7FnL/K79h.XchlvaHYAzXySa', '2026-05-10 21:06:28', 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoraciones`
--

CREATE TABLE `valoraciones` (
  `usuario_id` int(11) NOT NULL,
  `receta_id` int(11) NOT NULL,
  `puntuacion` int(11) DEFAULT NULL CHECK (`puntuacion` between 1 and 5),
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `receta_id` (`receta_id`);

--
-- Indices de la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `ruleta_historial`
--
ALTER TABLE `ruleta_historial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `receta_id` (`receta_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD PRIMARY KEY (`usuario_id`,`receta_id`),
  ADD KEY `receta_id` (`receta_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ruleta_historial`
--
ALTER TABLE `ruleta_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD CONSTRAINT `recetas_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `recetas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `ruleta_historial`
--
ALTER TABLE `ruleta_historial`
  ADD CONSTRAINT `ruleta_historial_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ruleta_historial_ibfk_2` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD CONSTRAINT `valoraciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `valoraciones_ibfk_2` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
