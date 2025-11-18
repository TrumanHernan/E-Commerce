<div class="space-y-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.4em] text-emerald-500">Seguridad</p>
        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Actualiza tu contraseña</h2>
        <p class="text-sm text-slate-500">
            Usa una contraseña segura y única para proteger tus compras y datos personales.
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div class="space-y-2">
            <label for="update_password_current_password" class="text-sm font-semibold text-slate-700">Contraseña actual</label>
            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                autocomplete="current-password"
                required
                class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 focus:border-[#11BF6E] focus:ring-[#11BF6E]"
            >
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div class="space-y-2">
            <label for="update_password_password" class="text-sm font-semibold text-slate-700">Nueva contraseña</label>
            <input
                id="update_password_password"
                name="password"
                type="password"
                autocomplete="new-password"
                required
                class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 focus:border-[#11BF6E] focus:ring-[#11BF6E]"
            >
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div class="space-y-2">
            <label for="update_password_password_confirmation" class="text-sm font-semibold text-slate-700">Confirmar contraseña</label>
            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                required
                class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 focus:border-[#11BF6E] focus:ring-[#11BF6E]"
            >
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[#11BF6E] px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-emerald-200 transition hover:scale-[1.01]">
                Actualizar contraseña
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-semibold text-emerald-600"
                >Contraseña actualizada</p>
            @endif
        </div>
    </form>
</div>
