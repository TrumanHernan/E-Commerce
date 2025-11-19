# Guía: Conectar a la Base de Datos de Producción (PostgreSQL)

## 🎯 OPCIÓN 1: Instalar PostgreSQL Local (RECOMENDADO)

### 1. Descargar PostgreSQL
- Ve a: https://www.postgresql.org/download/windows/
- Descarga el instalador (versión 16 o superior)
- Instala con configuración por defecto
- **IMPORTANTE**: Guarda la contraseña que pongas para el usuario `postgres`

### 2. Instalar Driver PHP para PostgreSQL
```powershell
# Verificar si ya tienes pdo_pgsql
php -m | Select-String pgsql
```

Si NO aparece nada, edita tu `php.ini`:
```ini
# Busca estas líneas y quítales el punto y coma (;) al inicio:
extension=pdo_pgsql
extension=pgsql
```

### 3. Crear Base de Datos Local PostgreSQL
```powershell
# Abrir terminal de PostgreSQL (psql)
psql -U postgres

# Dentro de psql:
CREATE DATABASE proyecto_suplementos;
\q
```

### 4. Actualizar tu .env LOCAL
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=proyecto_suplementos
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña_aqui
```

### 5. Migrar
```powershell
cd laravel-ecommerce
php artisan migrate:fresh --seed
```

---

## 🌐 OPCIÓN 2: Conectarte DIRECTAMENTE a Supabase (Producción)

**⚠️ PELIGRO**: Estarás modificando la base de datos REAL que todos ven en https://e-commerce-0ak2.onrender.com/

### Pedirle a tu compañero las credenciales:
```env
DB_CONNECTION=pgsql
DB_HOST=aws-0-ca-central-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.xxxxxxxxxxxx
DB_PASSWORD=la_contraseña_real
```

### Cambiar tu .env:
```powershell
# Haz una copia de seguridad primero
cp .env .env.local.backup

# Edita .env con las credenciales de Supabase
# Luego:
php artisan config:clear
php artisan serve
```

**⚠️ CUIDADO**: Todo lo que hagas afectará la app en producción.

---

## 🔀 OPCIÓN 3: Tener AMBAS (Recomendado para desarrollo)

### Crear archivo `.env.production`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://e-commerce-0ak2.onrender.com

DB_CONNECTION=pgsql
DB_HOST=aws-0-ca-central-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.xxxxxxxxxxxx
DB_PASSWORD=contraseña_supabase
```

### Mantener `.env` para local:
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=proyecto_suplementos
DB_USERNAME=root
DB_PASSWORD=
```

### Cambiar entre ambos:
```powershell
# Para trabajar en local (MySQL)
php artisan serve

# Para probar con producción (PostgreSQL)
cp .env .env.backup
cp .env.production .env
php artisan config:clear
php artisan serve
# Cuando termines:
cp .env.backup .env
```

---

## 📊 COMPARACIÓN

| Opción | Pros | Contras |
|--------|------|---------|
| **PostgreSQL Local** | ✅ No afecta producción<br>✅ Misma BD que Render<br>✅ Pruebas seguras | ⚠️ Necesitas instalar PostgreSQL |
| **Conectar a Supabase** | ✅ No instalar nada<br>✅ Datos reales | ❌ PELIGROSO (afecta producción)<br>❌ Necesitas credenciales |
| **Mantener MySQL** | ✅ Ya funciona<br>✅ Fácil | ⚠️ Diferencias entre MySQL y PostgreSQL |

---

## 🎓 RECOMENDACIÓN

Para desarrollo normal: **Sigue usando MySQL local como ahora**

Solo instala PostgreSQL si:
- Encuentras bugs específicos de PostgreSQL
- Quieres probar queries complejos
- Tu compañero tiene problemas y necesitas replicar el ambiente

---

## ❓ PREGUNTAS FRECUENTES

### ¿Necesito cambiar a PostgreSQL?
**NO.** MySQL local funciona perfecto para desarrollo. Solo Render usa PostgreSQL.

### ¿Cómo sé si mi código funcionará en Render?
Las diferencias son mínimas. Si funciona en MySQL, casi siempre funciona en PostgreSQL.

### ¿Puedo seguir usando MySQL y mi compañero PostgreSQL?
**SÍ.** Laravel se encarga de las diferencias. Solo sube cambios a Git y Render usa PostgreSQL automáticamente.

### ¿Los seeders funcionan igual?
Sí, solo asegúrate de NO usar funciones específicas de MySQL (como `DATE_FORMAT`).

---

**🎯 CONCLUSIÓN: Deja tu .env como está (MySQL local). Todo seguirá funcionando.**
