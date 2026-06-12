-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-06-2026 a las 22:13:51
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
(1, 'muy rico, recomendado!', '2026-05-05 01:35:26', 4, 7),
(2, 'feoo', '2026-06-03 17:45:16', 4, 14),
(5, 'muy feop', '2026-06-03 17:45:02', 4, 14),
(6, 'bastante ricos', '2026-06-04 19:06:05', 4, 17),
(8, 'ashlelele ashlelass', '2026-06-05 17:35:54', 4, 11);

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
  `dificultad` enum('Fácil','Media','Difícil') NOT NULL DEFAULT 'Fácil',
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
(3, 'Flan casero ', 'flan casero, postre cremoso, perfecto para cualquier ocasion.', 'para el caramelo:1 taza de azúcar, 1/4 taza de agua. \r\n\r\nPara el flan: 1 lata de leche condensada (395g), 1 lata de leche evaporada (360ml), 3 huevos grandes, 1 cucharadita de esencia de vainilla', '1. Preparar el caramelo: En una olla pequeña, mezclar el azúcar y el agua. Calentar a fuego medio sin revolver hasta que tome color ámbar.\r\n2. Verter el caramelo en un molde para flan, inclinándolo para que cubra el fondo y las paredes.\r\n3. Precalentar el horno a 180°C.\r\n4. En la licuadora, mezclar la leche condensada, leche evaporada, huevos y vainilla. Licuar por 2 minutos.\r\n5. Verter la mezcla en el molde acaramelado.\r\n6. Tapar el molde con papel aluminio.\r\n7. Colocar el molde dentro de una bandeja más grande con agua (baño María).\r\n8. Hornear por 50-60 minutos o hasta que al insertar un cuchillo salga limpio.\r\n9. Dejar enfriar, refrigerar por 4 horas, desmoldar y servir.\',', 60, 'Media', 'img/flan.jpg', 1, 3, '2026-05-04 17:40:50', 4, 1),
(4, 'Brownie de chocolate ', 'El clásico brownie súper chocolatoso, crocante por fuera y suave por dentro.', '200g de chocolate negro (70% cacao), 150g de manteca, 3 huevos, 200g de azúcar, 100g de harina, 1 cucharadita de esencia de vainilla, 50g de nueces (opcional)\'', '1. Precalentar el horno a 180°C.\r\n2. Derretir el chocolate con la manteca a baño María o en microondas.\r\n3. En un bol, batir los huevos con el azúcar hasta que estén espumosos.\r\n4. Agregar la mezcla de chocolate derretido y la vainilla. Mezclar.\r\n5. Incorporar la harina tamizada y las nueces si usas.\r\n6. Verter en un molde enmantecado (20x20cm).\r\n7. Hornear por 25-30 minutos.\r\n8. Dejar enfriar antes de cortar. El centro debe quedar húmedo.\',', 35, 'Fácil', 'img/1777942112_2442.jpg', 1, 4, '2026-05-05 00:48:32', 0, 0),
(5, 'Helado de vainilla casero ', 'Helado cremoso y fácil de hacer sin necesidad de máquina heladora.', '500ml de nata para montar, 1 lata de leche condensada, 1 cucharada de esencia de vainilla', '1. Batir la nata hasta que esté firme.\r\n2. Agregar la leche condensada y la vainilla.\r\n3. Mezclar suavemente.\r\n4. Congelar por 6 horas.\'', 15, 'Fácil', 'img/1777943296_4941.jpg', 2, 4, '2026-05-05 01:08:16', 5, 2),
(6, 'Cheescake de frutilla', 'Postre fresco y cremoso. Ideal para el verano porque no necesita horno.', 'Base:200g de galletitas, 100g de manteca derretida\r\n\r\nRelleno:500g de queso crema, 1 taza de azúcar, 200ml de crema de leche, 1 sobre de gelatina sin sabor,1 cucharadita de esencia de vainilla\r\n\r\nCobertura:300g de frutillas, 4 cucharadas de azúcar', '1. Triturar las galletitas y mezclar con manteca derretida.\r\n2. Presionar en un molde desmontable. Llevar a la heladera.\r\n3. Batir queso crema con azúcar.\r\n4. Hidratar la gelatina en agua, derretir y agregar.\r\n5. Agregar la crema de leche y mezclar.\r\n6. Verter sobre la base y refrigerar 4 horas.\r\n7. Para la cobertura: licuar frutillas con azúcar.\r\n8. Verter sobre el cheesecake y enfriar 2 horas más.', 40, 'Media', 'img/1777943664_7673.jpg', 2, 4, '2026-05-05 01:14:24', 5, 1),
(7, 'Tarta de manzana ', 'Tarta casera con manzanas caramelizadas. Perfecta para la merienda con mate o te.', 'Masa:200g de harina, 100g de manteca, 50g de azúcar, 1 huevo, 1 cucharadita de polvo de hornear\r\n\r\nRelleno:4 manzanas (rojas o verdes), 50g de azúcar,1 cucharadita de canela, Azúcar impalpable para decorar', '1. Mezclar harina, manteca, azúcar, huevo y polvo de hornear.\r\n2. Formar un bollo y refrigerar 30 minutos.\r\n3. Pelar y cortar manzanas en láminas finas.\r\n4. Estirar la masa y colocarla en un molde.\r\n5. Distribuir las manzanas en círculos superpuestos.\r\n6. Espolvorear azúcar y canela.\r\n7. Hornear 35-40 minutos a 180°C.\r\n8. Decorar con azúcar impalpable antes de servir.', 50, 'Fácil', 'img/1777944103_9583.jpg', 1, 4, '2026-05-05 01:21:43', 0, 0),
(8, 'Pavlova (merengue con frutas)', 'Postre elegante de merengue crocante por fuera y suave por dentro, coronado con crema y frutas.', 'Merengue:4 claras de huevo,200g de azúcar,1 cucharadita de maicena,1 cucharadita de vinagre blanco\r\n\r\nRelleno:200ml de crema de leche,2 cucharadas de azúcar impalpable,300g de frutillas,Hojas de menta', '1. Batir las claras a punto nieve.\r\n2. Agregar el azúcar de a poco, batiendo hasta que quede brillante.\r\n3. Incorporar la maicena y el vinagre.\r\n4. Formar un círculo de merengue en placa con papel manteca.\r\n5. Hornear 1 hora a 120°C. Apagar el horno y dejar enfriar adentro.\r\n6. Batir la crema con el azúcar.\r\n7. Cubrir el merengue con la crema.\r\n8. Decorar con frutillas y menta.', 80, 'Difícil', 'img/1777944466_9022.jpg', 2, 4, '2026-05-05 01:27:46', 4, 1),
(10, 'Rollos de canela con glaseado ', 'Suaves y esponjosos rollos de canela cubiertos con un glaseado cremoso. Perfectos para el desayuno o la merienda, especialmente en días de frío.', '-500g de harina de trigo (0000)\r\n-100g de azúcar\r\n-10g de sal\r\n-10g de levadura seca (o 25g de levadura fresca)\r\n-200ml de leche tibia\r\n-2 huevos\r\n-80g de manteca derretida\r\n\r\nPara el relleno:\r\n-100g de manteca \r\n-150g de azúcar morena\r\n-2 cucharadas de canela en polvo\r\n\r\nPara el glaseado:\r\n-100g de queso crema \r\n-50g de manteca\r\n-150g de azúcar impalpable\r\n-1 cucharadita de esencia de vainilla', 'Preparación de la masa:\r\nEn un bol grande, mezclar la harina, el azúcar y la sal.\r\nDisolver la levadura en la leche tibia (no caliente).\r\n\r\nAgregar la leche con levadura, los huevos y la manteca derretida a los ingredientes secos.\r\nAmasar durante 10 minutos hasta obtener una masa lisa y elástica.\r\nDejar levar en un lugar cálido por 1 hora, hasta que duplique su tamaño.\r\n\r\nArmado de los rollos:\r\nEstirar la masa con un palo de amasar formando un rectángulo de aproximadamente 40x30cm.\r\nUntar toda la superficie con la manteca pomada.\r\nMezclar el azúcar morena con la canela y espolvorear sobre la manteca.\r\n\r\nEnrollar la masa por el lado más largo, formando un cilindro compacto.\r\nCortar el rollo en rodajas de unos 3-4cm de grosor (aproximadamente 12 rollos).\r\nColocar los rollos en una fuente para horno enmantecada, dejando espacio entre ellos.\r\nDejar levar nuevamente por 30 minutos.\r\n\r\nHorneado:\r\nPrecalentar el horno a 180°C.\r\nHornear los rollos durante 20-25 minutos, hasta que estén dorados.\r\n\r\nGlaseado:\r\nBatir el queso crema con la manteca hasta obtener una crema.\r\nAgregar el azúcar impalpable de a poco, batiendo constantemente.\r\nIncorporar la esencia de vainilla y mezclar.\r\nUna vez que los rollos estén tibios, bañarlos con el glaseado.', 90, 'Media', 'img/1779305166_2205.jpg', 1, 5, '2026-05-20 19:26:06', 5, 1),
(11, 'Galletas con chips de chocolate ', 'Galletas clásicas, crujientes por fuera, suaves por dentro y llenas de chips de chocolate. Perfectas para acompañar el té o el café.', '-200g de manteca (temperatura ambiente)\r\n-150g de azúcar común\r\n-150g de azúcar moreno\r\n-2 huevos grandes\r\n-1 cucharadita de esencia de vainilla\r\n-300g de harina de trigo (0000)\r\n-1 cucharadita de bicarbonato de sodio\r\n-1/2 cucharadita de sal\r\n-300g de chips de chocolate (o chocolate picado en trozos)', '1.Precalentar el horno a 180°C.\r\n2.En un bol grande, batir la manteca (blanda) con los dos tipos de azúcar hasta obtener una mezcla cremosa.\r\n3.Agregar los huevos de a uno, batiendo bien después de cada agregado. Incorporar la esencia de vainilla.\r\n4.En otro bol, mezclar la harina, el bicarbonato y la sal.\r\n5.Incorporar los ingredientes secos a la mezcla de manteca, mezclando hasta integrar. No amasar de más.\r\n6.Agregar los chips de chocolate y mezclar suavemente.\r\n7.Tapar la masa y refrigerar por 30 minutos (esto evita que se esparzan mucho en el horno).\r\n8.Formar bolitas del tamaño de una nuez (unos 30g cada una) y colocarlas en una placa para horno cubierta con papel manteca, separadas entre sí (dejan espacio para que se expandan).\r\n9.Hornear durante 10-12 minutos, hasta que los bordes estén dorados pero el centro aún se vea suave.\r\n10.Dejar enfriar 5 minutos en la placa antes de pasar a una rejilla. Las galletas se endurecen al enfriarse.', 30, 'Fácil', 'img/1779306461_7440.jpg', 1, 4, '2026-05-20 19:47:41', 4, 1),
(12, 'Alfajores de maicena', 'Suaves y esponjosos, rellenos con dulce de leche y bañados con coco rallado. Perfectos para la merienda', 'Para las tapitas:\r\n200g de manteca (temperatura ambiente)\r\n100g de azúcar\r\n3 yemas de huevo\r\n1 cucharadita de esencia de vainilla\r\n250g de harina de trigo (0000)\r\n200g de maicena (almidón de maíz)\r\n1 cucharadita de polvo de hornear.\r\n\r\nPara el armado:\r\n300g de dulce de leche repostero\r\n100g de coco rallado', '1.En un bol grande, batir la manteca con el azúcar hasta obtener una mezcla cremosa y blanca.\r\n\r\n2.Agregar las yemas de a una, batiendo bien después de cada agregado. Incorporar la esencia de vainilla.\r\n\r\n3.En otro bol, mezclar la harina, la maicena y el polvo de hornear. Tamizar (para que no queden grumos).\r\n\r\n4.Incorporar los secos a la mezcla de manteca en dos o tres veces, mezclando con espátula o con las manos hasta formar una masa homogénea. No amasar demasiado (la masa no debe calentarse).\r\n\r\n5.Envolver la masa en papel film y refrigerar por 30 minutos.\r\n\r\n6.Precalentar el horno a 180°C.\r\n\r\n7.Estirar la masa con un palo de amasar hasta que tenga unos 5mm de espesor. Espolvorear un poco de maicena si se pega.\r\n\r\n8.Cortar círculos con un cortante de unos 5cm de diámetro.\r\n\r\n9.Colocar las tapitas en una placa para horno cubierta con papel manteca, separadas entre sí.\r\n\r\n10.Hornear durante 10-12 minutos. No deben dorarse (apenas se cocinan, se mantienen blancas).\r\n\r\n11.Dejar enfriar completamente sobre una rejilla.\r\n\r\n12.Una vez frías, unir dos tapitas con dulce de leche repostero (poner generoso en el medio).\r\n\r\n13.Pasar los bordes por coco rallado.\r\n\r\n14.Dejar reposar los alfajores tapados por unas horas (ideal de un día para otro) para que se ablanden.', 75, 'Media', 'img/1779472388_9611.jpg', 1, 5, '2026-05-22 17:53:08', 0, 0),
(13, 'Tiramisu ', 'Postre italiano clásico, cremoso y suave, con capas de vainillas mojadas en cafe y crema de mascarpone. No lleva horno y es perfecto para cualquier ocasión.', 'Para la crema:\r\n3 huevos grandes (separar claras y yemas)\r\n75g de azúcar\r\n250g de queso mascarpone (temperatura ambiente)\r\n\r\nPara el armado:\r\n200ml de café fuerte (ya frío)\r\n200g de vainillas (bizcochos de soletilla)\r\nCacao amargo en polvo (para espolvorear)\r\nOpcional: 1 cucharada de licor de café o amaretto\r\n\r\n', 'Preparación de la crema:\r\n1.Separar las claras de las yemas.\r\n\r\n2.En un bol, batir las yemas con el azúcar hasta que la mezcla esté blanca y cremosa (unos 5 minutos).\r\n\r\n3.Agregar el queso mascarpone a las yemas y mezclar suavemente con espátula hasta integrar.\r\n\r\n4.En otro bol (bien seco y limpio), batir las claras a punto nieve (firmes, que no se caigan al dar vuelta el bol).\r\n\r\n5.Incorporar las claras a la mezcla de mascarpone con movimientos envolventes (de abajo hacia arriba, no batir para que no se bajen).\r\n\r\nArmado:\r\n6.Preparar el café (cargado) y dejar enfriar completamente. Si querés, agregar el licor.\r\n\r\n7.Mojar las vainillas rápidamente en el café (1 o 2 segundos por lado) — no empaparlas demasiado o se desarman.\r\n\r\n8.Colocar una capa de vainillas en el fondo de una fuente (aproximadamente 20x20cm).\r\n\r\n9.Cubrir con la mitad de la crema de mascarpone y alisar con espátula.\r\nRepetir otra capa: vainillas mojadas y el resto de la crema.\r\n\r\n10.Espolvorear cacao amargo en polvo por encima usando un colador fino.\r\nRefrigerar por 4 horas (idealmente de un día para otro) antes de servir.', 40, 'Media', 'img/1779473062_7387.jpg', 2, 5, '2026-05-22 18:04:22', 0, 0),
(14, 'Churros', 'Crujientes por fuera, suaves por dentro. Perfectos para una tarde de frío, bañados en chocolate caliente o espolvoreados con azúcar y canela.', '1 taza de agua (250ml)\r\n2 cucharadas de azúcar (30g)\r\n1/2 cucharadita de sal\r\n2 cucharadas de aceite (30ml)\r\n1 taza de harina de trigo (0000 - 125g)\r\n2 huevos\r\nAceite para freír (cantidad suficiente, puede ser de girasol o maíz)', 'Preparación de la masa:\r\n1.En una olla mediana, poner el agua, el azúcar, la sal y el aceite. Llevar a fuego medio hasta que hierva.\r\n\r\n2.Cuando hierva, retirar del fuego y agregar la harina de una sola vez. Mezclar vigorosamente con cuchara de madera hasta que se forme una masa que se desprenda de los bordes.\r\n\r\n3.Volver a poner la olla a fuego bajo y cocinar la masa por 1-2 minutos, revolviendo constantemente, hasta que se forme una película en el fondo. Retirar.\r\n\r\n4.Dejar enfriar la masa durante 5 minutos (tibia, no caliente para que no se cocinen los huevos).\r\n\r\n5.Agregar los huevos de a uno, mezclando bien después de cada agregado. La masa debe quedar lisa, brillante y con una textura que se caiga de la cuchara.\r\n\r\nArmado y fritura:\r\n1.Colocar la masa en una manga pastelera con una punta estrellada (forma de churro).\r\n\r\n2.Calentar abundante aceite en una olla profunda (aproximadamente 180°C). Para saber si está listo, tirar un pedacito de masa: si sube rapidito, está bien.\r\n\r\n3.Con la manga, presionar la masa directamente sobre el aceite, cortando con tijera los churros del tamaño deseado (unos 10-12cm).\r\n\r\n4.Freír los churros hasta que estén dorados (unos 2-3 minutos), dándolos vuelta para que se doren parejo.\r\n\r\n5.Retirar con espumadera y colocar sobre papel absorbente para quitar el exceso de aceite.\r\n\r\n6.Mezclar azúcar con canela en un plato y rebozar los churros aún calientes.', 35, 'Difícil', 'img/1779475684_6773.jpg', 1, 5, '2026-05-22 18:35:27', 3, 1),
(16, 'Pastelitos de chocolate blanco y queso con fresas', 'Una base crujiente de galletas, una crema suave de chocolate blanco y queso, coronada con fresas frescas bañadas en chocolate negro. Un postre elegante y delicioso', '-1 tableta de Chocolate Blanco \r\nPara la base: \r\n-70 g de galletas tipo María\r\n-30 g de mantequilla\r\n-200 g de queso blanco cremoso para untar\r\n-200 ml de nata para montar\r\n-2 hojas de gelatina\r\n-25 g de azúcar\r\nPara la decoración: \r\n-6 frutillas\r\n-75 g de Chocolate Negro ', 'Cortar 6 tiras acetato de 22cm de largo. Poner alrededor de un molde de emplatar de 6 cm de diámetro, enganchar con cinta adhesiva y retirar del molde.Disponer los moldes en una bandeja con papel de horno debajo.Fundir la mantequilla al microondas y mezclar bien con las galletas trituradas. Repartir en el moldes y aplanar poniendo un vaso pequeño dentro del molde. Poner en el congelador.Poner las hojas de gelatina a hidratar en agua fría unos 10 minutos.Batir el queso con el azúcar. Poner a calentar en un cazo a fuego suave, la nata con el chocolate troceado, e ir removiendo hasta que se funda.Retirar el cazo del fuego, y añadir la gelatina escurrida. Remover hasta que se disuelva y dejar templar un pocoVerter poco a poco en la mezcla de queso y azúcar e ir batiendo hasta que quede todo bien incorporado. Dejar enfriar un poco, removiendo de vez en cuando.Sacar los moldes del congelador y repartir la crema. Dejar en el frigorífico un mínimo de 5 horas.Retirar el acetato de los pastelitos.Lavar y secar los fresones.Fundir el chocolate al baño maría o microondas y dejar templar un poco.Sumergirlos en el chocolate y ponerlos encima de los pastelitos. Con el chocolate restante,  decorar haciendo unas  líneas finas con una cuchara o manga pastelera.Reservar unos 15\' en la nevera para que el chocolate negro se solidifique y servir.', 40, 'Fácil', 'img/1780593888_3047.jpg', 2, 4, '2026-06-04 17:24:48', 0, 0),
(17, 'Trufas de chocolate ', 'trufas de chocolate deliciosas que se preparan en menos de 30 minutos ', '-200 g (1 tableta) de Chocolate Negro \r\n-125 ml de nata para montar\r\n-NESQUIK Intenso 100% cacao puro natural para rebozar\r\n-Fideos de chocolate para rebozar', '1.Calentar la nata en un cazo y fuera del fuego añadir el chocolate troceado. \r\n\r\n2.Dejar reposar unos minutos y mezclar bien.\r\n\r\n3.Poner papel film tocando la crema y refrigerar un mínimo de 2 horas.Si se ha endurecido demasiado dejar temperar un rato fuera del frigorífico.\r\n\r\n4.Con una cuchara coger porciones de unos 20 g y formar las bolitas con las manos.\r\n\r\n5.Rebozar con el NESQUIK Intenso 100% o fideos de chocolate y así completar las trufas de chocolate de forma original.', 30, 'Fácil', 'img/1780594641_3046.jpg', 2, 4, '2026-06-04 17:37:21', 4, 1),
(32, 'Vasitos de gelatina arcoíris', 'Disfruta de esta gelatina de colores pastel que te encantará. Su sabor de coco con yogurt griego y miel es perfecto para cuidar tú alimentación día a día. Gracias a su presentación individual es ideal para compartir con amigos y familia.', '-1/2 taza de yogur griego, para la gelatina azul\r\n-1/4 taza de miel, para la gelatina azul\r\n-cantidad suficiente de colorante artificial azul, turquesa, para la gelatina azul\r\n-2 tazas de leche, caliente, para la gelatina rosa\r\n-2 tazas de leche, caliente, para la gelatina amarilla\r\n-1/2 taza de yogur griego, para la gelatina verde\r\n-1/4 taza de miel, para la gelatina verde\r\n-1/2 taza de yogur griego, para la gelatina morada\r\n-1/4 taza de miel, para la gelatina morada\r\n-cantidad suficiente de crema batida, para decorar\r\n-1/2 taza de leche de coco, para la gelatina azul\r\n-3/4 taza de leche, caliente, para la gelatina azul\r\n-21 gramos de grenetina, hidratada, 3 sobres de 7g c/u\r\n-1/4 taza de gelatina de frutos rojos, para la gelatina rosa\r\n-1/4 taza de gelatina de vainilla, para la gelatina amarilla\r\n-1/2 taza de leche de coco, para la gelatina verde\r\n-3/4 taza de leche, caliente, para la gelatina verde\r\n-21 gramos de grenetina, hidratada, 3 sobres de 7g c/u\r\n-1/2 taza de leche de coco, para la gelatina morada\r\n-21 gramos de grenetina, hidratada, 3 sobres de 7g c/u\r\n-cantidad suficiente de perlas de azúcar comestibles, para decorar, de colores (rosa, blanco, plata y morado)', 'Para la gelatina azul turquesa, mezcla el yogurt natural junto con la leche de coco, la miel y la leche caliente. Agrega el colorante azul turquesa para obtener un tono color pastel, vierte la grenetina hidratada e incorpora. Reserva.\r\nPara la gelatina roja, diluye la gelatina de frutos rojos sobre la leche caliente para obtener un tono color pastel. Reserva. Repite el proceso para la gelatina amarilla.\r\nPara la gelatina verde y morada, repite el primer paso y reserva.\r\nVierte un poco de la mezcla color turquesa sobre un vasito para gelatina para formar una capa, refrigera por 15 minutos o hasta que cuaje.\r\nUna vez que la capa este firme, vierte la gelatina rosa para formar una segunda capa, refrigera por 15 min o hasta que cuaje y repite el proceso con el resto de los colores: amarillo, verde y morado.\r\nSirve los vasitos decorados con crema batida y perlas de azúcar de colores.', 40, 'Media', 'img/1780688667_5557.jpg', 2, 4, '2026-06-05 19:44:27', 0, 0),
(33, 'Postre tres leches con pan tostado', 'sencillo y rico ', '1 Paquete de Pan Tostado \r\n1 Taza de crema chantilly\r\n1 Taza de fresas\r\n1 Taza de leche\r\n1 Taza de crema de leche\r\n1 Taza de leche condensada\r\n2 Cucharada de vainilla', 'Paso 1: Licúa la leche, la crema de leche, la leche condensada y la vainilla durante 30 segundos hasta tener una mezcla un poco aireada.\r\n\r\nPaso 2: En una refractaria crea capas con Pan Tostado, agrega poco a poco con una cuchara parte de la mezcla de leche, cuándo esté toda húmeda vierte un poco de crema chantilly, otra capa de Pan Tostado con el remojo de leche y continúa hasta terminar con una capa de chantilly. \r\n\r\nPaso 3: Refrigera por al menos 3 horas antes de servir el postre. Pasado este tiempo, corta en porciones individuales y sirve con un poco de fresas por encima. ', 20, 'Fácil', 'img/1780689004_7314.png', 2, 4, '2026-06-05 19:50:04', 0, 0);

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
(3, 4, 8, '2026-05-10 22:19:09'),
(4, 4, 6, '2026-05-12 16:42:21'),
(5, 4, 8, '2026-05-22 16:53:09'),
(6, 5, 4, '2026-05-22 17:49:35'),
(7, 4, 12, '2026-05-26 19:02:36'),
(8, 4, 11, '2026-05-30 03:54:13'),
(9, 9, 8, '2026-06-02 21:00:21'),
(10, 9, 13, '2026-06-02 21:00:28'),
(11, 9, 8, '2026-06-02 21:00:33'),
(12, 9, 11, '2026-06-02 21:00:41'),
(13, 4, 13, '2026-06-03 16:45:31'),
(14, 4, 13, '2026-06-04 17:11:24'),
(15, 4, 12, '2026-06-05 17:57:37'),
(16, 4, 13, '2026-06-05 18:01:20'),
(17, 4, 17, '2026-06-05 18:06:21'),
(18, 4, 6, '2026-06-05 18:06:52'),
(19, 4, 3, '2026-06-05 18:06:56'),
(20, 4, 12, '2026-06-05 18:07:03'),
(21, 4, 7, '2026-06-05 18:12:10'),
(22, 4, 10, '2026-06-05 18:12:17'),
(23, 4, 8, '2026-06-05 18:12:24'),
(24, 4, 7, '2026-06-05 18:12:48'),
(25, 4, 8, '2026-06-05 18:13:00'),
(26, 5, 4, '2026-06-05 18:41:51'),
(27, 5, 5, '2026-06-05 18:42:06'),
(28, 5, 14, '2026-06-05 19:28:22'),
(29, 4, 4, '2026-06-05 19:28:50');

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
(4, 'admin', 'aadmin@gmail.com', '$2y$10$HYUo5Gp.vnzg3ZwDA/D5TuQm/j.XjO/TEbZJyWoyCZ57hRgSRfixi', '2026-05-05 00:33:33', 'admin'),
(5, 'usuario', 'usuario@gmail.com', '$2y$10$DvtJ4WxLOp8698uiUYItiOvucgbkR7FnL/K79h.XchlvaHYAzXySa', '2026-05-10 21:06:28', 'usuario'),
(7, 'diego', 'diego@pepe', '$2y$10$WzsClxAmQkTpPGNIgdvsRurV5CaN4TuNMxAqJvERcplOm0fneGHzW', '2026-05-26 20:36:37', 'usuario'),
(9, 'dac', 'dac@pepito.com', '$2y$10$hB5.P9ntSzjFhxjIuwLbouNscSW739Xq6eNJuiRfwZdH/SKXXCbAq', '2026-06-02 20:53:26', 'usuario');

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
-- Volcado de datos para la tabla `valoraciones`
--

INSERT INTO `valoraciones` (`usuario_id`, `receta_id`, `puntuacion`, `fecha`) VALUES
(4, 5, 1, '2026-05-20 19:14:06'),
(4, 6, 5, '2026-05-20 19:09:34'),
(4, 8, 4, '2026-05-20 19:13:02'),
(4, 11, 4, '2026-06-05 17:19:04'),
(4, 14, 3, '2026-06-03 17:45:02'),
(4, 17, 4, '2026-06-04 19:07:48'),
(5, 3, 4, '2026-05-20 19:16:17'),
(5, 5, 4, '2026-05-20 19:14:34'),
(5, 10, 5, '2026-05-20 19:26:57');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `ruleta_historial`
--
ALTER TABLE `ruleta_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
