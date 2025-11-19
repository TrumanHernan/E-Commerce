# Guía de Despliegue - Solución de Imágenes en Producción

## 🚨 PROBLEMA IDENTIFICADO

Las imágenes NO se mostraban en producción (https://e-commerce-0ak2.onrender.com/) porque:

1. **El enlace simbólico no se creaba**: Faltaba `php artisan storage:link` en el build de Render
2. **Las imágenes estaban ignoradas por Git**: El archivo `storage/app/public/.gitignore` tenía `*` que ignoraba todo
3. **El archivo render.yaml también estaba ignorado**: No se subía al repositorio

## ✅ SOLUCIÓN APLICADA

### 1. Actualizado `render.yaml`
Se agregó el comando `php artisan storage:link` al build:

```yaml
buildCommand: |
  composer install --optimize-autoloader --no-dev
  php artisan key:generate --force
  php artisan storage:link          # ← NUEVO: Crea el enlace simbólico
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php artisan migrate --force
```

### 2. Modificado `storage/app/public/.gitignore`
Se permitió que las imágenes de productos se suban al repo:

```gitignore
*
!.gitignore
!productos/         # ← NUEVO: Permite carpeta de productos
!productos/**       # ← NUEVO: Permite archivos dentro de productos
```

**NOTA**: Los avatares de usuarios NO se suben al repo (se generan dinámicamente en producción)

### 3. Removido `render.yaml` del `.gitignore` principal
Ahora el archivo de configuración de Render sí se sube al repositorio.

## 📋 PASOS PARA DESPLEGAR

### Opción A: Subir imágenes al repositorio (RECOMENDADO para productos iniciales)

```powershell
cd laravel-ecommerce

# 1. Agregar las imágenes de productos
git add storage/app/public/productos/

# 2. Agregar los archivos de configuración
git add storage/app/public/.gitignore
git add .gitignore
git add render.yaml

# 3. Hacer commit
git commit -m "fix: Habilitar imágenes de productos en producción

- Agregado storage:link al build de Render
- Modificado gitignore para incluir imágenes de productos
- Subidas imágenes iniciales de productos"

# 4. Subir a GitHub
git push origin master
```

### Opción B: Subir productos desde el panel admin (para nuevas imágenes)

Después del deploy, ingresa al panel de administración y:

1. Ve a **Productos** → **Agregar Producto**
2. Sube la imagen desde el formulario
3. Las imágenes se guardarán en `storage/app/public/productos/` en el servidor

## 🔍 VERIFICACIÓN EN RENDER

1. Ve al Dashboard de Render → Tu servicio
2. Espera a que termine el deploy (debe decir "Live")
3. Revisa los logs del deploy y busca:
   ```
   The [public/storage] link has been connected to [storage/app/public].
   ```
4. Abre tu sitio: https://e-commerce-0ak2.onrender.com/
5. Las imágenes ahora deberían verse correctamente

## 🎯 ESTRUCTURA DE ARCHIVOS

```
laravel-ecommerce/
├── storage/
│   └── app/
│       └── public/
│           ├── .gitignore (modificado)
│           ├── productos/
│           │   ├── .gitkeep
│           │   ├── iso100.png ✅ (se sube a Git)
│           │   ├── creatina_epiq.png ✅
│           │   └── ... (todas las imágenes)
│           └── avatars/
│               └── .gitkeep (la carpeta existe, pero avatares NO se suben)
└── public/
    └── storage → ../storage/app/public (enlace creado por storage:link)
```

## 🚀 CÓMO FUNCIONAN LAS IMÁGENES EN LARAVEL

1. **Se suben**: `storage/app/public/productos/imagen.png`
2. **Se crea enlace**: `php artisan storage:link`
3. **Se acceden vía**: `https://tudominio.com/storage/productos/imagen.png`
4. **En Blade**: `{{ asset('storage/productos/' . $producto->imagen) }}`

## ⚠️ NOTAS IMPORTANTES

- **Producción vs Local**: Las imágenes subidas en local NO aparecen automáticamente en producción (debes subirlas al repo o volver a subirlas desde el admin)
- **APP_URL**: Asegúrate de que el `.env` de Render tenga `APP_URL=https://e-commerce-0ak2.onrender.com`
- **Permisos**: Render automáticamente da permisos de escritura a `storage/` en deploy
- **Git LFS**: Si tienes MUCHAS imágenes grandes (>100MB total), considera usar Git LFS

## 🔧 COMANDOS ÚTILES DESPUÉS DEL DEPLOY

Si las imágenes siguen sin verse, accede a la Shell de Render y ejecuta:

```bash
# Verificar que el enlace existe
ls -la public/storage

# Volver a crear el enlace si es necesario
php artisan storage:link --force

# Ver qué imágenes hay
ls -la storage/app/public/productos/

# Ver permisos
ls -la storage/app/public/
```

## 📝 SIGUIENTE DEPLOY

Para futuros deploys con nuevos productos:

1. **Opción 1**: Subir imágenes al repo (si son productos permanentes)
   ```bash
   git add storage/app/public/productos/nueva_imagen.png
   git commit -m "feat: Agregar imagen de nuevo producto"
   git push
   ```

2. **Opción 2**: Subir desde el panel admin (si son productos temporales o ofertas)

---

**✨ Con estos cambios, todas las imágenes de productos deberían mostrarse correctamente en https://e-commerce-0ak2.onrender.com/**
