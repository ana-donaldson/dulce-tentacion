-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-11-2026 a las 19:12:06
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
(5, 'muy feop', '2026-06-03 17:45:02', 4, 14),
(6, 'bastante ricos', '2026-06-04 19:06:05', 4, 17),
(10, 'rica', '2026-06-15 22:24:10', 4, 5),
(11, 'le faltaba azucar', '2026-06-29 19:44:57', 4, 11),
(12, 'no lo recomiendo', '2026-06-29 21:11:56', 4, 5),
(13, 'muy bueno', '2026-07-08 19:14:09', 14, 4);

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
(3, 'Flan casero ', 'flan casero, postre cremoso, perfecto para cualquier ocasion.', 'para el caramelo:\r\n-1 taza de azúcar\r\n-1/4 taza de agua. \r\n\r\nPara el flan:\r\n-1 lata de leche condensada (395g)\r\n-1 lata de leche evaporada (360ml) \r\n-3 huevos grandes\r\n-1 cucharadita de esencia de vainilla', ' Preparar el caramelo: En una olla pequeña, mezclar el azúcar y el agua. Calentar a fuego medio sin revolver hasta que tome color ámbar.\r\n\r\nVerter el caramelo en un molde para flan, inclinándolo para que cubra el fondo y las paredes.\r\n\r\nPrecalentar el horno a 180°C.\r\n\r\nEn la licuadora, mezclar la leche condensada, leche evaporada, huevos y vainilla. Licuar por 2 minutos.\r\n\r\nVerter la mezcla en el molde acaramelado.\r\n\r\nTapar el molde con papel aluminio.\r\n\r\nColocar el molde dentro de una bandeja más grande con agua (baño María).\r\n\r\nHornear por 50-60 minutos o hasta que al insertar un cuchillo salga limpio.\r\n\r\nDejar enfriar, refrigerar por 4 horas, desmoldar y servir.', 60, 'Media', 'img/flan.jpg', 1, 3, '2026-05-04 17:40:50', 4, 1),
(4, 'Brownie de chocolate ', 'El clásico brownie súper chocolatoso, crocante por fuera y suave por dentro.', '-200g de chocolate negro (70% cacao)\r\n-150g de manteca\r\n-3 huevos, 200g de azúcar\r\n-100g de harina\r\n-1 cucharadita de esencia de vainilla\r\n-50g de nueces (opcional)', ' Precalentar el horno a 180°C.\r\n\r\n Derretir el chocolate con la manteca a baño María o en microondas.\r\n\r\n En un bol, batir los huevos con el azúcar hasta que estén espumosos.\r\n\r\nAgregar la mezcla de chocolate derretido y la vainilla. Mezclar.\r\n\r\nIncorporar la harina tamizada y las nueces si usas.\r\n\r\nVerter en un molde enmantecado (20x20cm).\r\n\r\nHornear por 25-30 minutos.\r\n\r\nDejar enfriar antes de cortar. El centro debe quedar húmedo.', 35, 'Fácil', 'img/1777942112_2442.jpg', 1, 4, '2026-05-05 00:48:32', 5, 1),
(5, 'Helado de vainilla casero ', 'Helado cremoso y fácil de hacer sin necesidad de máquina heladora.', '-500ml de nata para montar\r\n-1 lata de leche condensada\r\n-1 cucharada de esencia de vainilla', ' Batir la nata hasta que esté firme.\r\n\r\n Agregar la leche condensada y la vainilla.\r\n\r\n Mezclar suavemente.\r\n\r\n Congelar por 6 horas.', 15, 'Fácil', 'img/1777943296_4941.jpg', 2, 4, '2026-05-05 01:08:16', 6, 2),
(6, 'Cheescake de frutilla', 'Postre fresco y cremoso. Ideal para el verano porque no necesita horno.', 'Base:\r\n-200g de galletitas\r\n- 100g de manteca derretida\r\n\r\nRelleno:\r\n-500g de queso crema\r\n-1 taza de azúcar\r\n- 200ml de crema de leche,\r\n-1 sobre de gelatina sin sabor\r\n-1 cucharadita de esencia de vainilla\r\n\r\nCobertura:\r\n-300g de frutillas\r\n-4 cucharadas de azúcar', ' Triturar las galletitas y mezclar con manteca derretida.\r\n\r\n Presionar en un molde desmontable. Llevar a la heladera.\r\n\r\n Batir queso crema con azúcar.\r\n\r\n Hidratar la gelatina en agua, derretir y agregar.\r\n\r\n Agregar la crema de leche y mezclar.\r\n\r\n Verter sobre la base y refrigerar 4 horas.\r\n\r\n Para la cobertura: licuar frutillas con azúcar.\r\n\r\n Verter sobre el cheesecake y enfriar 2 horas más.', 40, 'Media', 'img/1777943664_7673.jpg', 2, 4, '2026-05-05 01:14:24', 5, 1),
(7, 'Tarta de manzana ', 'Tarta casera con manzanas caramelizadas. Perfecta para la merienda con mate o te.', 'Masa:\r\n-200g de harina\r\n- 100g de manteca\r\n- 50g de azúcar\r\n-1 huevo\r\n-1 cucharadita de polvo de hornear\r\n\r\nRelleno:\r\n4 manzanas (rojas o verdes),\r\n-50g de azúcar\r\n-1 cucharadita de canela\r\n-Azúcar impalpable para decorar', ' Mezclar harina, manteca, azúcar, huevo y polvo de hornear.\r\n\r\n Formar un bollo y refrigerar 30 minutos.\r\n\r\n Pelar y cortar manzanas en láminas finas.\r\n\r\n Estirar la masa y colocarla en un molde.\r\n\r\n Distribuir las manzanas en círculos superpuestos.\r\n\r\n Espolvorear azúcar y canela.\r\n\r\n Hornear 35-40 minutos a 180°C.\r\n\r\n Decorar con azúcar impalpable antes de servir.', 50, 'Fácil', 'img/1777944103_9583.jpg', 1, 4, '2026-05-05 01:21:43', 0, 0),
(8, 'Pavlova (merengue con frutas)', 'Postre elegante de merengue crocante por fuera y suave por dentro, coronado con crema y frutas.', 'Merengue:\r\n-4 claras de huevo\r\n-200g de azúcar\r\n-1 cucharadita de maicena\r\n-1 cucharadita de vinagre blanco\r\n\r\nRelleno:\r\n200ml de crema de leche\r\n-2 cucharadas de azúcar impalpable\r\n-300g de frutillas\r\n-Hojas de menta', ' Batir las claras a punto nieve.\r\n Agregar el azúcar de a poco, batiendo hasta que quede brillante.\r\n\r\n Incorporar la maicena y el vinagre.\r\n\r\n Formar un círculo de merengue en placa con papel manteca.\r\n\r\n Hornear 1 hora a 120°C. Apagar el horno y dejar enfriar adentro.\r\n\r\n Batir la crema con el azúcar.\r\n\r\n Cubrir el merengue con la crema.\r\n\r\nDecorar con frutillas y menta.', 80, 'Difícil', 'img/1777944466_9022.jpg', 2, 4, '2026-05-05 01:27:46', 4, 1),
(10, 'Rollos de canela con glaseado ', 'Suaves y esponjosos rollos de canela cubiertos con un glaseado cremoso. Perfectos para el desayuno o la merienda, especialmente en días de frío.', '-500g de harina de trigo (0000)\r\n-100g de azúcar\r\n-10g de sal\r\n-10g de levadura seca (o 25g de levadura fresca)\r\n-200ml de leche tibia\r\n-2 huevos\r\n-80g de manteca derretida\r\n\r\nPara el relleno:\r\n-100g de manteca \r\n-150g de azúcar morena\r\n-2 cucharadas de canela en polvo\r\n\r\nPara el glaseado:\r\n-100g de queso crema \r\n-50g de manteca\r\n-150g de azúcar impalpable\r\n-1 cucharadita de esencia de vainilla', 'Preparación de la masa:\r\nEn un bol grande, mezclar la harina, el azúcar y la sal.\r\nDisolver la levadura en la leche tibia (no caliente).\r\n\r\nAgregar la leche con levadura, los huevos y la manteca derretida a los ingredientes secos.\r\nAmasar durante 10 minutos hasta obtener una masa lisa y elástica.\r\nDejar levar en un lugar cálido por 1 hora, hasta que duplique su tamaño.\r\n\r\nArmado de los rollos:\r\nEstirar la masa con un palo de amasar formando un rectángulo de aproximadamente 40x30cm.\r\nUntar toda la superficie con la manteca pomada.\r\nMezclar el azúcar morena con la canela y espolvorear sobre la manteca.\r\n\r\nEnrollar la masa por el lado más largo, formando un cilindro compacto.\r\nCortar el rollo en rodajas de unos 3-4cm de grosor (aproximadamente 12 rollos).\r\nColocar los rollos en una fuente para horno enmantecada, dejando espacio entre ellos.\r\nDejar levar nuevamente por 30 minutos.\r\n\r\nHorneado:\r\nPrecalentar el horno a 180°C.\r\nHornear los rollos durante 20-25 minutos, hasta que estén dorados.\r\n\r\nGlaseado:\r\nBatir el queso crema con la manteca hasta obtener una crema.\r\nAgregar el azúcar impalpable de a poco, batiendo constantemente.\r\nIncorporar la esencia de vainilla y mezclar.\r\nUna vez que los rollos estén tibios, bañarlos con el glaseado.', 90, 'Media', 'img/1782761312_9073.jpg', 1, 5, '2026-05-20 19:26:06', 5, 1),
(11, 'Galletas con chips de chocolate ', 'Galletas clásicas, crujientes por fuera, suaves por dentro y llenas de chips de chocolate. Perfectas para acompañar el té o el café.', '-200g de manteca (temperatura ambiente)\r\n-150g de azúcar común\r\n-150g de azúcar moreno\r\n-2 huevos grandes\r\n-1 cucharadita de esencia de vainilla\r\n-300g de harina de trigo (0000)\r\n-1 cucharadita de bicarbonato de sodio\r\n-1/2 cucharadita de sal\r\n-300g de chips de chocolate (o chocolate picado en trozos)', 'Precalentar el horno a 180°C.\r\nEn un bol grande, batir la manteca (blanda) con los dos tipos de azúcar hasta obtener una mezcla cremosa.\r\n\r\nAgregar los huevos de a uno, batiendo bien después de cada agregado. Incorporar la esencia de vainilla.\r\n\r\nEn otro bol, mezclar la harina, el bicarbonato y la sal.\r\n\r\nIncorporar los ingredientes secos a la mezcla de manteca, mezclando hasta integrar. No amasar de más.\r\n\r\nAgregar los chips de chocolate y mezclar suavemente.\r\n\r\nTapar la masa y refrigerar por 30 minutos (esto evita que se esparzan mucho en el horno).\r\n\r\nFormar bolitas del tamaño de una nuez (unos 30g cada una) y colocarlas en una placa para horno cubierta con papel manteca, separadas entre sí (dejan espacio para que se expandan).\r\n\r\nHornear durante 10-12 minutos, hasta que los bordes estén dorados pero el centro aún se vea suave.\r\n\r\nDejar enfriar 5 minutos en la placa antes de pasar a una rejilla. Las galletas se endurecen al enfriarse.', 30, 'Fácil', 'img/1782761277_6190.jpg', 1, 4, '2026-05-20 19:47:41', 5, 1),
(12, 'Alfajores de maicena', 'Suaves y esponjosos, rellenos con dulce de leche y bañados con coco rallado. Perfectos para la merienda', 'Para las tapitas:\r\n-200g de manteca (temperatura ambiente)\r\n-100g de azúcar\r\n-3 yemas de huevo\r\n-1 cucharadita de esencia de vainilla\r\n-250g de harina de trigo (0000)\r\n-200g de maicena (almidón de maíz)\r\n-1 cucharadita de polvo de hornear.\r\n\r\nPara el armado:\r\n-300g de dulce de leche repostero\r\n-100g de coco rallado', 'En un bol grande, batir la manteca con el azúcar hasta obtener una mezcla cremosa y blanca.\r\n\r\nAgregar las yemas de a una, batiendo bien después de cada agregado. Incorporar la esencia de vainilla.\r\n\r\nEn otro bol, mezclar la harina, la maicena y el polvo de hornear. Tamizar (para que no queden grumos).\r\n\r\nIncorporar los secos a la mezcla de manteca en dos o tres veces, mezclando con espátula o con las manos hasta formar una masa homogénea. No amasar demasiado (la masa no debe calentarse).\r\n\r\nEnvolver la masa en papel film y refrigerar por 30 minutos.\r\n\r\nPrecalentar el horno a 180°C.\r\n\r\nEstirar la masa con un palo de amasar hasta que tenga unos 5mm de espesor. Espolvorear un poco de maicena si se pega.\r\n\r\nCortar círculos con un cortante de unos 5cm de diámetro.\r\n\r\nColocar las tapitas en una placa para horno cubierta con papel manteca, separadas entre sí.\r\n\r\nHornear durante 10-12 minutos. No deben dorarse (apenas se cocinan, se mantienen blancas).\r\n\r\nDejar enfriar completamente sobre una rejilla.\r\n\r\nUna vez frías, unir dos tapitas con dulce de leche repostero (poner generoso en el medio).\r\n\r\nPasar los bordes por coco rallado.\r\n\r\nDejar reposar los alfajores tapados por unas horas (ideal de un día para otro) para que se ablanden.', 75, 'Media', 'img/1782761258_9683.jpg', 1, 5, '2026-05-22 17:53:08', 0, 0),
(13, 'Tiramisu ', 'Postre italiano clásico, cremoso y suave, con capas de vainillas mojadas en cafe y crema de mascarpone. No lleva horno y es perfecto para cualquier ocasión.', 'Para la crema:\r\n-3 huevos grandes (separar claras y yemas)\r\n-75g de azúcar\r\n-250g de queso mascarpone (temperatura ambiente)\r\n\r\nPara el armado:\r\n-200ml de café fuerte (ya frío)\r\n-200g de vainillas (bizcochos de soletilla)\r\n-Cacao amargo en polvo (para espolvorear)\r\n-Opcional: 1 cucharada de licor de café o amaretto\r\n\r\n', 'Preparación de la crema:separar las claras de las yemas. En un bol, batir las yemas con el azúcar hasta que la mezcla esté blanca y cremosa (unos 5 minutos). Agregar el queso mascarpone a las yemas y mezclar suavemente con espátula hasta integrar.\r\nEn otro bol (bien seco y limpio), batir las claras a punto nieve (firmes, que no se caigan al dar vuelta el bol). Incorporar las claras a la mezcla de mascarpone con movimientos envolventes (de abajo hacia arriba, no batir para que no se bajen).\r\n\r\n\r\nPreparar el café (cargado) y dejar enfriar completamente. Si querés, agregar el licor.\r\n\r\nMojar las vainillas rápidamente en el café (1 o 2 segundos por lado) — no empaparlas demasiado o se desarman.\r\n\r\nColocar una capa de vainillas en el fondo de una fuente (aproximadamente 20x20cm).\r\n\r\nCubrir con la mitad de la crema de mascarpone y alisar con espátula.\r\nRepetir otra capa: vainillas mojadas y el resto de la crema.\r\n\r\nEspolvorear cacao amargo en polvo por encima usando un colador fino.\r\nRefrigerar por 4 horas (idealmente de un día para otro) antes de servir.', 40, 'Media', 'img/1782761331_2272.jpg', 2, 5, '2026-05-22 18:04:22', 0, 0),
(14, 'Churros', 'Crujientes por fuera, suaves por dentro. Perfectos para una tarde de frío, bañados en chocolate caliente o espolvoreados con azúcar y canela.', '-1 taza de agua (250ml)\r\n-2 cucharadas de azúcar (30g)\r\n-1/2 cucharadita de sal\r\n-2 cucharadas de aceite (30ml)\r\n-1 taza de harina de trigo (0000 - 125g)\r\n-2 huevos\r\n-Aceite para freír (cantidad suficiente, puede ser de girasol o maíz)', 'Preparación de la masa: en una olla mediana, poner el agua, el azúcar, la sal y el aceite. Llevar a fuego medio hasta que hierva.\r\n\r\nCuando hierva, retirar del fuego y agregar la harina de una sola vez. Mezclar vigorosamente con cuchara de madera hasta que se forme una masa que se desprenda de los bordes.\r\n\r\nVolver a poner la olla a fuego bajo y cocinar la masa por 1-2 minutos, revolviendo constantemente, hasta que se forme una película en el fondo. Retirar.\r\n\r\nDejar enfriar la masa durante 5 minutos (tibia, no caliente para que no se cocinen los huevos).\r\n\r\nAgregar los huevos de a uno, mezclando bien después de cada agregado. La masa debe quedar lisa, brillante y con una textura que se caiga de la cuchara.\r\n\r\nArmado y fritura: colocar la masa en una manga pastelera con una punta estrellada (forma de churro).\r\n\r\nCalentar abundante aceite en una olla profunda (aproximadamente 180°C). Para saber si está listo, tirar un pedacito de masa: si sube rapidito, está bien.\r\n\r\nCon la manga, presionar la masa directamente sobre el aceite, cortando con tijera los churros del tamaño deseado (unos 10-12cm).\r\n\r\nFreír los churros hasta que estén dorados (unos 2-3 minutos), dándolos vuelta para que se doren parejo.\r\n\r\nRetirar con espumadera y colocar sobre papel absorbente para quitar el exceso de aceite.\r\n\r\nMezclar azúcar con canela en un plato y rebozar los churros aún calientes.', 35, 'Difícil', 'img/1782761231_4003.jpg', 1, 5, '2026-05-22 18:35:27', 3, 1),
(16, 'Pastelitos de chocolate blanco y queso con fresas', 'Una base crujiente de galletas, una crema suave de chocolate blanco y queso, coronada con fresas frescas bañadas en chocolate negro. Un postre elegante y delicioso', '-1 tableta de Chocolate Blanco \r\nPara la base: \r\n-70 g de galletas tipo María\r\n-30 g de mantequilla\r\n-200 g de queso blanco cremoso para untar\r\n-200 ml de nata para montar\r\n-2 hojas de gelatina\r\n-25 g de azúcar\r\nPara la decoración: \r\n-6 frutillas\r\n-75 g de Chocolate Negro ', 'Cortar 6 tiras acetato de 22cm de largo. Poner alrededor de un molde de emplatar de 6 cm de diámetro, enganchar con cinta adhesiva y retirar del molde.Disponer los moldes en una bandeja con papel de horno debajo.Fundir la mantequilla al microondas y mezclar bien con las galletas trituradas. \r\n\r\nRepartir en el moldes y aplanar poniendo un vaso pequeño dentro del molde. Poner en el congelador. \r\n\r\nPoner las hojas de gelatina a hidratar en agua fría unos 10 minutos.Batir el queso con el azúcar. Poner a calentar en un cazo a fuego suave, la nata con el chocolate troceado, e ir removiendo hasta que se funda.\r\n\r\nRetirar el cazo del fuego, y añadir la gelatina escurrida. Remover hasta que se disuelva y dejar templar un poco. \r\nVerter poco a poco en la mezcla de queso y azúcar e ir batiendo hasta que quede todo bien incorporado. Dejar enfriar un poco, removiendo de vez en cuando.\r\n\r\nSacar los moldes del congelador y repartir la crema. Dejar en el frigorífico un mínimo de 5 horas.\r\n\r\nRetirar el acetato de los pastelitos. Lavar y secar los fresones.Fundir el chocolate al baño maría o microondas y dejar templar un poco.Sumergirlos en el chocolate y ponerlos encima de los pastelitos. Con el chocolate restante,  decorar haciendo unas  líneas finas con una cuchara o manga pastelera.Reservar unos 15\' en la nevera para que el chocolate negro se solidifique y servir.', 40, 'Fácil', 'img/1782761351_9067.jpg', 2, 4, '2026-06-04 17:24:48', 0, 0),
(17, 'Trufas de chocolate ', 'trufas de chocolate deliciosas que se preparan en menos de 30 minutos ', '-200 g (1 tableta) de Chocolate Negro \r\n-125 ml de nata para montar\r\n-NESQUIK Intenso 100% cacao puro natural para rebozar\r\n-Fideos de chocolate para rebozar', 'Calentar la nata en un cazo y fuera del fuego añadir el chocolate troceado. \r\n\r\nDejar reposar unos minutos y mezclar bien.\r\n\r\nPoner papel film tocando la crema y refrigerar un mínimo de 2 horas.Si se ha endurecido demasiado dejar temperar un rato fuera del frigorífico.\r\n\r\nCon una cuchara coger porciones de unos 20 g y formar las bolitas con las manos.\r\n\r\nRebozar con el NESQUIK Intenso 100% o fideos de chocolate y así completar las trufas de chocolate de forma original.', 30, 'Fácil', 'img/1782761196_8989.jpg', 2, 4, '2026-06-04 17:37:21', 4, 1),
(32, 'Vasitos de gelatina arcoíris', 'Disfruta de esta gelatina de colores pastel que te encantará. Su sabor de coco con yogurt griego y miel es perfecto para cuidar tú alimentación día a día. Gracias a su presentación individual es ideal para compartir con amigos y familia.', '-1/2 taza de yogur griego, para la gelatina azul\r\n-1/4 taza de miel, para la gelatina azul\r\n-cantidad suficiente de colorante artificial azul, turquesa, para la gelatina azul\r\n-2 tazas de leche, caliente, para la gelatina rosa\r\n-2 tazas de leche, caliente, para la gelatina amarilla\r\n-1/2 taza de yogur griego, para la gelatina verde\r\n-1/4 taza de miel, para la gelatina verde\r\n-1/2 taza de yogur griego, para la gelatina morada\r\n-1/4 taza de miel, para la gelatina morada\r\n-cantidad suficiente de crema batida, para decorar\r\n-1/2 taza de leche de coco, para la gelatina azul\r\n-3/4 taza de leche, caliente, para la gelatina azul\r\n-21 gramos de grenetina, hidratada, 3 sobres de 7g c/u\r\n-1/4 taza de gelatina de frutos rojos, para la gelatina rosa\r\n-1/4 taza de gelatina de vainilla, para la gelatina amarilla\r\n-1/2 taza de leche de coco, para la gelatina verde\r\n-3/4 taza de leche, caliente, para la gelatina verde\r\n-21 gramos de grenetina, hidratada, 3 sobres de 7g c/u\r\n-1/2 taza de leche de coco, para la gelatina morada\r\n-21 gramos de grenetina, hidratada, 3 sobres de 7g c/u\r\n-cantidad suficiente de perlas de azúcar comestibles, para decorar, de colores (rosa, blanco, plata y morado)', 'Para la gelatina azul turquesa, mezcla el yogurt natural junto con la leche de coco, la miel y la leche caliente. Agrega el colorante azul turquesa para obtener un tono color pastel, vierte la grenetina hidratada e incorpora. Reserva.\r\nPara la gelatina roja, diluye la gelatina de frutos rojos sobre la leche caliente para obtener un tono color pastel. Reserva. Repite el proceso para la gelatina amarilla.\r\nPara la gelatina verde y morada, repite el primer paso y reserva.\r\nVierte un poco de la mezcla color turquesa sobre un vasito para gelatina para formar una capa, refrigera por 15 minutos o hasta que cuaje.\r\nUna vez que la capa este firme, vierte la gelatina rosa para formar una segunda capa, refrigera por 15 min o hasta que cuaje y repite el proceso con el resto de los colores: amarillo, verde y morado.\r\nSirve los vasitos decorados con crema batida y perlas de azúcar de colores.', 40, 'Media', 'img/1782761175_9647.jpg', 2, 4, '2026-06-05 19:44:27', 0, 0),
(33, 'Postre tres leches con pan tostado', 'sencillo y rico ', '-1 Paquete de Pan Tostado \r\n-1 Taza de crema chantilly\r\n-1 Taza de fresas\r\n-1 Taza de leche\r\n-1 Taza de crema de leche\r\n-1 Taza de leche condensada\r\n-2 Cucharada de vainilla', 'Licúa la leche, la crema de leche, la leche condensada y la vainilla durante 30 segundos hasta tener una mezcla un poco aireada.\r\n\r\nEn una refractaria crea capas con Pan Tostado, agrega poco a poco con una cuchara parte de la mezcla de leche, cuándo esté toda húmeda vierte un poco de crema chantilly, otra capa de Pan Tostado con el remojo de leche y continúa hasta terminar con una capa de chantilly. \r\n\r\nRefrigera por al menos 3 horas antes de servir el postre. Pasado este tiempo, corta en porciones individuales y sirve con un poco de fresas por encima. ', 20, 'Fácil', 'img/1782761154_3607.png', 2, 4, '2026-06-05 19:50:04', 0, 0),
(43, 'Gelatina De Leche Estratificada', 'para verano', '-2 sobres (2 cucharadas) de gelatina sin sabor\r\n\r\n-2 3-oz cajas de gelatina de fresa\r\n\r\n-4 tazas agua fría, dividido\r\n\r\n-1 taza de Crema Daisy\r\n\r\n-4 onzas de queso crema\r\n\r\n-2 latas de 14 onzas de leche condensada azucarada', 'Rociar un tazón de vidrio o un molde de gelatina con spray de cocina antiadherente. Poner en el congelador.\r\n En una licuadora, mezclar la leche condensada azucarada, la crema agria de Daisy y el queso crema hasta que queden lisos. Verter la mezcla en una cacerola de salsa de 2 cuartos y calienta a fuego medio. Espolvorear la gelatina sin sabor y batirla constantemente hasta que la gelatina se disuelva por completo. Verter 1/3 de la mezcla de gelatina en el molde. Refrigerar hasta que la gelatina esté completamente ajustada (entre 15 y 20 minutos).\r\n\r\n Mientras tanto, dejar la mezcla de gelatina restante en la cacerola de la salsa. Agregar 1/4 taza de agua y batir con frecuencia para evitar que se espese. En una sartén con salsa de 3 cuartos, hervir 2 tazas de agua. Agregar la gelatina de fresa y remover hasta que se disuelva completamente. Agregar 1 3/4 tazas de agua fría y mezclar bien. Déjelo en la hornilla hasta que se enfríe. \r\n\r\nPara hacer las capas: usando un cucharón, verter cuidadosamente alrededor de 1/3 de la mezcla de gelatina de fresa encima de la gelatina (capa blanca) que ya está fijada (capa roja). Refrigerar hasta que la gelatina esté completamente ajustada (entre 15 y 20 minutos). Repetir las capas. Refrigerar al menos 2 horas una vez que haya terminado de agregar capas. \r\n\r\nPara desmoldar fácilmente: haga funcionar un cuchillo fino y afilado alrededor del borde superior de la gelatina. Luego, poner un plato redondo encima del molde de gelatina y voltee con cuidado. Adornar con las fresas.', 30, 'Fácil', 'img/1793733755_2532.jpg', 2, 4, '2026-11-03 19:22:35', 0, 0),
(44, 'Cheescake de arandanos ', 'Un equilibrio perfecto entre lo dulce y lo fresco.', '-250 g de arándanos Cuna de Platero (reservar unos cuantos para decorar)\r\n\r\n-200 g de galletas tipo digestive\r\n\r\n-100 g de mantequilla sin sal, derretida\r\n\r\n-500 g de queso crema (tipo Philadelphia)\r\n\r\n-200 ml de nata para montar (mínimo 35 % materia grasa)\r\n\r\n-120 g de azúcar glas\r\n\r\n-8 g de gelatina neutra en polvo (o 5 hojas de gelatina)\r\n\r\n-50 ml de agua (para hidratar la gelatina)\r\n\r\n-1 cucharadita de extracto de vainilla\r\n\r\n-2 cucharadas de zumo de limón natural\r\n\r\n-Ralladura de 1 limón (opcional, para un toque extra de frescura)', 'reúne todos los ingredientes para tenerlos a mano y agilizar el proceso. Lava cuidadosamente los arándanos de Cuna de Platero, escúrrelos bien y reserva algunos para la decoración final del cheesecake. Si los arándanos están muy fríos de la nevera, déjalos a temperatura ambiente unos minutos para que desarrollen todo su aroma y sabor.\r\n\r\nEn un bol pequeño o taza, hidrata la gelatina neutra con el agua indicada en la receta. Si usas gelatina en hojas, sumérgelas en agua fría para que se ablanden. Este paso previo permitirá que, cuando llegue el momento de incorporarla a la mezcla, se disuelva uniformemente y sin grumos, logrando una textura suave y cremosa en tu cheesecake.\r\n Tritura las galletas tipo digestive con un procesador de alimentos o colocándolas en una bolsa y aplastándolas con un rodillo hasta obtener una textura arenosa. Funde la mantequilla sin sal y mézclala con las galletas trituradas hasta que la mezcla se vea húmeda y manejable.\r\n\r\nForra la base de un molde desmontable de 20-22 cm con papel vegetal y vierte la mezcla de galletas. Presiona firmemente con el dorso de una cuchara o con la base de un vaso para compactar bien. Guarda el molde en la nevera mientras preparas el relleno, así la base se endurecerá y será más fácil de desmoldar después. \r\n\r\nCon esta base puedes preparar otras recetas como esta tarta fría de queso y moras.En un bol grande, bate el queso crema con el azúcar glas y la vainilla hasta obtener una crema suave y sin grumos. Aparte, monta la nata fría hasta que esté firme pero no excesivamente dura, para que luego se integre con facilidad.\r\n\r\nCalienta ligeramente el zumo de limón y disuelve en él la gelatina hidratada. Añádelo poco a poco a la mezcla de queso crema, batiendo a velocidad baja para que se integre sin cortar la nata. Finalmente, incorpora la nata montada con movimientos envolventes para mantener la textura aireada.\r\n\r\nColoca los arándanos frescos (excepto los reservados para decorar) en un cazo junto con dos cucharadas de azúcar y un chorrito de agua. Cocina a fuego medio durante 5-7 minutos, removiendo ocasionalmente, hasta que los frutos se ablanden y suelten su jugo natural.\r\n\r\nTritura la mezcla y pásala por un colador para eliminar las pieles, obteniendo un coulis suave. Deja templar unos minutos y luego reparte la mitad de este coulis sobre la mezcla de queso en el molde. Con una espátula o cuchillo, haz movimientos en espiral para crear un efecto marmolado. Cubre el cheesecake con film transparente, asegurándote de que no toque la superficie, y refrigera durante al menos 4 horas, aunque lo ideal es dejarlo reposar toda la noche para que adquiera la consistencia perfecta.\r\n\r\nAntes de servir, reparte el resto del coulis por encima y decora con los arándanos frescos que reservaste. Si quieres darle un toque más festivo, añade unas hojas de menta fresca o ralladura de limón. Sirve frío y disfruta de un postre que une frescura, color y sabor, perfecto para cualquier mesa navideña.\r\n\r\nTritura la mezcla y pásala por un colador para eliminar las pieles, obteniendo un coulis suave. Deja templar unos minutos y luego reparte la mitad de este coulis sobre la mezcla de queso en el molde. Con una espátula o cuchillo, haz movimientos en espiral para crear un efecto marmolado.\r\n\r\n', 60, 'Media', 'img/1793734065_6844.jpg', 2, 4, '2026-11-03 19:27:45', 0, 0),
(45, 'Tarta de chocolate', 'tarta de chocolate con base de hojaldre ', '-300 g (1 tableta y media) de Chocolate Negro NESTLÉ Postres\r\n-1 Masa Brisa BUITONI\r\n-350 ml de nata para montar\r\n-150 g de mantequilla', 'Precalentar el horno a 200º y seguir con la receta de esta tarta de chocolate fácil.\r\nExtender la masa brisa en un molde desmoldable de 25 cm. Recortar la masa sobrante.\r\nPinchar el fondo varias veces con un tenedor y cubrirla con papel que la envuelve, presionando bien las paredes.\r\n Rellenar la tarta con garbanzos secos.Hornear 15 min, retirar los garbanzos y el papel y hornear 5 min más, hasta que esté cocida\r\n.Mientras se enfría, preparar la crema de chocolate: Calentar la nata y cuando arranque a hervir, retirar el cazo del fuego y añadir la mantequilla y el chocolate troceado. \r\nRemover hasta dejar una crema fina.\r\nRellenar ahora la tarta de chocolate con la base de masa brisa \r\n.Refrigera mínimo 1 hora antes de servir esta tarta de chocolate. Decorar al gusto con los ingredientes favoritos para tartas de chocolate.', 40, 'Fácil', 'img/1793734398_7982.jpg', 1, 4, '2026-11-03 19:33:18', 0, 0),
(46, 'Donas', 'pequeño pastel de masa dulce y esponjosa', '- 700 g de harina 0000\r\n- 300 ml de leche tibia\r\n- 70 g de manteca blanda\r\n- 2 huevos\r\n- 150 g de azúcar\r\n- 1 cucharadita de esencia de vainilla\r\n- Ralladura de 1 limón o naranja\r\n- 25 g de levadura fresca\r\n- Chocolate blanco, negro, y grajeas para decorar\r\n- Aceite para freír', 'Hacer una corona con la harina, en el centro colocar la levadura, el azúcar, la ralladura de limón, la esencia de vainilla, los huevos, la manteca e ir incorporando de a poco la leche tibia. Amasar y si es necesario agregarle un poco más de harina.\r\nLa masa tiene que quedar lisa y blandita. Formar un bollo, colocarlo en un bol, envolverlo en papel film y dejar duplicar su tamaño en un lugar cálido (1 hora aproximadamente.) Estirar la masa en la mesada enharinada, cortar las rosquillas con un cortante redondo (usé uno de 7 cm) y cortar el centro. Cubrir y dejar leudar nuevamente (30 minutos). Freír en abundante aceite, a fuego bajo. \r\nRetirar y color sobre papel absorbente. La decoración: Al chocolate blanco derretido se le puede poner colorante para hacerlas del color preferido de tu mamá. Se las puede decorar con confites de chocolate, corazoncitos y también rellenar con dulce de leche.', 60, 'Media', 'img/1793734668_6232.jpg', 1, 4, '2026-11-03 19:37:48', 0, 0);

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
(9, NULL, 8, '2026-06-02 21:00:21'),
(10, NULL, 13, '2026-06-02 21:00:28'),
(11, NULL, 8, '2026-06-02 21:00:33'),
(12, NULL, 11, '2026-06-02 21:00:41'),
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
(29, 4, 4, '2026-06-05 19:28:50'),
(30, 4, 16, '2026-06-15 22:23:28'),
(31, 4, 12, '2026-06-15 22:23:32'),
(32, 4, 16, '2026-06-17 19:02:27'),
(33, 4, 8, '2026-06-27 22:00:50'),
(34, 4, 5, '2026-06-29 19:47:38'),
(35, 4, 33, '2026-06-29 19:47:47'),
(36, 4, 33, '2026-06-29 19:47:59'),
(37, 4, 14, '2026-06-29 19:48:37'),
(38, 4, 4, '2026-06-29 19:48:53'),
(39, 5, 8, '2026-06-29 20:18:53'),
(40, 5, 6, '2026-06-29 20:19:02'),
(41, 5, 6, '2026-06-29 20:19:11'),
(42, 5, 14, '2026-06-29 20:26:48'),
(43, 5, 33, '2026-06-29 20:26:49'),
(44, 5, 14, '2026-06-29 20:27:28'),
(45, 5, 33, '2026-06-29 20:27:36'),
(46, 4, 14, '2026-06-29 21:22:32'),
(47, 4, 6, '2026-07-01 16:42:46'),
(48, 4, 17, '2026-07-01 16:42:55'),
(49, 14, 14, '2026-07-08 19:13:29'),
(50, 14, 4, '2026-07-08 19:13:33'),
(51, 14, 17, '2026-07-08 19:15:16'),
(52, 14, 17, '2026-07-08 19:17:14'),
(53, 14, 6, '2026-07-08 19:17:17'),
(54, 14, 32, '2026-07-08 19:17:25'),
(55, 14, 12, '2026-07-08 19:17:33'),
(56, 14, 17, '2026-07-08 19:17:36'),
(57, 14, 14, '2026-07-08 19:17:38'),
(58, 14, 13, '2026-07-08 19:17:40'),
(59, 14, 32, '2026-07-08 19:17:42'),
(60, 14, 5, '2026-07-08 19:17:44'),
(61, 14, 17, '2026-07-08 19:17:46'),
(62, 14, 10, '2026-07-08 19:17:49'),
(63, 14, 33, '2026-07-08 19:17:51'),
(64, 14, 4, '2026-07-08 19:17:54'),
(65, 14, 33, '2026-07-08 19:17:55'),
(66, 14, 5, '2026-07-08 19:17:57'),
(67, 14, 8, '2026-07-08 19:18:01'),
(68, 14, 14, '2026-07-08 19:18:03');

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
(5, 'usuario', 'usuario@gmail.com', '$2y$10$y86puyBxODyWl85cHvNsI.9Fa.R1Pcfo9Neiz9UbG4yUn0r5YEroy', '2026-05-10 21:06:28', 'usuario'),
(12, 'nuevo', 'nuevo@gmail.com', '$2y$10$jhY1S2ikG6wgBIaPenDrMuoGh3mMxYZMmcwaWbdxmoZSZTJ8Dz0.C', '2026-06-24 18:20:37', 'usuario'),
(14, 'dac', 'dac@hotmail.com', '$2y$10$Jx2UgS8eU6um9yMo6jghauSQNFZ30drP3jgFDCo8ZnqMbGMSdxEn2', '2026-07-08 19:12:53', 'usuario');

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
(4, 5, 2, '2026-06-29 21:11:56'),
(4, 6, 5, '2026-05-20 19:09:34'),
(4, 8, 4, '2026-05-20 19:13:02'),
(4, 11, 5, '2026-06-29 19:44:57'),
(4, 14, 3, '2026-06-03 17:45:02'),
(4, 17, 4, '2026-06-04 19:07:48'),
(5, 3, 4, '2026-05-20 19:16:17'),
(5, 5, 4, '2026-05-20 19:14:34'),
(5, 10, 5, '2026-05-20 19:26:57'),
(14, 4, 5, '2026-07-08 19:14:09');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `ruleta_historial`
--
ALTER TABLE `ruleta_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
