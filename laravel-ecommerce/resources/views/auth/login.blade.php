@php
    $activeTab = old('auth_tab', 'login');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NutriShop') }} - Acceso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
<div class="contenedor-principal">
    <div class="contenedor-login shadow rounded-4 overflow-hidden">
        <div class="panel-izquierdo">
            <h1>BIENVENIDO a NutriShop</h1>
            <p>Únete a miles de atletas que confían en nosotros para alcanzar sus metas fitness. Como miembro obtendrás:</p>
            <ul class="lista-beneficios">
                <li><i class="bi bi-check2"></i> Descuentos exclusivos para miembros</li>
                <li><i class="bi bi-check2"></i> Envío gratis en tu primera compra</li>
                <li><i class="bi bi-check2"></i> Acceso a ofertas anticipadas</li>
                <li><i class="bi bi-check2"></i> Seguimiento de pedidos en tiempo real</li>
                <li><i class="bi bi-check2"></i> Atención al cliente prioritaria</li>
            </ul>
        </div>

        <div class="panel-derecho">
            <ul class="nav nav-tabs mb-4" id="pestanasAcceso" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'login' ? 'active' : '' }}" id="pestana-iniciar" data-bs-toggle="tab" data-bs-target="#iniciar" type="button" role="tab">
                        Iniciar Sesión
                    </button>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'register' ? 'active' : '' }}" id="pestana-registrar" data-bs-toggle="tab" data-bs-target="#registrar" type="button" role="tab">
                            Registrarse
                        </button>
                    </li>
                @endif
            </ul>

            <div class="tab-content" id="contenidoPestanasAcceso">
                <div class="tab-pane fade {{ $activeTab === 'login' ? 'show active' : '' }}" id="iniciar" role="tabpanel">
                    @if (session('status'))
                        <div class="alert alert-success py-2">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <input type="hidden" name="auth_tab" value="login">

                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email"
                                   class="form-control {{ $activeTab === 'login' && $errors->has('email') ? 'is-invalid' : '' }}"
                                   id="correo"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="ejemplo@correo.com"
                                   required
                                   autofocus
                                   autocomplete="email">
                            @if ($activeTab === 'login')
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input type="password"
                                   class="form-control {{ $activeTab === 'login' && $errors->has('password') ? 'is-invalid' : '' }}"
                                   name="password"
                                   id="contrasena"
                                   placeholder="********"
                                   required
                                   autocomplete="current-password">
                            @if ($activeTab === 'login')
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @endif
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="recordar" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="recordar">Recordarme</label>
                        </div>

                        <div class="mb-3 text-end">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-iniciar w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                        </button>
                    </form>

                    <hr class="my-4">

                    <button class="btn btn-outline-dark btn-social" type="button">
                        <i class="bi bi-google"></i> Continuar con Google
                    </button>
                    <button class="btn btn-outline-primary btn-social" type="button">
                        <i class="bi bi-facebook"></i> Continuar con Facebook
                    </button>
                </div>

                <div id="mensaje-error" class="text-danger mb-3">
                    @if ($activeTab === 'login' && ($errors->has('email') || $errors->has('password')))
                        {{ $errors->first('email') ?? $errors->first('password') }}
                    @endif
                </div>

                @if (Route::has('register'))
                    <div class="tab-pane fade {{ $activeTab === 'register' ? 'show active' : '' }}" id="registrar" role="tabpanel">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <input type="hidden" name="auth_tab" value="register">

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text"
                                       class="form-control {{ $activeTab === 'register' && $errors->has('name') ? 'is-invalid' : '' }}"
                                       id="nombre"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="Tu nombre"
                                       required>
                                @if ($activeTab === 'register')
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="correo-registro" class="form-label">Correo Electrónico</label>
                                <input type="email"
                                       class="form-control {{ $activeTab === 'register' && $errors->has('email') ? 'is-invalid' : '' }}"
                                       id="correo-registro"
                                       name="email"
                                       value="{{ $activeTab === 'register' ? old('email') : '' }}"
                                       placeholder="ejemplo@correo.com"
                                       required
                                       autocomplete="email">
                                @if ($activeTab === 'register')
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="contrasena-registro" class="form-label">Contraseña</label>
                                <input type="password"
                                       class="form-control {{ $activeTab === 'register' && $errors->has('password') ? 'is-invalid' : '' }}"
                                       id="contrasena-registro"
                                       name="password"
                                       placeholder="********"
                                       required
                                       autocomplete="new-password">
                                @if ($activeTab === 'register')
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="confirmar" class="form-label">Confirmar Contraseña</label>
                                <input type="password"
                                       class="form-control {{ $activeTab === 'register' && $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                       id="confirmar"
                                       name="password_confirmation"
                                       placeholder="********"
                                       required
                                       autocomplete="new-password">
                                @if ($activeTab === 'register')
                                    @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terminos" name="terminos" {{ old('terminos') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="terminos">Acepto los términos y condiciones</label>
                            </div>

                            <button type="submit" class="btn btn-iniciar w-100">
                                <i class="bi bi-person-plus"></i> Crear Cuenta
                            </button>
                        </form>

                        <hr class="my-4">

                        <button class="btn btn-outline-dark btn-social" type="button">
                            <i class="bi bi-google"></i> Registrarse con Google
                        </button>
                        <button class="btn btn-outline-primary btn-social" type="button">
                            <i class="bi bi-facebook"></i> Registrarse con Facebook
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
