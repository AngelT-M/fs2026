CREATE DATABASE ejercicio_tokens CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ejercicio_tokens;

CREATE TABLE usuarios (
  usuario_id INT AUTO_INCREMENT PRIMARY KEY,
  usuario    VARCHAR(50)  NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL          -- hash, nunca la contraseña en texto
);

CREATE TABLE tokens (
  token_id   INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT         NOT NULL,
  token      CHAR(64)    NOT NULL UNIQUE,
  expira_en  DATETIME    NOT NULL,
  activo     TINYINT(1)  NOT NULL DEFAULT 1,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(usuario_id)
);

CREATE TABLE tareas (
  tarea_id INT AUTO_INCREMENT PRIMARY KEY,
  titulo   VARCHAR(100) NOT NULL
);

-- Usuario de prueba: ana / clave123
INSERT INTO usuarios (usuario, contrasena)
VALUES ('ana', '$2y$10$fGHLTpkw8wgJg8XkarwxzO4moxsR/ICfaeCiDgthaizhQac9LTHPi');

INSERT INTO tareas (titulo) VALUES ('Diseñar el login'), ('Probar el token');