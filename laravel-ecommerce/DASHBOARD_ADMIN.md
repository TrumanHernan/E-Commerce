# Dashboard Admin - NutriShop

## Implementación Completada

Se ha migrado exitosamente el dashboard administrativo original al sistema Laravel, manteniendo el diseño y agregando funcionalidad completa de CRUD.

## Características Implementadas

### 1. Dashboard Principal (`/admin/dashboard`)
- **Estadísticas en tiempo real:**
  - Total de productos
  - Valor del inventario (calculado)
  - Productos con stock bajo (< 10 unidades)
  - Proveedores activos

- **Tablas informativas:**
  - Productos con stock bajo (top 5)
  - Compras recientes (últimas 5)
  - Productos más vendidos (top 10)

### 2. Gestión de Productos (`/admin/productos`)
- Listar todos los productos
- Crear nuevos productos
- Editar productos existentes
- Eliminar productos
- Control de stock automático

### 3. Gestión de Compras (`/admin/compras`)
**CRUD Completo:**
- ✅ **Create:** Registrar nuevas compras a proveedores
- ✅ **Read:** Ver listado de todas las compras
- ✅ **Update:** Editar compras existentes
- ✅ **Delete:** Eliminar compras

**Características especiales:**
- Actualización automática de stock al registrar compra
- Cálculo automático del total (cantidad × precio unitario)
- Reversión de stock al editar o eliminar compras
- Validación de datos completa
- Interfaz intuitiva con selects de proveedores y productos

### 4. Gestión de Proveedores (`/admin/proveedores`)
**CRUD Completo:**
- ✅ **Create:** Registrar nuevos proveedores
- ✅ **Read:** Ver listado de proveedores
- ✅ **Update:** Editar información de proveedores
- ✅ **Delete:** Eliminar proveedores

**Campos:**
- Nombre del contacto
- Empresa
- Email (único)
- Teléfono
- Dirección
- Ciudad
- País
- Notas
- Estado (Activo/Inactivo)

## Estructura de Archivos

### Modelos
- `app/Models/Compra.php` - Modelo de compras con relaciones
- `app/Models/Proveedor.php` - Modelo de proveedores
- `app/Models/Producto.php` - Actualizado con relación a compras

### Controladores
- `app/Http/Controllers/AdminDashboardController.php` - Estadísticas del dashboard
- `app/Http/Controllers/CompraController.php` - CRUD de compras
- `app/Http/Controllers/ProveedorController.php` - CRUD de proveedores

### Vistas
- `resources/views/layouts/admin.blade.php` - Layout con sidebar
- `resources/views/admin/dashboard.blade.php` - Dashboard principal
- `resources/views/admin/compras/` - Vistas de compras (index, create, edit)
- `resources/views/admin/proveedores/` - Vistas de proveedores (index, create, edit)

### Assets
- `public/css/dashboard.css` - Estilos del dashboard original
- `public/js/dashboard.js` - JavaScript del dashboard (para futuras mejoras)

## Base de Datos

### Tabla `compras`
```sql
- id
- fecha
- proveedor_id (FK -> proveedors)
- producto_id (FK -> productos)
- cantidad
- precio_unitario
- total (calculado)
- notas
- created_at
- updated_at
```

## Rutas Disponibles

### Admin Dashboard
- `GET /admin/dashboard` - Dashboard principal

### Productos
- `GET /admin/productos` - Listar productos
- `GET /admin/productos/create` - Formulario crear
- `POST /admin/productos` - Guardar producto
- `GET /admin/productos/{id}/edit` - Formulario editar
- `PUT /admin/productos/{id}` - Actualizar producto
- `DELETE /admin/productos/{id}` - Eliminar producto

### Compras
- `GET /admin/compras` - Listar compras
- `GET /admin/compras/create` - Formulario crear
- `POST /admin/compras` - Guardar compra
- `GET /admin/compras/{id}/edit` - Formulario editar
- `PUT /admin/compras/{id}` - Actualizar compra
- `DELETE /admin/compras/{id}` - Eliminar compra

### Proveedores
- `GET /admin/proveedores` - Listar proveedores
- `GET /admin/proveedores/create` - Formulario crear
- `POST /admin/proveedores` - Guardar proveedor
- `GET /admin/proveedores/{id}/edit` - Formulario editar
- `PUT /admin/proveedores/{id}` - Actualizar proveedor
- `DELETE /admin/proveedores/{id}` - Eliminar proveedor

## Navegación del Sidebar

El sidebar incluye:
1. Dashboard
2. Lista de Productos
3. Agregar Productos
4. Compras (NUEVO)
5. Proveedores (NUEVO)
6. Ver Sitio Web
7. Cerrar Sesión

## Funcionalidades Especiales

### Control de Stock Automático
- Al registrar una compra: `stock += cantidad`
- Al editar una compra: revierte stock anterior y aplica nuevo
- Al eliminar una compra: revierte el stock agregado

### Validaciones
- Todos los formularios tienen validación de Laravel
- Mensajes de error específicos por campo
- Prevención de duplicados (email de proveedor único)
- Confirmación antes de eliminar

### Mensajes Flash
- Éxito al crear, actualizar o eliminar
- Errores específicos en caso de fallos
- Alertas visuales con iconos

## Uso

### Acceso al Dashboard Admin
1. Inicia sesión con usuario administrador (rol='admin')
2. Visita: `http://localhost:8000/admin/dashboard`

### Registrar una Compra
1. Ve a "Compras" en el sidebar
2. Click en "Nueva Compra"
3. Llena el formulario:
   - Fecha de compra
   - Selecciona proveedor
   - Selecciona producto
   - Ingresa cantidad
   - Ingresa precio unitario
   - Notas opcionales
4. Click en "Guardar Compra"
5. El stock del producto se actualiza automáticamente

### Gestionar Proveedores
1. Ve a "Proveedores" en el sidebar
2. Puedes:
   - Ver lista completa de proveedores
   - Agregar nuevos proveedores
   - Editar información existente
   - Activar/desactivar proveedores
   - Eliminar proveedores (si no tienen compras asociadas)

## Próximas Mejoras Sugeridas

1. **Dashboard dinámico con JavaScript**
   - Gráficas de ventas
   - Estadísticas en tiempo real
   - Filtros por fecha

2. **Reportes**
   - Reporte de compras por proveedor
   - Reporte de productos más comprados
   - Exportar a PDF/Excel

3. **Inventario avanzado**
   - Alertas automáticas de stock bajo
   - Histórico de movimientos
   - Proyección de necesidades

4. **Gestión de pedidos de clientes**
   - Ver pedidos realizados
   - Cambiar estado de pedidos
   - Imprimir comprobantes

## Notas Técnicas

- Laravel 12
- Bootstrap 5.3.0
- Bootstrap Icons 1.10.5
- Base de datos: MySQL
- Middleware: `auth` + `admin`

## Créditos

Desarrollado para NutriShop por:
- Truman Castañeda
- Alberto Colindres
- Christopher Martínez

---

**Fecha de implementación:** 18 de Noviembre, 2025
