```php
// Script para crear usuario cajero de prueba
// Ejecutar en php artisan tinker

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Crear usuario cajero
$cajero = User::create([
    'name' => 'Cajero Demo',
    'email' => 'cajero@nutrishop.com',
    'password' => Hash::make('cajero123'),
    'rol' => 'cajero',
    'email_verified_at' => now(),
]);

echo "Usuario cajero creado exitosamente!\n";
echo "Email: cajero@nutrishop.com\n";
echo "Password: cajero123\n";
echo "Rol: cajero\n";
```

**Instrucciones:**

1. Ejecuta en la terminal:
```bash
php artisan tinker
```

2. Copia y pega el siguiente código:
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$cajero = User::create([
    'name' => 'Cajero Demo',
    'email' => 'cajero@nutrishop.com',
    'password' => Hash::make('cajero123'),
    'rol' => 'cajero',
    'email_verified_at' => now(),
]);

echo "✅ Usuario cajero creado!\n";
echo "Email: cajero@nutrishop.com\n";
echo "Password: cajero123\n";
```

3. Sal de tinker con `exit`

---

## Permisos por Rol

### Admin (admin@nutrishop.com)
✅ Dashboard
✅ Productos (ver/crear/editar/eliminar)
✅ Compras
✅ Proveedores
✅ Ventas
✅ Perfil
✅ Ver Sitio Web

### Cajero (cajero@nutrishop.com)
✅ Dashboard
✅ Ventas (ver/cambiar estado)
✅ Perfil
✅ Ver Sitio Web
❌ Productos
❌ Compras
❌ Proveedores

### Cliente
✅ Dashboard
✅ Productos (ver)
✅ Carrito
✅ Favoritos
✅ Mis Pedidos
✅ Perfil
