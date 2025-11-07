
USE proyecto_suplementos;

CREATE TABLE usuarios (
  id_usuario INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(255),
  email VARCHAR(255),
  contrasena VARCHAR(255),
  rol VARCHAR(50),
  PRIMARY KEY (id_usuario),
  UNIQUE KEY email (email)
);

CREATE TABLE direcciones_envio (
  id_direccion INT NOT NULL AUTO_INCREMENT,
  id_usuario INT,
  nombre_completo VARCHAR(255),
  telefono VARCHAR(20),
  direccion_linea1 VARCHAR(255),
  direccion_linea2 VARCHAR(255),
  ciudad VARCHAR(100),
  estado_provincia VARCHAR(100),
  codigo_postal VARCHAR(20),
  pais VARCHAR(100),
  es_predeterminada BOOLEAN,
  PRIMARY KEY (id_direccion),
  KEY id_usuario (id_usuario),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE notificaciones (
  id_notificacion INT NOT NULL AUTO_INCREMENT,
  id_usuario INT,
  tipo VARCHAR(50),
  mensaje TEXT,
  fecha DATETIME,
  leido BOOLEAN,
  PRIMARY KEY (id_notificacion),
  KEY id_usuario (id_usuario),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE categorias (
  id_categoria INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(255),
  PRIMARY KEY (id_categoria)
);

CREATE TABLE productos (
  id_producto INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(255),
  descripcion TEXT,
  precio DECIMAL(10,2),
  stock INT,
  imagen_url VARCHAR(500),
  id_categoria INT,
  PRIMARY KEY (id_producto),
  KEY id_categoria (id_categoria),
  FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

CREATE TABLE favoritos (
  id_favorito INT NOT NULL AUTO_INCREMENT,
  id_usuario INT,
  id_producto INT,
  fecha_agregado DATETIME,
  PRIMARY KEY (id_favorito),
  KEY id_usuario (id_usuario),
  KEY id_producto (id_producto),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE alertas_stock (
  id_alerta INT NOT NULL AUTO_INCREMENT,
  id_producto INT,
  cantidad_minima INT,
  activada BOOLEAN,
  PRIMARY KEY (id_alerta),
  KEY id_producto (id_producto),
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE proveedores (
  id_proveedor INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(255),
  contacto VARCHAR(255),
  telefono VARCHAR(20),
  email VARCHAR(255),
  direccion TEXT,
  PRIMARY KEY (id_proveedor)
);

CREATE TABLE compras_proveedor (
  id_compra INT NOT NULL AUTO_INCREMENT,
  id_proveedor INT,
  fecha DATETIME,
  total DECIMAL(10,2),
  estado VARCHAR(50),
  PRIMARY KEY (id_compra),
  KEY id_proveedor (id_proveedor),
  FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor)
);

CREATE TABLE compras_detalles (
  id_detalle INT NOT NULL AUTO_INCREMENT,
  id_compra INT,
  id_producto INT,
  cantidad INT,
  precio_unitario DECIMAL(10,2),
  PRIMARY KEY (id_detalle),
  KEY id_compra (id_compra),
  KEY id_producto (id_producto),
  FOREIGN KEY (id_compra) REFERENCES compras_proveedor(id_compra),
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE inventario_movimientos (
  id_movimiento INT NOT NULL AUTO_INCREMENT,
  id_producto INT,
  tipo_movimiento VARCHAR(50),
  cantidad INT,
  fecha DATETIME,
  referencia VARCHAR(255),
  comentario TEXT,
  PRIMARY KEY (id_movimiento),
  KEY id_producto (id_producto),
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE carrito (
  id_carrito INT NOT NULL AUTO_INCREMENT,
  id_usuario INT,
  fecha_creacion DATETIME,
  PRIMARY KEY (id_carrito),
  KEY id_usuario (id_usuario),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE carrito_items (
  id_item INT NOT NULL AUTO_INCREMENT,
  id_carrito INT,
  id_producto INT,
  cantidad INT,
  PRIMARY KEY (id_item),
  KEY id_carrito (id_carrito),
  KEY id_producto (id_producto),
  FOREIGN KEY (id_carrito) REFERENCES carrito(id_carrito),
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE pedidos (
  id_pedido INT NOT NULL AUTO_INCREMENT,
  id_usuario INT,
  fecha DATETIME,
  estado VARCHAR(50),
  total DECIMAL(10,2),
  PRIMARY KEY (id_pedido),
  KEY id_usuario (id_usuario),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE pedido_detalles (
  id_detalle INT NOT NULL AUTO_INCREMENT,
  id_pedido INT,
  id_producto INT,
  cantidad INT,
  precio_unitario DECIMAL(10,2),
  PRIMARY KEY (id_detalle),
  KEY id_pedido (id_pedido),
  KEY id_producto (id_producto),
  FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE pagos (
  id_pago INT NOT NULL AUTO_INCREMENT,
  id_pedido INT,
  fecha_pago DATETIME,
  metodo VARCHAR(50),
  estado VARCHAR(50),
  PRIMARY KEY (id_pago),
  KEY id_pedido (id_pedido),
  FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido)
);

CREATE TABLE seguimiento_pedidos (
  id_seguimiento INT NOT NULL AUTO_INCREMENT,
  id_pedido INT,
  estado VARCHAR(50),
  fecha DATETIME,
  comentario TEXT,
  PRIMARY KEY (id_seguimiento),
  KEY id_pedido (id_pedido),
  FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido)
);

CREATE TABLE dashboard_reportes (
  id_reporte INT NOT NULL AUTO_INCREMENT,
  tipo VARCHAR(50),
  fecha_generacion DATETIME,
  datos TEXT,
  PRIMARY KEY (id_reporte)
);