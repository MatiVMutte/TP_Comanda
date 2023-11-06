-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-11-2023 a las 22:56:18
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id` int(11) NOT NULL,
  `rol` enum('Bartender','Cervecero','Cocinero','Mozo') NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1,
  `estado` enum('Presente','Ausente') NOT NULL DEFAULT 'Presente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id`, `rol`, `nombre`, `disponible`, `estado`) VALUES
(1, 'Cocinero', 'Juancito', 1, 'Presente'),
(2, 'Cocinero', 'Juancito', 1, 'Presente'),
(3, 'Cocinero', 'Nose', 0, 'Presente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `idMesa` int(11) NOT NULL,
  `nombreCliente` varchar(60) NOT NULL,
  `puntuacionMesa` int(11) NOT NULL DEFAULT 0,
  `puntuacionRestaurante` int(11) NOT NULL DEFAULT 0,
  `puntuacionMozo` int(11) NOT NULL DEFAULT 0,
  `puntuacionCocinero` int(11) NOT NULL DEFAULT 0,
  `descripcion` varchar(66) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `id` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `idMozo` int(11) NOT NULL,
  `estado` enum('Esperando','Comiendo','Pagando','Cerrado') NOT NULL DEFAULT 'Cerrado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`id`, `idPedido`, `idMozo`, `estado`) VALUES
(1, 1, 2, 'Esperando'),
(2, 4, 1, 'Esperando');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id` varchar(5) NOT NULL,
  `idMesa` int(11) NOT NULL,
  `nombreCliente` varchar(50) NOT NULL,
  `totalPrecio` decimal(10,0) NOT NULL DEFAULT 0,
  `estado` enum('Entregado','EnPreparacion','Cancelado') NOT NULL,
  `tiempoEstimado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id`, `idMesa`, `nombreCliente`, `totalPrecio`, `estado`, `tiempoEstimado`) VALUES
('deZQ0', 1, 'Matias', 500, 'EnPreparacion', 30),
('fUv0U', 1, 'Santi', 500, 'EnPreparacion', 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productopedido`
--

CREATE TABLE `productopedido` (
  `id` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `estado` enum('Realizado','Pendiente','EnProceso') NOT NULL DEFAULT 'Pendiente',
  `idPedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `precio` decimal(10,0) NOT NULL DEFAULT 0,
  `tipoProducto` enum('Bartender','Cervecero','Cocinero') NOT NULL,
  `tiempoMinutos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `tipoProducto`, `tiempoMinutos`) VALUES
(1, 'Doritos', 99, 'Cervecero', 1),
(2, 'Milanesa', 100, 'Bartender', 5),
(4, 'Fideos', 150, 'Cocinero', 5),
(5, 'Holaaa', 99, 'Cocinero', 30),
(6, 'Lauty', 99, 'Cocinero', 30),
(7, 'Matute', 99, 'Cocinero', 30),
(8, 'Matute', 99, 'Cocinero', 30);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productopedido`
--
ALTER TABLE `productopedido`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productopedido`
--
ALTER TABLE `productopedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
