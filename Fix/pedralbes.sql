-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-06-2014 a las 09:54:17
-- Versión del servidor: 5.6.12-log
-- Versión de PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `pedralbes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_menor`
--

CREATE TABLE IF NOT EXISTS `caja_menor` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Valor` bigint(20) DEFAULT NULL,
  `Tipo` varchar(7) DEFAULT NULL,
  `Usuario` varchar(100) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora` time DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_menor_detalles`
--

CREATE TABLE IF NOT EXISTS `caja_menor_detalles` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Consecutivo` bigint(20) NOT NULL,
  `Valor` bigint(20) DEFAULT NULL,
  `Tipo` varchar(7) DEFAULT NULL,
  `Detalle` longtext,
  `Usuario` varchar(100) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora` time DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_Caja_Menor_Detalles_Caja_Menor1_idx` (`Consecutivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes_informacion`
--

CREATE TABLE IF NOT EXISTS `clientes_informacion` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Cedula` varchar(50) DEFAULT NULL,
  `Nombres` varchar(100) DEFAULT NULL,
  `Apellidos` varchar(100) DEFAULT NULL,
  `Telefono` varchar(100) DEFAULT NULL,
  `Correo` varchar(200) DEFAULT NULL,
  `Fidelizacion` varchar(8) DEFAULT NULL,
  `Descuento` varchar(8) DEFAULT NULL,
  `Fecha` datetime DEFAULT NULL,
  `Usuario` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura_proveedor`
--

CREATE TABLE IF NOT EXISTS `detalle_factura_proveedor` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `FacturaProveedor` bigint(20) NOT NULL,
  `ReferenciaInventario` int(10) DEFAULT NULL,
  `ValorNeto` bigint(20) DEFAULT NULL,
  `ValorIVA` bigint(20) DEFAULT NULL,
  `ValorTotal` bigint(20) DEFAULT NULL,
  `CantidadProducto` int(10) DEFAULT NULL,
  `CantidadInvActual` int(10) DEFAULT NULL,
  `EstadoInventario` varchar(8) DEFAULT NULL,
  `Usuario` varchar(100) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora` time DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_Detalle_Factura_Proveedor_Factura_Proveedor1_idx` (`FacturaProveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE IF NOT EXISTS `facturas` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Fecha` date DEFAULT NULL,
  `Hora` time DEFAULT NULL,
  `Cliente` bigint(20) DEFAULT NULL,
  `ValorNeto` bigint(20) DEFAULT NULL,
  `ValorIVA` bigint(20) DEFAULT NULL,
  `ValorTotal` bigint(20) DEFAULT NULL,
  `PagoEfectivo` bigint(20) DEFAULT NULL,
  `DevolucionEfectivo` bigint(20) DEFAULT NULL,
  `Autorizacion` varchar(100) DEFAULT NULL,
  `Estado` varchar(8) DEFAULT NULL,
  `Descuento` varchar(8) DEFAULT NULL,
  `Usuario` varchar(100) DEFAULT NULL,
  `Factura_Venta` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas_detalle`
--

CREATE TABLE IF NOT EXISTS `facturas_detalle` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `NumeroFactura` bigint(20) DEFAULT NULL,
  `Referencia` int(10) DEFAULT NULL,
  `Cantidad` int(10) DEFAULT NULL,
  `ValorNeto` bigint(20) DEFAULT NULL,
  `ValorIVA` bigint(20) DEFAULT NULL,
  `ValorTotal` bigint(20) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora` time DEFAULT NULL,
  `Descuento` varchar(8) DEFAULT NULL,
  `Usuario` varchar(100) DEFAULT NULL,
  `Factura_Venta` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_anulaciones`
--

CREATE TABLE IF NOT EXISTS `factura_anulaciones` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Fecha` date DEFAULT NULL,
  `Hora` time DEFAULT NULL,
  `Usuario` varchar(100) DEFAULT NULL,
  `FacturaAnulada` bigint(20) DEFAULT NULL,
  `FacturaNueva` bigint(20) DEFAULT NULL,
  `TipoAnulacion` varchar(100) DEFAULT NULL,
  `Factura_Venta` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_proveedor`
--

CREATE TABLE IF NOT EXISTS `factura_proveedor` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Numero` varchar(255) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `ValorNeto` bigint(20) DEFAULT NULL,
  `ValorIVA` bigint(20) DEFAULT NULL,
  `ValorTotal` bigint(20) DEFAULT NULL,
  `Usuario` varchar(100) DEFAULT NULL,
  `FechaIngreso` date DEFAULT NULL,
  `HoraIngreso` time DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_proveedor_temporal`
--

CREATE TABLE IF NOT EXISTS `factura_proveedor_temporal` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Id de Tabla Temporal',
  `IdFactura` bigint(20) NOT NULL COMMENT 'Id de la Factura',
  `Referencia` int(10) DEFAULT NULL COMMENT 'Referencia',
  `Categoria` varchar(100) DEFAULT NULL,
  `SubCategoria` varchar(100) DEFAULT NULL,
  `Color` varchar(200) DEFAULT NULL,
  `Material` varchar(200) DEFAULT NULL,
  `Talla` varchar(200) DEFAULT NULL,
  `Cantidad` int(10) DEFAULT NULL,
  `Descripcion_Venta` longtext,
  `Valor_Minimo` int(10) DEFAULT NULL,
  `Valor_bodega` int(10) DEFAULT NULL,
  `Valor_Almacen` int(10) DEFAULT NULL,
  `Estado` varchar(100) DEFAULT NULL,
  `Accion` varchar(100) DEFAULT NULL,
  `Usuario` varchar(100) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora` time DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_Factura_Proveedor_Temporal_Factura_Proveedor1_idx` (`IdFactura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE IF NOT EXISTS `inventario` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Id del Inventario',
  `Referencia` int(10) DEFAULT NULL COMMENT 'Referencia',
  `Proveedor` bigint(20) NOT NULL COMMENT 'Id Proveedor',
  `Categoria` varchar(100) DEFAULT NULL COMMENT 'Categoria del Producto',
  `SubCategoria` varchar(100) DEFAULT NULL COMMENT 'Sub-Categoria',
  `Color` varchar(200) DEFAULT NULL COMMENT 'Color del Producto',
  `Material` varchar(200) DEFAULT NULL COMMENT 'Material del Producto',
  `Talla` varchar(200) DEFAULT NULL COMMENT 'Talla del Producto',
  `Cantidad` bigint(10) DEFAULT NULL COMMENT 'Cantidad Disponible',
  `Descripcion_Venta` longtext COMMENT 'Descripción del Producto',
  `Valor_Minimo` int(10) DEFAULT NULL COMMENT 'Valor Minimo de Venta',
  `Valor_bodega` int(10) DEFAULT NULL COMMENT 'Valor Intermedio de Venta',
  `Valor_Almacen` int(10) DEFAULT NULL COMMENT 'Valor Final de Venta',
  `Estado` varchar(100) DEFAULT NULL COMMENT 'Estado del Producto',
  `ActivoWeb` varchar(8) DEFAULT 'INACTIVO' COMMENT 'Activo para la Web',
  PRIMARY KEY (`Id`),
  KEY `fk_Inventario_Proveedores1_idx` (`Proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_historial`
--

CREATE TABLE IF NOT EXISTS `inventario_historial` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Fecha` date DEFAULT NULL,
  `Hora` time DEFAULT NULL,
  `Usuario` varchar(255) DEFAULT NULL,
  `Cantidad` bigint(20) DEFAULT NULL,
  `Inventario_Id` bigint(20) NOT NULL,
  `Tipo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_Inventario_Historial_Inventario1_idx` (`Inventario_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_imagenes`
--

CREATE TABLE IF NOT EXISTS `inventario_imagenes` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Referencia` int(10) DEFAULT NULL,
  `Nombre` varchar(200) DEFAULT NULL,
  `Estado` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_rating`
--

CREATE TABLE IF NOT EXISTS `inventario_rating` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Referencia` int(10) DEFAULT NULL,
  `Usuario` varchar(100) DEFAULT NULL,
  `Calificacion` int(1) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE IF NOT EXISTS `permisos` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) DEFAULT NULL,
  `Detalle` longtext,
  `Estado` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`Id`, `Nombre`, `Detalle`, `Estado`) VALUES
(1, 'Administrador', '{"Central":true,"Proveedor":true,"Facturacion":true}', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE IF NOT EXISTS `proveedores` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) DEFAULT NULL,
  `Estado` varchar(8) DEFAULT NULL,
  `Telefono_1` varchar(100) DEFAULT NULL,
  `Telefono_2` varchar(100) DEFAULT NULL,
  `Direccion` varchar(200) DEFAULT NULL,
  `Contacto_1` varchar(200) DEFAULT NULL,
  `Contacto_2` varchar(200) DEFAULT NULL,
  `Comentario` text,
  `Usuario` varchar(100) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora` time DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`Id`, `Nombre`, `Estado`, `Telefono_1`, `Telefono_2`, `Direccion`, `Contacto_1`, `Contacto_2`, `Comentario`, `Usuario`, `Fecha`, `Hora`) VALUES
(9, 'LOTUS', 'ACTIVO', '0000', '', 'GRAN SAN LOCAL  2009', 'FELIPE', '', 'SACOS', 'Blind', '2014-01-06', '14:36:29'),
(10, 'PIN UPS', 'ACTIVO', '0000', '', 'GRAN SAN LOCAL 2089', 'DIANA', '', 'CHAQUETAS DRIL', 'Blind', '2014-01-06', '14:37:23'),
(11, 'MOST', 'ACTIVO', '3363278', '', 'GRAN SAN LOCAL 2189', 'SIN CONTACTO', '', 'JEANS DAMA', 'Blind', '2014-01-06', '14:40:29'),
(12, 'SHAPELY', 'ACTIVO', '3521235', '', 'GRAN SAN LOCAL 2151', 'LORENA', '', 'JEANS DAMA', 'Blind', '2014-01-06', '14:41:25'),
(13, 'GANGSTERS', 'ACTIVO', '3521246', '', 'GRAN SAN LOCAL 2153', 'SIN CONTACTO', '', 'JEAN DIESEL', 'Blind', '2014-01-06', '14:42:31'),
(14, 'SYNTHETIC', 'ACTIVO', '3114473980', '', 'GRAN SAN LOCAL 3303', 'SANDRA', '', 'ESTAMPADOS', 'Blind', '2014-01-06', '14:43:50'),
(15, 'DVIO', 'ACTIVO', '3520916', '', 'GRAN SAN LOCAL 2003', 'SIN CONTACTO', '', 'CHAQUETAS Y CHALECOS EN JEAN', 'Blind', '2014-01-06', '14:45:44'),
(16, 'XIXMO', 'ACTIVO', '2845986', '', 'GRAN SAN LOCAL 2227', 'PILAR', '', 'JEANS', 'Blind', '2014-01-06', '14:46:25'),
(17, 'MODANY', 'ACTIVO', '3521330', '', 'GRAN SAN LOCAL 2213', 'SANDRA', '', 'BLUSAS', 'Blind', '2014-01-06', '14:48:30'),
(18, 'PROVEEDOR 2013', 'ACTIVO', '0000', '', 'GRAN SAN', 'SIN CONTACTO', '', '', 'Blind', '2014-01-06', '14:49:29'),
(19, 'ESPORADICO', 'ACTIVO', '0000', '', 'SIN DIRECCION', 'SIN CONTACTO', '', '', 'Blind', '2014-01-06', '14:50:22'),
(20, 'PANDEMIA', 'ACTIVO', '3213865278', '', 'GRAN SAN LOCAL 1046', 'MARTHA', '', 'MARCAS', 'Blind', '2014-01-06', '15:57:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Usuario` varchar(100) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Apellidos` varchar(100) DEFAULT NULL,
  `Estado` varchar(8) DEFAULT NULL,
  `Permisos` bigint(20) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_Usuarios_Permisos_idx` (`Permisos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Id`, `Usuario`, `Password`, `Nombre`, `Apellidos`, `Estado`, `Permisos`) VALUES
(1, 'Fix', 'cb40dd606cfa58af70d3cef46feb91e38b9c78ba', 'Alejo', 'Montenegro', 'ACTIVO', 1),
(2, 'Blind', 'cb40dd606cfa58af70d3cef46feb91e38b9c78ba', 'Marcela', 'Cabrera', 'ACTIVO', 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `caja_menor_detalles`
--
ALTER TABLE `caja_menor_detalles`
  ADD CONSTRAINT `fk_Caja_Menor_Detalles_Caja_Menor1` FOREIGN KEY (`Consecutivo`) REFERENCES `caja_menor` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_factura_proveedor`
--
ALTER TABLE `detalle_factura_proveedor`
  ADD CONSTRAINT `fk_Detalle_Factura_Proveedor_Factura_Proveedor1` FOREIGN KEY (`FacturaProveedor`) REFERENCES `factura_proveedor` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `factura_proveedor_temporal`
--
ALTER TABLE `factura_proveedor_temporal`
  ADD CONSTRAINT `fk_Factura_Proveedor_Temporal_Factura_Proveedor1` FOREIGN KEY (`IdFactura`) REFERENCES `factura_proveedor` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `fk_Inventario_Proveedores1` FOREIGN KEY (`Proveedor`) REFERENCES `proveedores` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `inventario_historial`
--
ALTER TABLE `inventario_historial`
  ADD CONSTRAINT `fk_Inventario_Historial_Inventario1` FOREIGN KEY (`Inventario_Id`) REFERENCES `inventario` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_Usuarios_Permisos` FOREIGN KEY (`Permisos`) REFERENCES `permisos` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
