-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 16-04-2025 a las 16:34:13
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cluster_role`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) CHARACTER SET ucs2 COLLATE ucs2_spanish_ci DEFAULT NULL,
  `contraseña` varchar(100) CHARACTER SET ucs2 COLLATE ucs2_spanish_ci DEFAULT NULL,
  `email` varchar(20) CHARACTER SET ucs2 COLLATE ucs2_spanish_ci DEFAULT NULL,
  `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `positivo` int DEFAULT '0',
  `negativo` int DEFAULT '0',
  `profile_photo` blob,
  `premium` tinyint(1) DEFAULT NULL,
  `baneo` tinyint(1) DEFAULT NULL,
  `administrador` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_user`, `user_name`, `contraseña`, `email`, `creation_date`, `positivo`, `negativo`, `profile_photo`, `premium`, `baneo`, `administrador`) VALUES
(2, 'prueba', '$2y$10$onE9tSPfYq7h6', 'a@gmail.com', '2025-04-16 13:14:59', 0, 0, NULL, NULL, NULL, NULL),
(3, 'prueba1', '$2y$10$kw6CD71iNA0oR', 'si@gmail.com', '2025-04-16 15:31:49', 0, 0, NULL, NULL, NULL, NULL),
(4, 'prueba2', '$2y$10$FqS/EUPsxoeYpFoS5lrfeO1uq0G3Y3N7TNkNdMjoQvWO0T1bKIQDy', 'aa@gmail.com', '2025-04-16 15:59:42', 0, 0, NULL, NULL, NULL, NULL),
(5, 'admin', '$2y$10$cKeUHkBVe7Q7FIinNP8reuuaa2B88OtQ.tDnxKcyKJloADmgnwEla', 'admin@hotmail.com', '2025-04-16 16:10:18', 0, 0, NULL, NULL, NULL, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
