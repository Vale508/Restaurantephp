-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-11-2025 a las 15:37:58
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
-- Base de datos: `restaurantenuevo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `Id_Menu` int(5) NOT NULL,
  `Nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`Id_Menu`, `Nombre`) VALUES
(1, 'entrada'),
(2, 'entradas'),
(3, 'platos fuertes'),
(4, 'postres'),
(5, 'bebidas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `Id_Mesa` int(5) NOT NULL,
  `Numero_Mesa` int(10) NOT NULL,
  `Capacidad` int(10) NOT NULL,
  `Ubicacion` varchar(255) NOT NULL,
  `Disponible` enum('Si','No') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`Id_Mesa`, `Numero_Mesa`, `Capacidad`, `Ubicacion`, `Disponible`) VALUES
(3, 2, 444, 'Norte', 'No'),
(5, 0, 0, 'Domicilio', 'Si');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `Id_Pedido` int(5) NOT NULL,
  `Fecha` date NOT NULL,
  `Estado` enum('Pendiente','En preparacion','Entregado','Cancelado') NOT NULL,
  `Total` decimal(10,2) NOT NULL,
  `Id_Mesa` int(5) NOT NULL,
  `Id_Usuario` int(11) DEFAULT NULL,
  `DireccionEntrega` varchar(255) DEFAULT NULL,
  `TelefonoEntrega` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`Id_Pedido`, `Fecha`, `Estado`, `Total`, `Id_Mesa`, `Id_Usuario`, `DireccionEntrega`, `TelefonoEntrega`) VALUES
(4, '2025-11-20', 'Cancelado', 2997000.00, 5, NULL, 'calle 99', '455555'),
(5, '2025-11-20', 'Pendiente', 90000.00, 5, NULL, 'calle 99', '3333'),
(6, '2025-11-20', 'Pendiente', 108000.00, 5, NULL, 'calle cualquiera', '454555'),
(7, '2025-11-20', 'Pendiente', 48000.00, 3, NULL, NULL, NULL),
(8, '2025-11-20', 'Entregado', 732000.00, 5, NULL, 'calle 1', '32145689635');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle`
--

CREATE TABLE `pedido_detalle` (
  `Id_Detalle` int(11) NOT NULL,
  `Id_Pedido` int(11) NOT NULL,
  `Id_Producto` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `Subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_detalle`
--

INSERT INTO `pedido_detalle` (`Id_Detalle`, `Id_Pedido`, `Id_Producto`, `Cantidad`, `Precio`, `Subtotal`) VALUES
(3, 4, 44, 999, 3000.00, 2997000.00),
(4, 5, 36, 9, 10000.00, 90000.00),
(5, 6, 34, 9, 12000.00, 108000.00),
(6, 7, 34, 4, 12000.00, 48000.00),
(7, 8, 43, 122, 6000.00, 732000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_producto`
--

CREATE TABLE `pedido_producto` (
  `Id_Pedido` int(5) NOT NULL,
  `Id_Producto` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `Id_Producto` int(5) NOT NULL,
  `Nombre` varchar(30) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Precio` int(10) NOT NULL,
  `Categoria` enum('Entradas','Platos Fuertes','Postres','Bebidas') NOT NULL,
  `Imagen` varchar(255) NOT NULL,
  `Disponibilidad` enum('Disponible','No Disponible') NOT NULL,
  `Id_Menu` int(5) NOT NULL,
  `Disponible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`Id_Producto`, `Nombre`, `Descripcion`, `Precio`, `Categoria`, `Imagen`, `Disponibilidad`, `Id_Menu`, `Disponible`) VALUES
(34, 'Trio de empanadas', 'Masa crujiente rellena de carne, pollo o verduras, servidas con una salsa de ají o chimichurri.', 12000, 'Entradas', 'https://media-cdn.tripadvisor.com/media/photo-s/1c/18/62/73/trio-d-empanadas.jpg', 'Disponible', 2, 0),
(35, 'Rollos de queso y jamón apanad', 'Rollitos de jamon y queso, crujientes por fuera y fundidos por dentro. Perfectos para disfrutar en cualquier momento.', 15000, 'Entradas', 'https://i.ytimg.com/vi/zM7QkQLjTQA/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLByBOcqZcLdngKHXo2Rgg3525sddA', 'Disponible', 2, 1),
(36, 'Chorizos coctel', 'Pequeños chorizos azados a la parrila, servidos con pan y chimichurri para un sabor tradicional.', 10000, 'Entradas', 'https://d1gvlij56ll33n.cloudfront.net/products/843-1-644187b645556099695602.jpg', '', 2, 1),
(37, 'Churrasco', 'Corte de carne a la parrilla, jugoso y tierno, acompañado de papas, ensalada y chimichurri.', 35000, 'Entradas', 'https://www.restauranteskab.com/wp-content/uploads/2021/04/churrasco-1.png', '', 3, 1),
(38, 'Lomo de res', 'Filete de lomo de res perfectamente sellado, servido con guarniciones tradicionales como pure de papa o verduras asadas.', 38000, 'Platos Fuertes', 'https://www.rama.com.co/-/media/Project/Upfield/Brands/Rama/Rama-CO/Assets/Recipes/sync-img/722ced7b-509f-4de9-87d3-2c89bc4996e0.png?rev=7e0149c612f74cfda39ca97f6aaca534', '', 3, 1),
(39, 'Bandeja paisa', 'Un festín de sabores colombianos que incluye arroz, frijoles, carne molida, chicharrón, arepa, huevo frio, aguacate y plátano maduro.', 32000, 'Platos Fuertes', 'https://www.infobae.com/new-resizer/j8Tn2FTf03GyboaZXdMHZtfrIjk=/arc-anglerfish-arc2-prod-infobae/public/7ZLBIEXDAFEUFB2MXROVEX2DHI.jpg', '', 3, 1),
(40, 'Gelatina', 'Deliciosa y fresca, hecha con jugos naturales, ideal para un postre ligero y refrescante.', 7000, 'Postres', 'https://lh6.googleusercontent.com/proxy/UQH6Eu3pn5dXcxiZ5L8iNsnE9FCm5W00-EPSb55UI6bqmSQnWYWrE6BrFaxd4zg7qZYg1oNjhBE40IUsljlsEHQwj2JkWJnUilajpZi1rhjMT5I72d-sz6M-', '', 4, 1),
(41, 'Fresas con crema', 'Fresas bañadas en una suave y dulce crema, un postre simple pero delicioso.', 8500, 'Postres', 'https://www.mycolombianrecipes.com/wp-content/uploads/2014/03/Fresas-con-Crema.jpg', '', 4, 1),
(42, 'Torta de chocolate', 'Un esponjoso pastel de chocolate, cubierto con un glaciado cremoso, perfecto para los amantes del chocolate.', 6500, 'Postres', 'https://cdn.colombia.com/gastronomia/2016/06/08/torta-de-chocolate-3099.jpg', '', 4, 1),
(43, 'Coca Cola', 'La clásica bebida refrescante con su sabor único y burbujeante, ideal para acompañar cualquier plato y disfrutar en cualquier momento.', 6000, 'Bebidas', 'https://upload.wikimedia.org/wikipedia/commons/e/e8/15-09-26-RalfR-WLC-0098_-_Coca-Cola_glass_bottle_%28Germany%29.jpg', '', 5, 1),
(44, 'Jugo de naranja', 'Natural y fresco, exprimido al momento, para ofrecer todo el sabor y la vitalidad de las naranjas, Perfecto para un toque de frescura y energía.', 3000, 'Bebidas', 'https://www.zuvamesa.com/imagenes/para-que-es-bueno-el-zumo-de-naranja.jpg', '', 5, 1),
(45, 'Coronita', 'Cerveza refrescante y ligera, servida en su tradicional botella pequeña, ideal para disfrutar con amigos o acompañar un buen plato de comida.', 12000, 'Bebidas', 'https://lacaretalicores.com/cdn/shop/files/Cerveza-Coronita-Extra-210-ml.webp?v=1737561563&width=800', '', 5, 1),
(48, 'ffsf', 'dsd', 12, 'Platos Fuertes', 'https://www.cocinacaserayfacil.net/wp-content/uploads/2020/03/Recetas-faciles-de-cocinar-y-sobrevivir-en-casa-al-coronavirus_2.jpg', 'Disponible', 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `Id_Reserva` int(5) NOT NULL,
  `Numero_Personas` int(10) NOT NULL,
  `Estado` varchar(30) NOT NULL,
  `Fecha_Reserva` date NOT NULL,
  `Id_Usuario` int(5) NOT NULL,
  `Id_Mesa` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`Id_Reserva`, `Numero_Personas`, `Estado`, `Fecha_Reserva`, `Id_Usuario`, `Id_Mesa`) VALUES
(1, 10, 'Cancelada', '2025-11-25', 6, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `Id_Usuario` int(5) NOT NULL,
  `Tipo_Usuario` varchar(30) NOT NULL,
  `Nombre` varchar(30) NOT NULL,
  `Documento` int(10) NOT NULL,
  `Telefono` int(10) NOT NULL,
  `Correo` varchar(30) NOT NULL,
  `Contrasena` varchar(255) NOT NULL,
  `Direccion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`Id_Usuario`, `Tipo_Usuario`, `Nombre`, `Documento`, `Telefono`, `Correo`, `Contrasena`, `Direccion`) VALUES
(1, 'Administrador', 'Carlos', 1234, 12345, 'carlitos@gmail.com', '$2y$10$KpeBBJbAIa26xmFBhE8ZNexNyfs9Wo6Lo152ba0eZGSGlQ44WaqiC', 'calle 23'),
(4, 'Administrador', 'dtf', 5756, 46, 'njuan44@gmail.com', '$2y$10$HL9MQRoYCpEHdmPO3RB3o.JSm/MiUZ2X3isElWzbbgI09zSntScwy', 'gtfg'),
(6, 'Usuario', 'jaja', 23456, 4444, 'jaja@gmail.com', '$2y$10$V5mbmxO5NVItuRyKzez46Ovn7JeSJFiVzlTTAGMmP3YIqOFbAl8ba', 'calle 29'),
(7, 'Usuario', 'Valerin', 1074589638, 2147483647, 'cansona1@gmail.com', '$2y$10$PcI5IE6yjI5TrmoXYV2O7eDig03IpMnhcYBMP6RILcdP1qLETbfvC', 'calle 9'),
(9, 'Usuario', 'sofia', 1074589638, 2147483647, 'cansona@gmail.com', '$2y$10$sn0stL4hZwN1d/YsNYlIEuSv7MaPiTeezBiE2WAhJcpHgOp0sYYGW', 'calle 9');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`Id_Menu`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`Id_Mesa`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`Id_Pedido`),
  ADD UNIQUE KEY `Id_Mesa` (`Id_Mesa`,`Id_Usuario`),
  ADD KEY `fk_pedido_usuario` (`Id_Usuario`);

--
-- Indices de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD PRIMARY KEY (`Id_Detalle`),
  ADD KEY `Id_Pedido` (`Id_Pedido`),
  ADD KEY `Id_Producto` (`Id_Producto`);

--
-- Indices de la tabla `pedido_producto`
--
ALTER TABLE `pedido_producto`
  ADD UNIQUE KEY `Id_Pedido` (`Id_Pedido`,`Id_Producto`),
  ADD KEY `fk_pedidoproducto_producto` (`Id_Producto`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`Id_Producto`),
  ADD KEY `Id_Menu_2` (`Id_Menu`),
  ADD KEY `Id_Menu` (`Id_Menu`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`Id_Reserva`),
  ADD UNIQUE KEY `Id_Usuario` (`Id_Usuario`,`Id_Mesa`),
  ADD KEY `fk_reserva_mesa` (`Id_Mesa`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`Id_Usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `Id_Menu` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `Id_Mesa` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `Id_Pedido` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  MODIFY `Id_Detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `Id_Producto` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `Id_Reserva` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `Id_Usuario` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_mesa` FOREIGN KEY (`Id_Mesa`) REFERENCES `mesa` (`Id_Mesa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD CONSTRAINT `pedido_detalle_ibfk_1` FOREIGN KEY (`Id_Pedido`) REFERENCES `pedido` (`Id_Pedido`),
  ADD CONSTRAINT `pedido_detalle_ibfk_2` FOREIGN KEY (`Id_Producto`) REFERENCES `producto` (`Id_Producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedido_producto`
--
ALTER TABLE `pedido_producto`
  ADD CONSTRAINT `fk_pedidoproducto_pedido` FOREIGN KEY (`Id_Pedido`) REFERENCES `pedido` (`Id_Pedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedidoproducto_producto` FOREIGN KEY (`Id_Producto`) REFERENCES `producto` (`Id_Producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_menu` FOREIGN KEY (`Id_Menu`) REFERENCES `menu` (`Id_Menu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_reserva_mesa` FOREIGN KEY (`Id_Mesa`) REFERENCES `mesa` (`Id_Mesa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reserva_usuario` FOREIGN KEY (`Id_Usuario`) REFERENCES `usuario` (`Id_Usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
