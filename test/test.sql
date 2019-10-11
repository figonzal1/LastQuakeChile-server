-- Creando Tabla
DROP TABLE IF EXISTS `quakes`;
CREATE TABLE `quakes` (
  `quakes_id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_local` datetime NOT NULL,
  `fecha_utc` datetime NULL,
  `ciudad` varchar(45) NULL,
  `referencia` varchar(255) CHARACTER SET utf32 NULL,
  `magnitud` decimal(5,1) NULL,
  `escala` char(2) NULL,
  `sensible` tinyint(1) NULL,
  `latitud` varchar(100) NOT NULL,
  `longitud` varchar(100) NULL,
  `profundidad` float NULL,
  `agencia` char(3) NULL,
  `imagen` varchar(255) NOT NULL,
  `estado` varchar(30) NULL,
  PRIMARY KEY (`quakes_id`,`fecha_local`,`latitud`,`imagen`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;