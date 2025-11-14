USE proyecto_suplementos;

INSERT INTO usuarios (nombre, email, contrasena, rol)
VALUES ('Usuario Prueba', 'prueba@correo.com', '123456', 'admin');

INSERT INTO usuarios (nombre, email, contrasena, rol)
VALUES ('Truman', 'truman@test.com', '123456', 'admin');

INSERT INTO usuarios (nombre, email, contrasena, rol)
VALUES ('Truman Castañeda', 'trumanhernan@gmail.com', '123456', 'admin');


select * from usuarios;

CREATE TABLE tokens_recuperacion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL,
  token VARCHAR(64) NOT NULL,
  expiracion DATETIME NOT NULL
);

SELECT * FROM tokens_recuperacion;

