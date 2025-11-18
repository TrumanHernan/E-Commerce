# Configuración de Autenticación Social (Google y Facebook)

## 🎯 ¿Qué se ha implementado?

Se ha configurado Laravel Socialite para permitir que los usuarios inicien sesión con sus cuentas de Google y Facebook.

## 📋 Pasos Previos

### 1. Instalar Laravel Socialite

```bash
cd laravel-ecommerce
composer update
```

### 2. Ejecutar las migraciones

```bash
php artisan migrate
```

Esto agregará los campos `provider`, `provider_id` y `avatar` a la tabla `users`.

---

## 🔧 Configuración de Google OAuth

### Paso 1: Crear un proyecto en Google Cloud Console

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Crea un nuevo proyecto o selecciona uno existente
3. Habilita la **Google+ API**:
   - Ve a "API y servicios" > "Biblioteca"
   - Busca "Google+ API" y habilítala

### Paso 2: Crear credenciales OAuth 2.0

1. Ve a "API y servicios" > "Credenciales"
2. Haz clic en "Crear credenciales" > "ID de cliente de OAuth"
3. Configura la pantalla de consentimiento si es necesario
4. Tipo de aplicación: **Aplicación web**
5. Nombre: "NutriShop Login"
6. **URIs de redirección autorizados**:
   ```
   http://localhost:8000/auth/google/callback
   ```
7. Copia el **Client ID** y el **Client Secret**

### Paso 3: Configurar en Laravel

Edita el archivo `.env`:

```env
GOOGLE_CLIENT_ID=tu_google_client_id_aqui
GOOGLE_CLIENT_SECRET=tu_google_client_secret_aqui
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

---

## 🔧 Configuración de Facebook OAuth

### Paso 1: Crear una aplicación en Facebook Developers

1. Ve a [Facebook Developers](https://developers.facebook.com/)
2. Haz clic en "Mis aplicaciones" > "Crear aplicación"
3. Selecciona "Consumidor" como tipo
4. Nombre: "NutriShop"
5. Completa la información requerida

### Paso 2: Configurar Facebook Login

1. En el panel de tu aplicación, ve a "Productos" > "Facebook Login"
2. Haz clic en "Configuración"
3. En **URI de redireccionamiento de OAuth válidos**, agrega:
   ```
   http://localhost:8000/auth/facebook/callback
   ```
4. Guarda los cambios

### Paso 3: Obtener credenciales

1. Ve a "Configuración" > "Básica"
2. Copia el **ID de la aplicación** y el **Secret de la aplicación**

### Paso 4: Configurar en Laravel

Edita el archivo `.env`:

```env
FACEBOOK_CLIENT_ID=tu_facebook_app_id_aqui
FACEBOOK_CLIENT_SECRET=tu_facebook_app_secret_aqui
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
```

---

## 🚀 Probar la Configuración

### 1. Limpiar la caché de configuración:

```bash
php artisan config:clear
php artisan cache:clear
```

### 2. Iniciar el servidor:

```bash
php artisan serve
```

### 3. Probar el login:

1. Ve a `http://localhost:8000/login`
2. Haz clic en "Continuar con Google" o "Continuar con Facebook"
3. Autoriza la aplicación
4. Deberías ser redirigido al home como usuario autenticado

---

## 📝 Notas Importantes

### Para producción:

- Cambia las URIs de callback a tu dominio real:
  - En Google: `https://tudominio.com/auth/google/callback`
  - En Facebook: `https://tudominio.com/auth/facebook/callback`
- Actualiza las variables en `.env` con las URLs de producción

### Seguridad:

- **NUNCA** compartas tu `GOOGLE_CLIENT_SECRET` o `FACEBOOK_CLIENT_SECRET`
- **NUNCA** subas el archivo `.env` a GitHub
- Asegúrate de que `.env` esté en `.gitignore`

### Campos de la base de datos:

Los usuarios que se registren con Google o Facebook tendrán:
- `provider`: 'google' o 'facebook'
- `provider_id`: ID del usuario en el proveedor
- `avatar`: URL de la foto de perfil
- `email_verified_at`: Marcado automáticamente como verificado

---

## 🐛 Solución de Problemas

### Error: "Client ID not found"
- Verifica que hayas copiado correctamente las credenciales en `.env`
- Ejecuta `php artisan config:clear`

### Error: "Redirect URI mismatch"
- Asegúrate de que las URIs en Google/Facebook coincidan exactamente con las configuradas
- Revisa que no haya espacios o caracteres extra

### No recibo el email del usuario
- Algunos proveedores requieren permisos adicionales para acceder al email
- Verifica que el scope incluya el email (Socialite lo hace por defecto)

---

## 📚 Recursos Adicionales

- [Laravel Socialite Documentation](https://laravel.com/docs/socialite)
- [Google OAuth Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Facebook Login Documentation](https://developers.facebook.com/docs/facebook-login)
