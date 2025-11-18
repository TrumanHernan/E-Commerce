<div class="space-y-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.4em] text-red-500">Zona sensible</p>
        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Eliminar cuenta</h2>
        <p class="text-sm text-slate-500">
            Esta acción no se puede deshacer. Se eliminarán tus pedidos, historial y preferencias guardadas.
        </p>
    </div>

    <div class="rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-800">
        Guarda tus comprobantes o información clave antes de solicitar la eliminación definitiva.
    </div>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center justify-center rounded-2xl bg-red-600 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-red-200 transition hover:scale-[1.01]"
    >
        Quiero eliminar mi cuenta
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6 bg-white p-6 text-slate-700">
            @csrf
            @method('delete')

            <div>
                <h2 class="text-xl font-semibold text-slate-900">¿Estás seguro?</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Escribe tu contraseña para confirmar que comprendés que tu información se eliminará permanentemente.
                </p>
            </div>

            <div class="space-y-2">
                <label for="password" class="text-sm font-semibold text-slate-700">Contraseña</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 focus:border-red-500 focus:ring-red-500"
                    placeholder="Ingresa tu contraseña"
                >
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-200 transition hover:bg-red-500"
                >
                    Eliminar definitivamente
                </button>
            </div>
        </form>
    </x-modal>
</div>
