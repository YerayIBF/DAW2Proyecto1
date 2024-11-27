-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         9.0.1 - MySQL Community Server - GPL
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
CREATE DATABASE IF NOT EXISTS `BBDD_DAW2Proyecto1` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla BBDD_DAW2Proyecto1.DetallePedido: ~0 rows (aproximadamente)

-- Volcando estructura para tabla BBDD_DAW2Proyecto1.Logs_Admin
CREATE TABLE IF NOT EXISTS `Logs_Admin` (
  `ID_Log` int NOT NULL AUTO_INCREMENT,
  `Accion` varchar(255) NOT NULL,
  `Fecha_Log` datetime DEFAULT CURRENT_TIMESTAMP,
  `ID_Usuario` int DEFAULT NULL,
  PRIMARY KEY (`ID_Log`),
  KEY `ID_Usuario` (`ID_Usuario`),
  CONSTRAINT `Logs_Admin_ibfk_1` FOREIGN KEY (`ID_Usuario`) REFERENCES `Usuarios` (`ID_Usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla BBDD_DAW2Proyecto1.Logs_Admin: ~0 rows (aproximadamente)

-- Volcando estructura para tabla BBDD_DAW2Proyecto1.Oferta
CREATE TABLE IF NOT EXISTS `Oferta` (
  `ID_Oferta` int NOT NULL AUTO_INCREMENT,
  `Codigo` varchar(20) NOT NULL,
  `Descuento` decimal(5,2) NOT NULL,
  `Usos_Disponibles` int DEFAULT '1',
  PRIMARY KEY (`ID_Oferta`) USING BTREE,
  UNIQUE KEY `Codigo` (`Codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla BBDD_DAW2Proyecto1.Oferta: ~0 rows (aproximadamente)

-- Volcando estructura para tabla BBDD_DAW2Proyecto1.Pedidos
CREATE TABLE IF NOT EXISTS `Pedidos` (
  `ID_Pedido` int NOT NULL AUTO_INCREMENT,
  `Fecha_Pedido` datetime DEFAULT CURRENT_TIMESTAMP,
  `Precio_Total` decimal(10,2) NOT NULL,
  `ID_Usuario` int DEFAULT NULL,
  `ID_Oferta` int DEFAULT NULL,
  PRIMARY KEY (`ID_Pedido`),
  KEY `ID_Usuario` (`ID_Usuario`),
  KEY `ID_Oferta` (`ID_Oferta`),
  CONSTRAINT `Pedidos_ibfk_1` FOREIGN KEY (`ID_Usuario`) REFERENCES `Usuarios` (`ID_Usuario`),
  CONSTRAINT `Pedidos_ibfk_2` FOREIGN KEY (`ID_Oferta`) REFERENCES `Oferta` (`ID_Oferta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla BBDD_DAW2Proyecto1.Pedidos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla BBDD_DAW2Proyecto1.Productos
CREATE TABLE IF NOT EXISTS `Productos` (
  `ID_Producto` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  `Descripcion` text,
  `Precio` decimal(10,2) NOT NULL,
  `Imagen` varchar(255) DEFAULT NULL,
  `cantidad` int DEFAULT '1',
  PRIMARY KEY (`ID_Producto`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `Nombre` varchar(50) NOT NULL,
  `Correo` varchar(100) NOT NULL,
  `Contraseña` varchar(255) NOT NULL,
  `Rol` enum('usuario','admin') DEFAULT 'usuario',
  PRIMARY KEY (`ID_Usuario`),
  UNIQUE KEY `Correo` (`Correo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla BBDD_DAW2Proyecto1.Usuarios: ~1 rows (aproximadamente)
INSERT INTO `Usuarios` (`ID_Usuario`, `Nombre`, `Correo`, `Contraseña`, `Rol`) VALUES
	(7, 'Yeray', 'yerayalbesa312@gmail.com', '$2y$10$8M6AnR5rGHjhxX9y5KPzVO7OpFUfRn8Q/WylpmpWc1IrmyL.jTyu.', 'usuario');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
