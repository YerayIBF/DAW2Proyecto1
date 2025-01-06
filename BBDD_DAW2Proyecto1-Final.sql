-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.0 - MySQL Community Server - GPL
-- SO del servidor:              Linux
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para BBDD_DAW2Proyecto1
CREATE DATABASE IF NOT EXISTS `BBDD_DAW2Proyecto1` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `BBDD_DAW2Proyecto1`;

-- Volcando estructura para tabla BBDD_DAW2Proyecto1.DetallePedido
CREATE TABLE IF NOT EXISTS `DetallePedido` (
  `ID_DetallePedido` int NOT NULL AUTO_INCREMENT,
  `ID_Pedido` int DEFAULT NULL,
  `ID_Producto` int DEFAULT NULL,
  `Cantidad` int NOT NULL,
  `Precio_Unitario` decimal(10,2) NOT NULL,
  `PrecioTotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`ID_DetallePedido`),
  KEY `ID_Pedido` (`ID_Pedido`),
  KEY `ID_Producto` (`ID_Producto`),
  CONSTRAINT `DetallePedido_ibfk_1` FOREIGN KEY (`ID_Pedido`) REFERENCES `Pedidos` (`ID_Pedido`),
  CONSTRAINT `DetallePedido_ibfk_2` FOREIGN KEY (`ID_Producto`) REFERENCES `Productos` (`ID_Producto`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla BBDD_DAW2Proyecto1.DetallePedido: ~23 rows (aproximadamente)
INSERT INTO `DetallePedido` (`ID_DetallePedido`, `ID_Pedido`, `ID_Producto`, `Cantidad`, `Precio_Unitario`, `PrecioTotal`) VALUES
	(15, 23, 2, 2, 18.00, 36.00),
	(16, 24, 2, 1, 18.00, 18.00),
	(17, 25, 4, 1, 14.00, 14.00),
	(18, 25, 1, 1, 12.99, 12.99),
	(19, 25, 2, 1, 18.00, 18.00),
	(20, 25, 3, 1, 11.00, 11.00),
	(21, 29, 2, 2, 18.00, 36.00),
	(22, 29, 3, 2, 11.00, 22.00),
	(23, 30, 3, 15, 11.00, 165.00),
	(30, 52, 2, 1, 18.00, 18.00),
	(31, 52, 1, 1, 12.99, 12.99),
	(32, 52, 2, 1, 18.00, 18.00),
	(33, 17, 1, 12, 12.99, 155.88),
	(34, 52, 1, 1, 12.99, 12.99),
	(36, 30, 1, 1, 12.99, 12.99),
	(37, 30, 2, 1, 18.00, 18.00),
	(39, 53, 1, 2, 12.99, 25.98),
	(41, 17, 1, 1, 12.99, 12.99),
	(42, 55, 1, 1, 12.99, 12.99),
	(43, 56, 2, 1, 18.00, 18.00),
	(44, 57, 2, 1, 18.00, 18.00),
	(45, 58, 3, 1, 11.00, 11.00),
	(46, 59, 2, 1, 18.00, 18.00),
	(47, 61, 4, 2, 14.00, 28.00),
	(48, 61, 1, 1, 12.99, 12.99),
	(49, 62, 2, 1, 18.00, 18.00),
	(50, 63, 2, 1, 18.00, 18.00);

-- Volcando estructura para tabla BBDD_DAW2Proyecto1.Logs_Admin
CREATE TABLE IF NOT EXISTS `Logs_Admin` (
  `ID_Log` int NOT NULL AUTO_INCREMENT,
  `Accion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Fecha_Log` datetime DEFAULT CURRENT_TIMESTAMP,
  `ID_Usuario` int DEFAULT NULL,
  PRIMARY KEY (`ID_Log`),
  KEY `ID_Usuario` (`ID_Usuario`),
  CONSTRAINT `Logs_Admin_ibfk_1` FOREIGN KEY (`ID_Usuario`) REFERENCES `Usuarios` (`ID_Usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla BBDD_DAW2Proyecto1.Logs_Admin: ~29 rows (aproximadamente)
INSERT INTO `Logs_Admin` (`ID_Log`, `Accion`, `Fecha_Log`, `ID_Usuario`) VALUES
	(1, 'pRUEBA PARA MOSTRAR LOS LOGS', '2025-01-03 05:07:38', 8),
	(2, 'Se ha creado un nuevo pedido con ID: 51', '2025-01-03 20:45:43', NULL),
	(3, 'Eliminado pedido con ID: 50', '2025-01-04 00:13:41', 8),
	(4, 'Se ha creado un nuevo pedido con ID: 52', '2025-01-04 00:13:59', 8),
	(5, 'Actualizado pedido con ID: 52', '2025-01-04 00:15:43', 8),
	(6, 'Eliminada oferta con ID: 3', '2025-01-04 00:16:02', 8),
	(7, 'Creada nueva oferta: Prueba2', '2025-01-04 00:16:15', 8),
	(8, 'Actualizada oferta con ID: 10', '2025-01-04 00:16:27', 8),
	(9, 'Eliminada oferta con ID: 10', '2025-01-04 00:16:34', 8),
	(10, 'Creado nuevo producto con ID 14', '2025-01-04 00:16:58', 8),
	(11, 'Actualizado producto con ID: 14', '2025-01-04 00:17:06', 8),
	(12, 'Eliminado producto con ID: 14', '2025-01-04 00:17:14', 8),
	(13, 'Creado nuevo usuario: Prueba', '2025-01-04 00:17:34', 8),
	(14, 'Actualizado usuario con ID: 10', '2025-01-04 00:17:46', 8),
	(15, 'Actualizado usuario con ID: 10', '2025-01-04 00:17:50', 8),
	(16, 'Creada nueva oferta: Prueba2', '2025-01-04 00:18:21', 8),
	(17, 'Creado nuevo usuario: Yeray', '2025-01-04 00:20:00', 8),
	(18, 'Eliminado producto con ID: 12', '2025-01-04 00:20:23', 8),
	(19, 'Creado nuevo usuario: Prueba2', '2025-01-04 00:21:29', 8),
	(20, 'Eliminado usuario con ID: 14', '2025-01-04 00:21:32', 8),
	(21, 'Creado nuevo usuario: Prueba2', '2025-01-04 00:21:48', 8),
	(22, 'Eliminado usuario con ID: 15', '2025-01-04 00:21:51', 8),
	(23, 'Eliminado detalle de pedido con ID: 35', '2025-01-04 16:16:57', 8),
	(24, 'Creado detalle de pedido para pedido con ID: 17', '2025-01-04 16:17:01', 8),
	(25, 'Actualizado detalle de pedido con ID: 41', '2025-01-04 16:17:05', 8),
	(26, 'Actualizado detalle de pedido con ID: 41', '2025-01-04 16:17:10', 8),
	(27, 'Actualizado detalle de pedido con ID: 41', '2025-01-04 16:17:16', 8),
	(28, 'Actualizado usuario con ID: 7', '2025-01-04 18:52:14', 8),
	(29, 'Actualizado pedido con ID: 57', '2025-01-05 01:42:46', 8),
	(30, 'Creado nuevo usuario: Prueba2', '2025-01-05 16:13:00', 8),
	(31, 'Eliminado usuario con ID: 30', '2025-01-05 16:13:08', 8),
	(32, 'Actualizado usuario con ID: 7', '2025-01-05 16:21:57', 8),
	(33, 'Actualizado usuario con ID: 7', '2025-01-05 16:22:01', 8),
	(34, 'Actualizado usuario con ID: 7', '2025-01-05 16:22:10', 8);

-- Volcando estructura para tabla BBDD_DAW2Proyecto1.Oferta
CREATE TABLE IF NOT EXISTS `Oferta` (
  `ID_Oferta` int NOT NULL AUTO_INCREMENT,
  `Codigo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Descuento` decimal(5,2) NOT NULL,
  `Usos_Disponibles` int DEFAULT '1',
  PRIMARY KEY (`ID_Oferta`) USING BTREE,
  UNIQUE KEY `Codigo` (`Codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla BBDD_DAW2Proyecto1.Oferta: ~2 rows (aproximadamente)
INSERT INTO `Oferta` (`ID_Oferta`, `Codigo`, `Descuento`, `Usos_Disponibles`) VALUES
	(1, 'prueba', 10.00, 85),
	(11, 'Prueba2', 1.00, 0);

-- Volcando estructura para tabla BBDD_DAW2Proyecto1.Pedidos
CREATE TABLE IF NOT EXISTS `Pedidos` (
  `ID_Pedido` int NOT NULL AUTO_INCREMENT,
  `Fecha_Pedido` datetime DEFAULT CURRENT_TIMESTAMP,
  `Precio_Total` decimal(10,2) NOT NULL,
  `ID_Usuario` int DEFAULT NULL,
  `ID_Oferta` int DEFAULT NULL,
  `Direccion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Dedicatoria` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'En preparación',
  PRIMARY KEY (`ID_Pedido`),
  KEY `ID_Usuario` (`ID_Usuario`),
  KEY `ID_Oferta` (`ID_Oferta`),
  CONSTRAINT `Pedidos_ibfk_1` FOREIGN KEY (`ID_Usuario`) REFERENCES `Usuarios` (`ID_Usuario`),
  CONSTRAINT `Pedidos_ibfk_2` FOREIGN KEY (`ID_Oferta`) REFERENCES `Oferta` (`ID_Oferta`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla BBDD_DAW2Proyecto1.Pedidos: ~21 rows (aproximadamente)
INSERT INTO `Pedidos` (`ID_Pedido`, `Fecha_Pedido`, `Precio_Total`, `ID_Usuario`, `ID_Oferta`, `Direccion`, `Dedicatoria`, `Estado`) VALUES
	(17, '2024-12-11 17:05:07', 18.00, 7, 1, 'calle 13', '', 'En camino'),
	(22, '2024-12-12 16:36:12', 25.00, 7, NULL, 'Calle 5', '', 'Entregado'),
	(23, '2024-12-16 19:06:04', 18.00, 7, NULL, 'calle 2', '', 'En preparación'),
	(24, '2024-12-16 19:07:54', 18.00, 7, 1, 'calle53', '', 'En preparación'),
	(25, '2024-12-29 19:39:09', 55.99, 8, NULL, 'Carrer Puig del Ravell, 6, 2o-4a', '', 'En preparación'),
	(28, '2024-12-30 18:37:14', 0.01, 7, 1, 'cALLE 2', '32323232', 'En preparación'),
	(29, '2024-12-31 18:18:08', 47.00, 8, NULL, 'Carrer Puig del Ravell, 6, 2o-4a', '', 'En preparación'),
	(30, '2024-12-31 18:20:56', 148.50, 8, 1, 'Carrer Puig del Ravell, 6, 2o-4a', '', 'En preparación'),
	(36, '2025-01-01 01:45:53', 99.97, 7, 1, 'C1del Ravell, 6, 2o-4a', '1weqw', 'En camino'),
	(37, '2025-01-02 00:27:48', 2.00, 7, 1, 'Carrer Puig del Ravell, 6, 2o-4a', '2', 'En preparación'),
	(39, '2025-01-02 00:59:10', 1.00, 7, 1, '2', '1', 'En preparación'),
	(48, '2025-01-03 17:40:08', 10.00, 7, 1, 'Carrer Puig del Ravell, 6, 2o-4a', 'eqweqw', 'En preparación'),
	(49, '2025-01-03 17:44:41', 11.00, 7, 1, 'Carrer Puig del Ravell, 6, 2o-4a', '1', 'En preparación'),
	(52, '2025-01-04 01:13:59', 11.00, 8, 1, 'Carrer', '1', 'En preparación'),
	(53, '2025-01-04 02:49:05', 23.38, 8, 1, 'calle2', '', 'En preparación'),
	(54, '2025-01-04 02:49:08', 0.00, 8, NULL, 'calle2', NULL, 'En preparación'),
	(55, '2025-01-05 01:42:31', 11.69, 8, 1, 'Prueba2', 'Prueba', 'En preparación'),
	(56, '2025-01-05 01:57:49', 16.20, 8, 1, 'Prueba2', '', 'En preparación'),
	(57, '2025-01-05 02:01:40', 16.20, 8, 1, 'Prueba2', '', 'En camino'),
	(58, '2025-01-05 07:10:48', 16.81, 8, 1, '1', '', 'En preparación'),
	(59, '2025-01-05 07:11:11', 26.61, 8, NULL, '1', '', 'En preparación'),
	(61, '2025-01-05 07:24:46', 49.47, 8, 1, '1', '', 'En preparación'),
	(62, '2025-01-05 07:39:45', 24.43, 8, 1, '1', '', 'En preparación'),
	(63, '2025-01-05 08:29:01', 24.43, 8, 1, '11', '', 'En preparación');

-- Volcando estructura para tabla BBDD_DAW2Proyecto1.Productos
CREATE TABLE IF NOT EXISTS `Productos` (
  `ID_Producto` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Descripcion` text COLLATE utf8mb4_unicode_ci,
  `Precio` decimal(10,2) NOT NULL,
  `Imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cantidad` int DEFAULT '1',
  PRIMARY KEY (`ID_Producto`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla BBDD_DAW2Proyecto1.Productos: ~12 rows (aproximadamente)
INSERT INTO `Productos` (`ID_Producto`, `Nombre`, `Descripcion`, `Precio`, `Imagen`, `cantidad`) VALUES
	(1, 'Mezcla De Tempura', 'Mezcla crujiente de tempura: tempuras de coliflor, brócoli y mayonesa picante', 12.99, 'mezclaTempura.webp', 1),
	(2, 'Albahaca tailandesa', 'Carne vegana salteada, bambú, albahaca, jengibre y arroz basmati', 18.00, 'albahacatailandesa.webp', 1),
	(3, 'Tortitas', 'Tortitas de avena y plátano con un toque de vainilla, crema de cacao y arándanos frescos y plátano', 11.00, 'Tortitas.webp', 1),
	(4, 'Pastel De Arroz', 'Torta de arroz crujiente con crema de aguacate y sin pollo, termina con una salsa coreana de chile dulce', 14.00, 'pastelDeArroz.webp', 1),
	(5, 'Tzatziki', 'Una salsa clásica de yogur griego con ajo y pepino, servida con pan', 8.00, 'Tzatziki.webp', 1),
	(6, 'Tacos', 'Boloñesa en 3 tacos de pan, con rúcula, salsa, tomates, cebolletas y pepinillos', 14.99, 'Tacos.webp', 1),
	(7, 'Baba Ganoush', 'Dip de berenjena asada ahumada con tahini, pan caliente del Medio Oriente', 9.99, 'babaGanoush.webp', 1),
	(8, 'Ensalada Del Medio Oriente', 'bowl de ensalada del Medio Oriente: tabulé, falafels, tzatziki, pepinillos y más', 16.99, 'ensaladaDelMedioOriente.webp', 1),
	(9, 'Pad-thai', 'Pad Thai con verduras de temporada, coco, cebolletas y cilantro. ', 15.00, 'Pad-Thai.webp', 1),
	(10, 'Pasta Boloñesa', 'Un clásico hecho con mozzarella y nuestra salsa secreta, servida con tzatziki', 16.00, 'PastaBoloñesa.webp', 1),
	(11, 'Patatas Fritas', 'Elige salsa de tomate o mayonesa', 4.99, 'patatasFritas.webp', 1),
	(12, 'Curry Tailandés', 'Curry panang con crema de coco, jengibre, bambú, verduras y arroz basmati', 18.00, 'Curry_Tailandés.webp', 1);

-- Volcando estructura para tabla BBDD_DAW2Proyecto1.Usuarios
CREATE TABLE IF NOT EXISTS `Usuarios` (
  `ID_Usuario` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Apellido` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Correo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Contraseña` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Rol` enum('usuario','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'usuario',
  PRIMARY KEY (`ID_Usuario`),
  UNIQUE KEY `Correo` (`Correo`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla BBDD_DAW2Proyecto1.Usuarios: ~2 rows (aproximadamente)
INSERT INTO `Usuarios` (`ID_Usuario`, `Nombre`, `Apellido`, `Correo`, `Contraseña`, `Rol`) VALUES
	(7, 'Yeray', 'Albesa2', 'yerayalbesa312@gmail.com', '$2y$10$cOi9w3pDKUyVG4S5pQ4Pb.ghr5/dQ88ul3lxRifo7z72rGANpdHVC', 'usuario'),
	(8, 'Admin', 'Admin', 'Admin@gmail.com', '$2y$10$Rkvyyve6i50uS2KQ0uZPNOQ5soa.mGo5hyQ04C0YbUMmccOWMMGaK', 'admin'),
	(29, 'Prueba2', 'Prueba2', 'admin2@gmail.com', '$2y$10$cTm3WDnvvYvs.ziQ2T1Dk.Kgu4romm4quYi91fwBZ5K0hbTwWRneS', 'usuario');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
