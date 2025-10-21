CREATE TABLE usuarios (
  id_usuario int NOT NULL,
  nombre varchar,
  email varchar,
  contrasena varchar,
  rol varchar,
  PRIMARY KEY (id_usuario),
  UNIQUE KEY email (email)
);

CREATE TABLE direcciones_envio (
  id_direccion int NOT NULL,
  id_usuario int,
  nombre_completo varchar,
  telefono varchar,
  direccion_linea1 varchar,
  direccion_linea2 varchar,
  ciudad varchar,
  estado_provincia varchar,
  codigo_postal varchar,
  pais varchar,
  es_predeterminada boolean,
  PRIMARY KEY (id_direccion),
  KEY id_usuario (id_usuario),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE notificaciones (
  id_notificacion int NOT NULL,
  id_usuario int,
  tipo varchar,
  mensaje text,
  fecha datetime,
  leido boolean,
  PRIMARY KEY (id_notificacion),
  KEY id_usuario (id_usuario),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE categorias (
  id_categoria int NOT NULL,
  nombre varchar,
  PRIMARY KEY (id_categoria)
);

CREATE TABLE productos (
  id_producto int NOT NULL,
  nombre varchar,
  descripcion text,
  precio decimal,
  stock int,
  imagen_url varchar,
  id_categoria int,
  PRIMARY KEY (id_producto),
  KEY id_categoria (id_categoria),
  FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

CREATE TABLE favoritos (
  id_favorito int NOT NULL,
  id_usuario int,
  id_producto int,
  fecha_agregado datetime,
  PRIMARY KEY (id_favorito),
  KEY id_usuario (id_usuario),
  KEY id_producto (id_producto),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE alertas_stock (
  id_alerta int NOT NULL,
  id_producto int,
  cantidad_minima int,
  activada boolean,
  PRIMARY KEY (id_alerta),
  KEY id_producto (id_producto),
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE proveedores (
  id_proveedor int NOT NULL,
  nombre varchar,
  contacto varchar,
  telefono varchar,
  email varchar,
  direccion text,
  PRIMARY KEY (id_proveedor)
);

CREATE TABLE compras_proveedor (
  id_compra int NOT NULL,
  id_proveedor int,
  fecha datetime,
  total decimal,
  estado varchar,
  PRIMARY KEY (id_compra),
  KEY id_proveedor (id_proveedor),
  FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor)
);

CREATE TABLE compras_detalles (
  id_detalle int NOT NULL,
  id_compra int,
  id_producto int,
  cantidad int,
  precio_unitario decimal,
  PRIMARY KEY (id_detalle),
  KEY id_compra (id_compra),
  KEY id_producto (id_producto),
  FOREIGN KEY (id_compra) REFERENCES compras_proveedor(id_compra),
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE inventario_movimientos (
  id_movimiento int NOT NULL,
  id_producto int,
  tipo_movimiento varchar,
  cantidad int,
  fecha datetime,
  referencia varchar,
  comentario text,
  PRIMARY KEY (id_movimiento),
  KEY id_producto (id_producto),
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE carrito (
  id_carrito int NOT NULL,
  id_usuario int,
  fecha_creacion datetime,
  PRIMARY KEY (id_carrito),
  KEY id_usuario (id_usuario),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE carrito_items (
  id_item int NOT NULL,
  id_carrito int,
  id_producto int,
  cantidad int,
  PRIMARY KEY (id_item),
  KEY id_carrito (id_carrito),
  KEY id_producto (id_producto),
  FOREIGN KEY (id_carrito) REFERENCES carrito(id_carrito),
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE pedidos (
  id_pedido int NOT NULL,
  id_usuario int,
  fecha datetime,
  estado varchar,
  total decimal,
  PRIMARY KEY (id_pedido),
  KEY id_usuario (id_usuario),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE pedido_detalles (
  id_detalle int NOT NULL,
  id_pedido int,
  id_producto int,
  cantidad int,
  precio_unitario decimal,
  PRIMARY KEY (id_detalle),
  KEY id_pedido (id_pedido),
  KEY id_producto (id_producto),
  FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE pagos (
  id_pago int NOT NULL,
  id_pedido int,
  fecha_pago datetime,
  metodo varchar,
  estado varchar,
  PRIMARY KEY (id_pago),
  KEY id_pedido (id_pedido),
  FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido)
);

CREATE TABLE seguimiento_pedidos (
  id_seguimiento int NOT NULL,
  id_pedido int,
  estado varchar,
  fecha datetime,
  comentario text,
  PRIMARY KEY (id_seguimiento),
  KEY id_pedido (id_pedido),
  FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido)
);

CREATE TABLE dashboard_reportes (
  id_reporte int NOT NULL,
  tipo varchar,
  fecha_generacion datetime,
  datos text,
  PRIMARY KEY (id_reporte)
);