
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-10-2024 a las 00:29:04
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
-- Base de datos: `inmobiliaria`
--
CREATE DATABASE IF NOT EXISTS `inmobiliaria` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `inmobiliaria`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `duenio`
--

CREATE TABLE IF NOT EXISTS `duenio` (
  `id_owner` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(80) NOT NULL,
  PRIMARY KEY (`id_owner`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `duenio`
--

INSERT INTO `duenio` (`id_owner`, `name`, `phone`, `email`) VALUES
(3, 'lisandra', '15674231', 'nuevomaillis@gmail.com'),
(19, 'Jose', '2494496102', 'jose@gmail.com'),
(21, 'Mariano', '2494874411', 'mariano@gmail.com'),
(23, 'Mariana', '2494496100', 'mariana@gmail.com'),
(25, 'Fernanda Gonzales', '2494496133', 'fer@gmail.com'),
(26, 'Kenai', '1548754', 'kenai@mail.com'),
(28, 'Alicia', '248471', 'ali@gmail.com'),
(30, 'maria marta', '1234676876897', 'mariamarta@gmail.com'),
(31, 'joaquin santellan', '1234', 'j@mail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propiedad`
--

CREATE TABLE IF NOT EXISTS `propiedad` (
  `id_property` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `zone` varchar(45) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `description` varchar(500) NOT NULL,
  `mode` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `city` varchar(45) NOT NULL,
  `id_owner` int(11) NOT NULL,
  PRIMARY KEY (`id_property`),
  KEY `id_duenioFK` (`id_owner`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `propiedad`
--

INSERT INTO `propiedad` (`id_property`, `type`, `zone`, `price`, `description`, `mode`, `status`, `city`, `id_owner`) VALUES
(6, 'Departamento', 'Centro', 200000, 'Departamento luminoso', 'Alquiler', 'Alquilado', 'Tandil', 3),
(10, 'casa', 'centro', 12134, 'nueva descripcion de la casita ', 'venta', 'vendido', 'tandil', 28),
(13, 'Lote', 'calvario', 85000, 'Terreno amplio en área residencial', 'venta', 'Disponible', 'tandil ', 19),
(14, 'departamento', 'uncas', 1234, 'departamento en zona uncas precioso', 'venta', 'vendido', 'tandil', 25),
(15, 'lot', 'centro', 7888, 'Hermoso lote para construccion servicios incluidos ', 'sell', 'rented', 'azul', 30),
(16, 'quinta', 'ferrocarril', 342, 'descripcion de la quinta del ferrocarril', 'venta', 'disponible', 'tandil', 30),
(17, 'lote', 'ferro', 9876, 'lore excepcional ', 'alquiler', 'vendido', 'tandil', 25),
(18, 'casa', 'dique', 12000, 'casa quinta', 'venta', 'vendido', 'tandil', 28);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`username`, `password`, `id_user`) VALUES
('dieguitoS', '$2y$10$z6HW2mJ0pe4qFClrYscZzOq0hyEoU1Zivo9r131LyraccMpGOt0Ni', 2),
('webadmin', '$2y$10$KRzkpwvb7sBn389por.7oOewkyw1KEJuqylEiF26PnEGSHYJXta8K', 7),
('webadmin2', '$2y$10$nz4i0yidCfJ26nWAcvQ3nOgCPO.wxu/4BS/A5.7M43n0gvDwBARVG', 8),
('webadmin3', '$2y$10$NmrN5zUHDgVVE5QtLvGPtemdU9HxXTWRkWvu02RHdxPoL9J6dyg2i', 9),
('webadmin4', '$2y$10$ZiptL0NDIXhcfUPEV/OrpuWn/4odGSmnB3JO/HXGEPY2Nhmz/OQr2', 10),
('webadmin5', '$2y$10$B.9yhGTK7tyhJMpvV96XdeUtrPBYbnBt2rstkFEnUSrst3D.90xBO', 11),
('webadmin200', '$2y$10$GYkWuOleLFNo2aQFh8.iw.xc7YDALowCtL1b196LIbghroqMUze42', 12),
('webadmin2000', '$2y$10$oQh0RCAJGAVrPIQ0MvjbCeOL7E6EpPfBx9fwvS9DpkEiBZaHd25Y6', 13),
('webadmin200000', '$2y$10$GvKF6EeJlzFDTvKmCEWav.p8lCx8L.Rb3rgLhhvlr4f3ZFK2uQmKm', 14),
('admin', '$2y$10$nyDnaFuDfodUfmO70SzUg.W6AgbvYixA5Hb0osbCFKhYM0n1Umffe', 15),
('usuario1', '$2y$10$uD0mLWKxA3E6v6UbGLL02e/15FwsLrvF7ibhxqyujkGCNUO/oBcTi', 16);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `propiedad`
--
ALTER TABLE `propiedad`
  ADD CONSTRAINT `propiedad_ibfk_1` FOREIGN KEY (`id_owner`) REFERENCES `duenio` (`id_owner`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
