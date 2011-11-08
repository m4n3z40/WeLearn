CREATE DATABASE welearn;

USE welearn;

CREATE TABLE wl_cursos_sugestao (
  id VARCHAR(36) PRIMARY KEY,
  votos INTEGER NOT NULL,
  area_id VARCHAR(45) NOT NULL,
  segmento_id VARCHAR(45) NOT NULL
);