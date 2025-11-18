<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirigir al usuario al proveedor de autenticación
     */
    public function redirectToProvider($provider)
    {
        // Validar que el proveedor sea soportado
        if (!in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login')->with('error', 'Proveedor no soportado');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Manejar el callback del proveedor
     */
    public function handleProviderCallback($provider)
    {
        try {
            // Obtener información del usuario del proveedor
            $socialUser = Socialite::driver($provider)->user();

            // Buscar o crear el usuario en la base de datos
            $user = $this->findOrCreateUser($socialUser, $provider);

            // Iniciar sesión
            Auth::login($user, true);

            return redirect()->intended('/');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'No se pudo iniciar sesión con ' . ucfirst($provider) . '. Por favor, intenta de nuevo.');
        }
    }

    /**
     * Buscar o crear usuario basado en datos sociales
     */
    private function findOrCreateUser($socialUser, $provider)
    {
        // Buscar usuario por email
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Si el usuario existe, actualizar información del proveedor
            $user->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]);

            // Asegurar que tenga carrito
            if (!$user->carrito) {
                $user->carrito()->create();
            }

            return $user;
        }

        // Crear nuevo usuario
        $user = User::create([
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'avatar' => $socialUser->getAvatar(),
            'password' => Hash::make(Str::random(24)), // Contraseña aleatoria
            'email_verified_at' => now(), // Marcar como verificado
            'rol' => 'user', // Asignar rol de usuario
        ]);

        // Crear carrito para el nuevo usuario
        $user->carrito()->create();

        return $user;
    }
}
