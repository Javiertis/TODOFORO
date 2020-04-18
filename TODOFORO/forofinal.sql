CREATE DATABASE  IF NOT EXISTS `fororicarteje2`;
USE `fororicarteje2`;

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `nickname` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `pass` varchar(74) NOT NULL,
  `nivelacceso` varchar(45) NOT NULL,
  `creado` datetime NOT NULL,
  `descripcion` tinytext,
  PRIMARY KEY (`nickname`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `nickname_UNIQUE` (`nickname`)
);

DROP TABLE IF EXISTS `temas`;
CREATE TABLE `temas` (
  `idtema` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `fechacreacion` datetime NOT NULL,
  `descripcion` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`idtema`)
);

DROP TABLE IF EXISTS `hilos`;
CREATE TABLE `hilos` (
  `idhilo` int(11) NOT NULL AUTO_INCREMENT,
  `creador` varchar(20) DEFAULT NULL,
  `idtema` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `fechacreacion` datetime NOT NULL,
  `descripcion` varchar(80) DEFAULT NULL,
  `cerrado` enum('N','Y') DEFAULT NULL,
  PRIMARY KEY (`idhilo`),
  KEY `fk_hilos_temas1_idx` (`idtema`),
  KEY `fk_hilos_usuarios1_idx` (`creador`),
  CONSTRAINT `fk_hilos_temas1` FOREIGN KEY (`idtema`) REFERENCES `temas` (`idtema`) ON DELETE CASCADE,
  CONSTRAINT `fk_hilos_usuarios1` FOREIGN KEY (`creador`) REFERENCES `usuarios` (`nickname`) ON DELETE SET NULL
);

DROP TABLE IF EXISTS `comentarios`;
CREATE TABLE `comentarios` (
  `idcomentario` int(11) NOT NULL AUTO_INCREMENT,
  `idhilo` int(11) NOT NULL,
  `creador` varchar(20) DEFAULT NULL,
  `fechacreacion` datetime NOT NULL,
  `comentario` text NOT NULL,
  PRIMARY KEY (`idcomentario`),
  KEY `fk_comentarios_usuarios_idx` (`creador`),
  KEY `fk_comentarios_hilos1_idx` (`idhilo`),
  CONSTRAINT `fk_comentarios_hilos1` FOREIGN KEY (`idhilo`) REFERENCES `hilos` (`idhilo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_comentarios_usuarios` FOREIGN KEY (`creador`) REFERENCES `usuarios` (`nickname`) ON DELETE SET NULL ON UPDATE CASCADE
);