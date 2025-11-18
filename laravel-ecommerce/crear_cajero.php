use App\Models\User;
use Illuminate\Support\Facades\Hash;

$cajero = User::create([
    'name' => 'Cajero Demo',
    'email' => 'cajero@nutrishop.com',
    'password' => Hash::make('cajero123'),
    'rol' => 'cajero',
    'email_verified_at' => now(),
]);

echo "✅ Usuario cajero creado exitosamente!\n";
echo "📧 Email: cajero@nutrishop.com\n";
echo "🔑 Password: cajero123\n";
echo "👤 Rol: cajero\n";
