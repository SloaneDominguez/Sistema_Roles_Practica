-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-03-2026 a las 03:22:20
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
-- Base de datos: `sistema_roles`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_logs`
--

CREATE TABLE `actividad_logs` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `accion` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividad_logs`
--

INSERT INTO `actividad_logs` (`id_log`, `id_usuario`, `accion`, `fecha`) VALUES
(7, 1, 'Creó un nuevo producto: AWS', '2026-03-20 03:02:30'),
(8, 1, 'Actualizó el producto: Laptop Gamer Pro ', '2026-03-20 03:05:59'),
(9, 7, 'Actualizó el producto: Laptop Gamer Pro Max', '2026-03-20 03:08:50'),
(10, 7, 'Actualizó el producto: Laptop Gamer Pro Max 17', '2026-03-20 03:40:13'),
(11, 8, 'Realizó una venta de 1 unidad(es) de \'Laptop Gamer Pro Max 17\'. Total: $17,888.00', '2026-03-20 03:42:52'),
(12, 8, 'Realizó una venta de 1 unidad(es) de \'Laptop Gamer Pro Max 17\'. Total: $17,888.00', '2026-03-20 03:42:53'),
(13, 8, 'Realizó una venta de 1 unidad(es) de \'Laptop Gamer Pro Max 17\'. Total: $17,888.00', '2026-03-20 03:42:53'),
(14, 8, 'Realizó una venta de 1 unidad(es) de \'Laptop Gamer Pro Max 17\'. Total: $17,888.00', '2026-03-20 03:42:54'),
(15, 1, 'Realizó una venta de 1 unidad(es) de \'AWS\'. Total: $34,543.00', '2026-03-20 14:18:34'),
(16, 1, 'Actualizó el producto: Laptop Gamer', '2026-03-20 14:19:04'),
(17, 1, 'Realizó una venta de 1 unidad(es) de \'Laptop Gamer\'. Total: $17,888.00', '2026-03-21 04:00:04'),
(18, 9, 'Actualizó el producto: Laptop Gamer Pro ', '2026-03-21 05:48:32'),
(19, 1, 'Actualizó el producto: Laptop Gamer Pro Max', '2026-03-21 18:00:06'),
(20, 1, 'Actualizó el producto: Laptop Gamer Pro', '2026-03-21 18:01:20'),
(21, 1, 'Creó un nuevo producto: Belem', '2026-03-21 18:04:26'),
(22, 1, 'Realizó una venta de 1 unidad(es) de \'Laptop Gamer Pro\'. Total: $17,888.00', '2026-03-21 18:07:18'),
(23, 1, 'Realizó una venta de 1 unidad(es) de \'Laptop Gamer Pro\'. Total: $17,888.00', '2026-03-21 18:07:21'),
(24, 1, 'Creó un nuevo producto: Dani', '2026-03-21 18:07:47'),
(25, 1, 'Actualizó el producto: Laptop Gamer ', '2026-03-21 18:08:06'),
(26, 11, 'Actualizó el producto: Laptop Gamer Pro ', '2026-03-21 18:23:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `accion` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id_log`, `id_usuario`, `accion`, `fecha`) VALUES
(1, 1, 'Inicio de sesión exitoso', '2026-03-17 03:08:40'),
(2, 1, 'Inicio de sesión exitoso', '2026-03-17 03:08:54'),
(3, 1, 'Inicio de sesión exitoso', '2026-03-17 03:13:31'),
(4, 1, 'Inicio de sesión exitoso', '2026-03-17 03:31:19'),
(5, 1, 'Inicio de sesión exitoso', '2026-03-17 03:49:15'),
(6, 1, 'Inicio de sesión exitoso', '2026-03-17 03:50:20'),
(7, 1, 'Inicio de sesión exitoso', '2026-03-17 15:07:27'),
(8, 1, 'Inicio de sesión exitoso', '2026-03-17 15:09:24'),
(9, 1, 'Inicio de sesión exitoso', '2026-03-18 03:45:42'),
(10, 1, 'Inicio de sesión exitoso', '2026-03-18 17:32:18'),
(11, 2, 'Inicio de sesión exitoso', '2026-03-18 17:34:20'),
(12, 2, 'Inicio de sesión exitoso', '2026-03-18 17:36:11'),
(13, 1, 'Inicio de sesión exitoso', '2026-03-18 17:36:34'),
(14, 1, 'Inicio de sesión exitoso', '2026-03-18 17:37:18'),
(15, 3, 'Inicio de sesión exitoso', '2026-03-18 17:39:56'),
(16, 1, 'Inicio de sesión exitoso', '2026-03-18 17:42:24'),
(17, 1, 'Inicio de sesión exitoso', '2026-03-19 04:27:45'),
(18, 2, 'Inicio de sesión exitoso', '2026-03-19 04:42:34'),
(19, 1, 'Inicio de sesión exitoso', '2026-03-19 04:44:22'),
(20, 1, 'Inicio de sesión exitoso', '2026-03-19 04:50:50'),
(21, 1, 'Inicio de sesión exitoso', '2026-03-19 04:54:18'),
(22, 1, 'Inicio de sesión exitoso', '2026-03-19 04:56:43'),
(23, 1, 'Inicio de sesión exitoso', '2026-03-19 23:00:23'),
(24, 1, 'Inicio de sesión exitoso', '2026-03-19 23:25:12'),
(25, 1, 'Inicio de sesión exitoso', '2026-03-19 23:26:10'),
(26, 1, 'Inicio de sesión exitoso', '2026-03-19 23:44:18'),
(27, 1, 'Inicio de sesión exitoso', '2026-03-19 23:51:02'),
(28, 1, 'Inicio de sesión exitoso', '2026-03-20 02:12:21'),
(29, 2, 'Inicio de sesión exitoso', '2026-03-20 02:13:16'),
(30, 1, 'Inicio de sesión exitoso', '2026-03-20 02:21:15'),
(31, 1, 'Inicio de sesión exitoso', '2026-03-20 02:21:47'),
(32, 1, 'Inicio de sesión exitoso', '2026-03-20 02:55:30'),
(33, 2, 'Inicio de sesión exitoso', '2026-03-20 03:06:33'),
(34, 1, 'Inicio de sesión exitoso', '2026-03-20 03:07:19'),
(35, 7, 'Inicio de sesión exitoso', '2026-03-20 03:08:38'),
(36, 1, 'Inicio de sesión exitoso', '2026-03-20 03:09:26'),
(37, 7, 'Inicio de sesión exitoso', '2026-03-20 03:40:00'),
(38, 1, 'Inicio de sesión exitoso', '2026-03-20 03:40:43'),
(39, 8, 'Inicio de sesión exitoso', '2026-03-20 03:41:49'),
(40, 8, 'Inicio de sesión exitoso', '2026-03-20 03:42:45'),
(41, 1, 'Inicio de sesión exitoso', '2026-03-20 14:14:06'),
(42, 1, 'Inicio de sesión exitoso', '2026-03-20 14:29:39'),
(43, 1, 'Inicio de sesión exitoso', '2026-03-20 14:34:47'),
(44, 1, 'Inicio de sesión exitoso', '2026-03-21 03:55:20'),
(45, 1, 'Inicio de sesión exitoso', '2026-03-21 03:59:37'),
(46, 11, 'Inicio de sesión exitoso', '2026-03-21 18:23:15'),
(47, 1, 'Inicio de sesión exitoso', '2026-03-25 02:08:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `precio`, `stock`, `id_usuario_creador`, `creado_por`, `fecha_creacion`) VALUES
(1, 'Laptop Gamer Pro ', NULL, 17888.00, 12, NULL, NULL, '2026-03-19 04:57:25'),
(2, 'Mouse Inalámbrico RGB', NULL, 25.50, 52, NULL, NULL, '2026-03-19 04:57:25'),
(3, 'Teclado Mecánico', NULL, 85.00, 30, NULL, NULL, '2026-03-19 04:57:25'),
(4, 'Monitor 24\" Full HD', NULL, 180.00, 9, NULL, NULL, '2026-03-19 04:57:25'),
(5, 'Auriculares con Micrófono', NULL, 45.99, 20, NULL, NULL, '2026-03-19 04:57:25'),
(7, 'Mouse Inalámbrico RGB', NULL, 400.00, 70, 1, NULL, '2026-03-20 02:06:08'),
(11, 'Julio Cesar ', NULL, 2423.40, 12136, 1, NULL, '2026-03-20 02:07:12'),
(12, 'Teclado', NULL, 3434.00, 34, 1, NULL, '2026-03-20 02:24:28'),
(13, 'AWS', NULL, 34543.00, 534542, 1, NULL, '2026-03-20 03:02:30'),
(14, 'Belem', NULL, 5.00, 3434, 1, NULL, '2026-03-21 18:04:26'),
(15, 'Dani', NULL, 4.00, 1212, 1, NULL, '2026-03-21 18:07:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Acceso total al sistema'),
(2, 'Usuario', 'Gestión completa de productos'),
(3, 'Vendedor', 'Consulta de productos y registro de ventas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `estado` enum('activo','bloqueado') DEFAULT 'activo',
  `intentos_fallidos` int(11) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `google_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `email`, `username`, `password`, `id_rol`, `estado`, `intentos_fallidos`, `fecha_creacion`, `google_id`) VALUES
(1, 'Montoya Romero', 'julio.messi.montoya@gmail.com', 'Julio', '$2y$10$s5nVhPGPGbVHjuTQRsvQf.451YNcDwWjKMb0pHWosH0e35c8LXxZ6', 1, 'activo', 0, '2026-03-17 03:08:01', NULL),
(2, 'Belem Martin', 'belem@gmail.com', 'Belem', '$2y$10$dKDimRgMzJ4ahjYvM6xDzuEWt6rcjjWFHU/eSNKnbmL1HqfjLdttm', 3, 'activo', 0, '2026-03-18 17:33:51', NULL),
(3, 'Dani Max Cabrera', 'daniel.joto@gmail.com', 'Dan', '$2y$10$6jZAgr81YYkhBTNGYlF8HORc9BWZMx98/nkjajtEvhnUsQvC9eVtC', 2, 'activo', 2, '2026-03-18 17:39:28', NULL),
(4, 'Fatima Barron', 'fatyma.dom@gmail.com', 'Faty', '$2y$10$9rWoszPFDVHnWH.floX2P.epiz2m4jAuOTAlTnOmDJ6gRf7GaZPhq', 2, 'activo', 0, '2026-03-19 04:12:07', NULL),
(5, 'Gael Ambrosio', 'gael@gmail.com', 'Silis', '$2y$10$8B5HC83AyPNfqGT9nbiJm.5hMSYujoZHvj1dxaoinl/b.PG0jxh1m', 2, 'bloqueado', 0, '2026-03-19 04:18:04', NULL),
(6, 'Noe', 'noe@gmail.com', 'Noe', '$2y$10$oEktcLtzwhKVlErnsEmo3esS1qYO0hwyagC22I/LmO.7v8uqfvGWi', 2, 'activo', 0, '2026-03-19 04:22:57', NULL),
(7, 'Jessi', 'jessica@gmail.com', 'jessi', '$2y$10$w7Tux.KiOGCKri6bewNIIuuKM5U0JNviG97F0gMacWmnPtcT831Yi', 2, 'activo', 0, '2026-03-20 03:08:20', NULL),
(8, 'Gato', 'gato@gmail.com', 'gat', '$2y$10$7KbOVz/UP8l39LLcvfg0zeOVQR3VnXKF.zOKYdM3k.Mj3xw7BeXYa', 3, 'activo', 0, '2026-03-20 03:41:36', NULL),
(9, 'Julio Montoy', 'jul10montoya1801@gmail.com', 'jul10montoya1801', NULL, 2, 'activo', 0, '2026-03-21 03:26:50', '116775014487564824515'),
(10, 'Johanan', 'johan@gmail.com', 'Johan', '$2y$10$vV2SKNVSpNGpLkJp1VWoPuO/rCWf4n93fFJjPim99tfFvl9v/aJ7a', 2, 'activo', 0, '2026-03-21 18:11:43', NULL),
(11, 'Abril lirut', 'abril@gmail.com', 'Abril', '$2y$10$1mfkvmTpAM7AYcntiYnVt.gl4J3oQXtp2b2I/6lumQkgK.sPTbtO.', 2, 'activo', 0, '2026-03-21 18:22:40', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha_venta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_producto`, `cantidad`, `total`, `fecha_venta`) VALUES
(1, 1, 1, 1250.00, '2026-03-18 23:00:31'),
(2, 2, 2, 51.00, '2026-03-18 23:00:31'),
(3, 3, 1, 85.00, '2026-03-18 23:00:31'),
(4, 5, 3, 137.97, '2026-03-18 23:00:31'),
(5, 5, 1, 45.99, '2026-03-19 17:04:59'),
(6, 5, 1, 45.99, '2026-03-19 17:05:02'),
(7, 5, 3, 137.97, '2026-03-19 17:23:22'),
(8, 1, 1, 17888.00, '2026-03-19 17:23:37'),
(9, 4, 1, 180.00, '2026-03-19 17:25:27'),
(10, 1, 1, 17888.00, '2026-03-19 20:12:45'),
(11, 1, 1, 17888.00, '2026-03-19 20:19:04'),
(12, 11, 1, 2423.40, '2026-03-19 20:19:06'),
(13, 11, 1, 2423.40, '2026-03-19 20:20:56'),
(14, 1, 1, 17888.00, '2026-03-19 20:32:12'),
(15, 1, 1, 17888.00, '2026-03-19 20:32:13'),
(16, 1, 1, 17888.00, '2026-03-19 20:32:13'),
(17, 1, 1, 17888.00, '2026-03-19 20:32:14'),
(18, 1, 1, 17888.00, '2026-03-19 20:32:14'),
(19, 1, 1, 17888.00, '2026-03-19 20:32:14'),
(20, 1, 1, 17888.00, '2026-03-19 20:32:15'),
(21, 1, 1, 17888.00, '2026-03-19 20:32:15'),
(22, 1, 1, 17888.00, '2026-03-19 21:06:40'),
(23, 1, 1, 17888.00, '2026-03-19 21:42:52'),
(24, 1, 1, 17888.00, '2026-03-19 21:42:53'),
(25, 1, 1, 17888.00, '2026-03-19 21:42:53'),
(26, 1, 1, 17888.00, '2026-03-19 21:42:54'),
(27, 13, 1, 34543.00, '2026-03-20 08:18:34'),
(28, 1, 1, 17888.00, '2026-03-20 22:00:04'),
(29, 1, 1, 17888.00, '2026-03-21 12:07:18'),
(30, 1, 1, 17888.00, '2026-03-21 12:07:21');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividad_logs`
--
ALTER TABLE `actividad_logs`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `logs_ibfk_1` (`id_usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `productos_ibfk_1` (`creado_por`),
  ADD KEY `fk_usuario_creador` (`id_usuario_creador`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `google_id` (`google_id`),
  ADD KEY `usuarios_ibfk_1` (`id_rol`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividad_logs`
--
ALTER TABLE `actividad_logs`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividad_logs`
--
ALTER TABLE `actividad_logs`
  ADD CONSTRAINT `actividad_logs_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_usuario_creador` FOREIGN KEY (`id_usuario_creador`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
