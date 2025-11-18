<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.4em] text-emerald-500">Información personal</p>
            <h2 class="mt-2 text-2xl font-semibold text-slate-900">Datos de tu cuenta</h2>
            <p class="text-sm text-slate-500">
                Mantén tu nombre y correo actualizados para recibir notificaciones importantes sin contratiempos.
            </p>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <label for="name" class="text-sm font-semibold text-slate-700">Nombre completo</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $user->name) }}"
                    required
                    autocomplete="name"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-inner focus:border-[#11BF6E] focus:ring-[#11BF6E]"
                >
                <x-input-error class="mt-1" :messages="$errors->get('name')" />
            </div>

            <div class="space-y-2">
                <label for="email" class="text-sm font-semibold text-slate-700">Correo electrónico</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="username"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-inner focus:border-[#11BF6E] focus:ring-[#11BF6E]"
                >
                <x-input-error class="mt-1" :messages="$errors->get('email')" />
            </div>
        </div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[#11BF6E] px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-emerald-200 transition hover:scale-[1.01]">
                Guardar cambios
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-semibold text-emerald-600"
                >Información actualizada</p>
            @endif
        </div>
    </form>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
            <p class="font-semibold">Tu correo aún no ha sido verificado.</p>
            <p class="mt-1">Revisa tu bandeja o pide un nuevo enlace:</p>
            <div class="mt-3 flex flex-col gap-2 sm:flex-row sm:items-center">
                <button
                    form="send-verification"
                    class="inline-flex items-center justify-center rounded-2xl border border-amber-400 px-4 py-2 text-sm font-semibold text-amber-900 transition hover:bg-amber-100"
                >
                    Enviar enlace de verificación
                </button>

                @if (session('status') === 'verification-link-sent')
                    <span class="text-xs font-semibold uppercase tracking-widest text-emerald-600">Reenviamos el enlace ✅</span>
                @endif
            </div>
        </div>
    @endif
</div>
