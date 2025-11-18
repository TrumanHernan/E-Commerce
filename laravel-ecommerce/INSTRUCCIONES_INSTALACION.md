# 📋 INSTRUCCIONES DE INSTALACIÓN - NUTRISHOP

Proyecto E-commerce de Suplementos Deportivos migrado a Laravel 11

---

## ✅ FUNCIONALIDAD DE RECUPERACIÓN DE CONTRASEÑA

**¡SÍ ESTÁ INCLUIDA!** El trabajo de Truman configurando el email fue migrado exitosamente a Laravel Breeze.

### Configuración de Email (YA CONFIGURADO)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=trumanhernan@gmail.com
MAIL_PASSWORD=nkmogwkqrmfbbwmg
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="trumanhernan@gmail.com"
MAIL_FROM_NAME="NutriShop"
```

### Rutas de Recuperación de Contraseña:
- **Solicitar reset**: `http://localhost:8000/forgot-password`
- **Resetear contraseña**: `http://localhost:8000/reset-password/{token}`

### Vistas Migradas:
- ✅ `resources/views/auth/forgot-password.blade.php`
- ✅ `resources/views/auth/reset-password.blade.php`
- ✅ Controladores: `PasswordResetLinkController`, `NewPasswordController`

---

## 🍎 INSTALACIÓN EN macOS (Para Chris)

### Prerequisitos
```bash
# Verificar que tienes instalado:
php -v          # PHP 8.2 o superior
mysql --version # MySQL 8.0 o superior
composer -V     # Composer 2.x
```

### Paso 1: Acceder al Proyecto
```bash
cd /Users/chris/Develop/E-Commerce/laravel-ecommerce
```

### Paso 2: Instalar Dependencias
```bash
composer install
```

### Paso 3: Configurar Base de Datos

**Iniciar MySQL:**
```bash
mysql.server start
```

**Crear base de datos:**
```bash
mysql -u root -p
```

```sql
CREATE DATABASE proyecto_suplementos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### Paso 4: Verificar Configuración .env
```bash
cat .env | grep -E "(DB_|MAIL_)"
```

Debe mostrar:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=proyecto_suplementos
DB_USERNAME=root
DB_PASSWORD=QBuxsx4L48LtnQwPMb6PuFjw.

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=trumanhernan@gmail.com
```

### Paso 5: Ejecutar Migraciones y Seeders
```bash
# Crear todas las tablas y cargar datos
php artisan migrate:fresh --seed
```

### Paso 6: Crear Symlink de Storage
```bash
php artisan storage:link
```

### Paso 7: Levantar el Servidor
```bash
php artisan serve
```

### Paso 8: Acceder al Proyecto
```
🌐 Sitio Público: http://localhost:8000
🔐 Panel Admin: http://localhost:8000/admin/dashboard
📧 Recuperar Contraseña: http://localhost:8000/forgot-password
```

---

## 🪟 INSTALACIÓN EN WINDOWS CON XAMPP (Para Truman)

### Prerequisitos
- ✅ XAMPP instalado (normalmente en `C:\xampp`)
- ✅ Composer instalado globalmente
- ✅ Git instalado (para clonar el proyecto)

### Paso 1: Iniciar XAMPP

1. Abrir **XAMPP Control Panel**
2. Click en **Start** en Apache
3. Click en **Start** en MySQL
4. Verificar que ambos estén en verde (Running)

![XAMPP Control Panel](https://i.imgur.com/xampp.png)

### Paso 2: Clonar/Copiar el Proyecto

**Opción A: Usando Git**
```cmd
cd C:\xampp\htdocs
git clone <repositorio> nutrishop
cd nutrishop
```

**Opción B: Copiar manualmente**
1. Copiar la carpeta del proyecto a `C:\xampp\htdocs\nutrishop`
2. Asegurarse de que la estructura sea:
   ```
   C:\xampp\htdocs\nutrishop\
   ├── app\
   ├── public\
   ├── resources\
   └── ...
   ```

### Paso 3: Instalar Dependencias

Abrir **CMD** o **PowerShell** como Administrador:

```cmd
cd C:\xampp\htdocs\nutrishop
composer install
```

Si da error de permisos:
```cmd
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
```

### Paso 4: Configurar Base de Datos

**Usando phpMyAdmin:**

1. Abrir navegador: http://localhost/phpmyadmin
2. Click en "**Nueva**" (New) en el sidebar izquierdo
3. Nombre de la base de datos: `proyecto_suplementos`
4. Cotejamiento (Collation): `utf8mb4_unicode_ci`
5. Click en "**Crear**" (Create)

**O usando MySQL desde CMD:**
```cmd
C:\xampp\mysql\bin\mysql.exe -u root -p
```

```sql
CREATE DATABASE proyecto_suplementos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### Paso 5: Configurar Archivo .env

**Si NO existe .env:**
```cmd
copy .env.example .env
```

**Editar .env** (usar Notepad++, VSCode, o cualquier editor):
```cmd
notepad .env
```

Configurar estos valores:
```env
APP_NAME=NutriShop
APP_URL=http://localhost/nutrishop/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=proyecto_suplementos
DB_USERNAME=root
DB_PASSWORD=              # Dejar vacío (sin password por defecto en XAMPP)

# Configuración de Email (YA CONFIGURADA POR TRUMAN)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=trumanhernan@gmail.com
MAIL_PASSWORD=nkmogwkqrmfbbwmg
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="trumanhernan@gmail.com"
MAIL_FROM_NAME="NutriShop"
```

**Guardar y cerrar** el archivo.

### Paso 6: Generar Application Key
```cmd
php artisan key:generate
```

### Paso 7: Ejecutar Migraciones y Seeders
```cmd
# Crear todas las tablas y cargar datos de prueba
php artisan migrate:fresh --seed
```

Deberías ver:
```
INFO  Running migrations.
✓ 12 migraciones ejecutadas exitosamente

INFO  Seeding database.
✓ CategoriaSeeder
✓ ProductoSeeder  
✓ ProveedorSeeder
```

### Paso 8: Crear Symlink de Storage

**CMD como Administrador:**
```cmd
php artisan storage:link
```

Si da error, usar:
```cmd
mklink /D "C:\xampp\htdocs\nutrishop\public\storage" "C:\xampp\htdocs\nutrishop\storage\app\public"
```

### Paso 9: Configurar Virtual Host (RECOMENDADO)

**Editar httpd-vhosts.conf:**
```cmd
notepad C:\xampp\apache\conf\extra\httpd-vhosts.conf
```

Agregar al final:
```apache
<VirtualHost *:80>
    ServerName nutrishop.local
    DocumentRoot "C:/xampp/htdocs/nutrishop/public"
    
    <Directory "C:/xampp/htdocs/nutrishop/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Editar archivo hosts:**
```cmd
notepad C:\Windows\System32\drivers\etc\hosts
```

Agregar al final:
```
127.0.0.1    nutrishop.local
```

**Reiniciar Apache en XAMPP Control Panel**
1. Click en **Stop** en Apache
2. Click en **Start** en Apache

### Paso 10: Acceder al Proyecto

**Opción A: Con Virtual Host**
```
🌐 Sitio: http://nutrishop.local
🔐 Admin: http://nutrishop.local/admin/dashboard
📧 Recuperar: http://nutrishop.local/forgot-password
```

**Opción B: Sin Virtual Host**
```
🌐 Sitio: http://localhost/nutrishop/public
🔐 Admin: http://localhost/nutrishop/public/admin/dashboard
📧 Recuperar: http://localhost/nutrishop/public/forgot-password
```

**Opción C: Servidor de desarrollo de PHP** (Recomendado para desarrollo)
```cmd
cd C:\xampp\htdocs\nutrishop
php artisan serve
```

Luego acceder a:
```
🌐 http://localhost:8000
🔐 http://localhost:8000/admin/dashboard
📧 http://localhost:8000/forgot-password
```

---

## 🔑 CREDENCIALES DE ACCESO

```
👨‍💼 ADMINISTRADOR:
Email: admin@nutrishop.com
Password: admin123
Rol: admin
Acceso: Panel completo, gestión de productos, proveedores, pedidos

👤 USUARIO NORMAL:
Email: usuario@nutrishop.com  
Password: usuario123
Rol: user
Acceso: Carrito, favoritos, pedidos, perfil
```

---

## 🧪 PROBAR RECUPERACIÓN DE CONTRASEÑA

### Método 1: Usando la interfaz web

1. **Ir a login:**
   ```
   http://localhost:8000/login
   ```

2. **Click en "Forgot your password?"**
   
3. **Ingresar email:**
   ```
   usuario@nutrishop.com
   ```

4. **Revisar logs** (el email se registra en logs):
   ```cmd
   type storage\logs\laravel.log
   ```

5. **Buscar el link en los logs** o revisar el email si configuraste Gmail correctamente

6. **Copiar el link del reset** y pegarlo en el navegador:
   ```
   http://localhost:8000/reset-password/{token}?email=usuario@nutrishop.com
   ```

7. **Ingresar nueva contraseña y confirmar**

### Método 2: Generar token manualmente

```cmd
php artisan tinker
```

```php
use Illuminate\Support\Facades\Password;

// Generar token de reset
$user = App\Models\User::where('email', 'usuario@nutrishop.com')->first();
$token = Password::createToken($user);

// Mostrar el link
echo "http://localhost:8000/reset-password/{$token}?email=usuario@nutrishop.com";
```

Copiar el link y abrirlo en el navegador.

---

## 📊 DATOS DE PRUEBA CARGADOS

### Usuarios (2)
- ✅ admin@nutrishop.com / admin123 (Admin)
- ✅ usuario@nutrishop.com / usuario123 (Usuario)

### Categorías (4)
- ✅ Proteínas
- ✅ Creatinas
- ✅ Pre-Entreno
- ✅ Vitaminas

### Productos (12)
- ✅ 3 Proteínas (Whey Protein, Iso 100, Mass Gainer)
- ✅ 3 Creatinas (Evolution, Basic, Epiq)
- ✅ 3 Pre-Entrenos (C4, Pre-War, Gold Standard)
- ✅ 3 Vitaminas (Omega-3, D3, C)

### Proveedores (3)
- ✅ Optimum Nutrition
- ✅ MuscleTech
- ✅ Cellucor

---

## 🛠️ COMANDOS ÚTILES

### Windows (CMD/PowerShell)

**Limpiar caché:**
```cmd
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

**Ver rutas:**
```cmd
php artisan route:list
php artisan route:list --name=productos
php artisan route:list --path=admin
```

**Resetear base de datos:**
```cmd
php artisan migrate:fresh --seed
```

**Ver logs en tiempo real:**
```cmd
# PowerShell
Get-Content storage\logs\laravel.log -Wait -Tail 50

# CMD
type storage\logs\laravel.log
```

**Crear nuevo admin:**
```cmd
php artisan tinker
```
```php
App\Models\User::create([
    'name' => 'Truman Admin',
    'email' => 'truman@admin.com',
    'password' => bcrypt('admin123'),
    'rol' => 'admin'
]);
```

### Mac (Terminal)

**Limpiar caché:**
```bash
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear
```

**Ver logs en tiempo real:**
```bash
tail -f storage/logs/laravel.log
```

**Resetear BD:**
```bash
php artisan migrate:fresh --seed
```

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Windows (Truman)

#### Error: "SQLSTATE[HY000] [2002] No connection could be made"
```
✅ Solución:
1. Abrir XAMPP Control Panel
2. Verificar que MySQL esté iniciado (verde)
3. Click en "Stop" y luego "Start" en MySQL
4. Verificar puerto en .env: DB_PORT=3306
```

#### Error: "file_put_contents(): failed to open stream"
```cmd
# CMD como Administrador
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
```

#### Error: "The stream or file could not be opened"
```
✅ Solución:
1. Click derecho en carpeta storage
2. Propiedades > Seguridad > Editar
3. Dar control total a "Usuarios"
4. Aplicar a subcarpetas
```

#### Error: "No application encryption key"
```cmd
php artisan key:generate
```

#### El symlink no funciona
```cmd
# Borrar symlink anterior
rmdir public\storage

# Crear nuevo (CMD como Administrador)
mklink /D "C:\xampp\htdocs\nutrishop\public\storage" "C:\xampp\htdocs\nutrishop\storage\app\public"
```

#### CSS/JS no cargan
```
✅ Verificar que las rutas en .env sean correctas:
APP_URL=http://localhost/nutrishop/public

O usar servidor de desarrollo:
php artisan serve
```

#### Error al enviar emails
```cmd
# Verificar configuración
php artisan config:cache

# Ver logs
type storage\logs\laravel.log

# Probar envío
php artisan tinker
```
```php
Mail::raw('Test', function($msg) {
    $msg->to('test@test.com')->subject('Test');
});
```

### Mac (Chris)

#### MySQL no inicia
```bash
# Iniciar MySQL
mysql.server start

# Si falla, verificar procesos
ps aux | grep mysql
```

#### Permisos de storage
```bash
chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:$USER storage bootstrap/cache
```

#### Port 8000 ocupado
```bash
# Ver qué usa el puerto
lsof -i :8000

# Usar otro puerto
php artisan serve --port=8001
```

---

## 📁 ESTRUCTURA DEL PROYECTO

```
laravel-ecommerce/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php               ✅
│   │   │   ├── ProductoController.php           ✅
│   │   │   ├── CarritoController.php            ✅
│   │   │   ├── FavoritoController.php           ✅
│   │   │   ├── PedidoController.php             ✅
│   │   │   ├── ProveedorController.php          ✅
│   │   │   ├── AdminDashboardController.php     ✅
│   │   │   └── Auth/
│   │   │       ├── PasswordResetLinkController  ✅
│   │   │       └── NewPasswordController        ✅
│   │   └── Middleware/
│   │       └── AdminMiddleware.php              ✅
│   ├── Models/
│   │   ├── User.php                             ✅
│   │   ├── Categoria.php                        ✅
│   │   ├── Producto.php                         ✅
│   │   ├── Carrito.php                          ✅
│   │   ├── CarritoItem.php                      ✅
│   │   ├── Pedido.php                           ✅
│   │   ├── PedidoDetalle.php                    ✅
│   │   ├── Favorito.php                         ✅
│   │   └── Proveedor.php                        ✅
│   └── Providers/
│       └── AppServiceProvider.php               ✅
├── database/
│   ├── migrations/                              12 archivos ✅
│   └── seeders/                                 4 archivos ✅
├── public/
│   ├── css/                                     Migrados ✅
│   ├── js/                                      Migrados ✅
│   ├── img/                                     Migrados ✅
│   └── storage/                                 Symlink ✅
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── main.blade.php                   ✅
│       │   └── admin.blade.php                  ✅
│       ├── auth/
│       │   ├── login.blade.php                  ✅
│       │   ├── register.blade.php               ✅
│       │   ├── forgot-password.blade.php        ✅
│       │   └── reset-password.blade.php         ✅
│       ├── home.blade.php                       ✅
│       ├── productos/                           3 vistas ✅
│       ├── carrito/                             1 vista ✅
│       ├── pedidos/                             3 vistas ✅
│       ├── favoritos/                           1 vista ✅
│       └── admin/                               7 vistas ✅
├── routes/
│   ├── web.php                                  30+ rutas ✅
│   └── auth.php                                 Breeze ✅
└── storage/
    └── app/public/productos/                    12 imágenes ✅
```

---

## ✅ CHECKLIST DE INSTALACIÓN

### Windows (Truman)
- [ ] XAMPP instalado
- [ ] Apache y MySQL iniciados (verde en XAMPP)
- [ ] Composer instalado (`composer -V`)
- [ ] Proyecto en `C:\xampp\htdocs\nutrishop`
- [ ] Base de datos `proyecto_suplementos` creada
- [ ] `.env` configurado (verificar DB_PASSWORD vacío)
- [ ] `php artisan key:generate` ejecutado
- [ ] `composer install` ejecutado
- [ ] Permisos de storage configurados
- [ ] `php artisan migrate:fresh --seed` ejecutado
- [ ] Symlink creado (`php artisan storage:link`)
- [ ] Virtual host configurado (opcional)
- [ ] Acceso a http://nutrishop.local o http://localhost:8000
- [ ] Login con admin@nutrishop.com funciona
- [ ] Login con usuario@nutrishop.com funciona
- [ ] **Recuperación de contraseña probada** ✅

### Mac (Chris)
- [ ] MySQL iniciado (`mysql.server start`)
- [ ] Composer instalado
- [ ] Base de datos creada
- [ ] `composer install` ejecutado
- [ ] `php artisan migrate:fresh --seed` ejecutado
- [ ] `php artisan storage:link` ejecutado
- [ ] `php artisan serve` corriendo
- [ ] Acceso a http://localhost:8000
- [ ] Login funciona
- [ ] **Recuperación de contraseña probada** ✅

---

## 🎉 CARACTERÍSTICAS COMPLETADAS

### ✅ Migración HTML → Laravel
- [x] Todos los archivos HTML convertidos a Blade
- [x] Assets (CSS, JS, imágenes) migrados
- [x] Estructura responsive mantenida
- [x] Bootstrap 5.3 implementado

### ✅ Backend
- [x] 9 modelos Eloquent con relaciones
- [x] 12 tablas en base de datos
- [x] 7 controladores completos
- [x] Middleware de autenticación y autorización
- [x] Validación de formularios
- [x] Sistema de seeders

### ✅ Funcionalidades
- [x] Autenticación completa (Login, Register, Logout)
- [x] **Recuperación de contraseña** ✅✅✅
- [x] Sistema de roles (Admin/Usuario)
- [x] Carrito de compras
- [x] Favoritos
- [x] Checkout y pedidos
- [x] Panel de administración
- [x] Gestión de productos
- [x] Gestión de proveedores
- [x] Dashboard con estadísticas

---

## 📧 NOTA SOBRE EMAIL

**El sistema de recuperación de contraseña está completamente funcional.**

La configuración de Gmail de Truman (`trumanhernan@gmail.com`) está migrada y funcionando.

**Para probar en local sin enviar emails reales:**

1. Usar servicio como **Mailtrap** (recomendado para desarrollo):
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=tu_username
   MAIL_PASSWORD=tu_password
   ```

2. O usar el driver `log` para ver emails en los logs:
   ```env
   MAIL_MAILER=log
   ```

3. Revisar email en: `storage/logs/laravel.log`

---

**¡Proyecto completamente migrado y listo para usar! 🚀**

Cualquier duda, revisar los logs:
- Windows: `type storage\logs\laravel.log`
- Mac: `tail -f storage/logs/laravel.log`
