SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `wl_cursos` (
  `id` varchar(36) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `tema` varchar(1024) NOT NULL,
  `descricao` varchar(2048) DEFAULT NULL,
  `area_id` varchar(45) NOT NULL,
  `segmento_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `wl_cursos_sugestao` (
  `id` varchar(36) NOT NULL,
  `votos` int(11) NOT NULL,
  `area_id` varchar(45) NOT NULL,
  `segmento_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `wl_reviews` (
  `id` varchar(36) NOT NULL,
  `curso_id` varchar(36) NOT NULL,
  `qualidade` int(11) NOT NULL DEFAULT '0',
  `dificuldade` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `curso_id` (`curso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `wl_usuarios` (
  `id` varchar(40) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `sobrenome` varchar(60) NOT NULL,
  `email` varchar(80) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `segmento_id` varchar(100) NOT NULL,
  `area_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


ALTER TABLE `wl_reviews`
  ADD CONSTRAINT `wl_reviews_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `wl_cursos` (`id`);