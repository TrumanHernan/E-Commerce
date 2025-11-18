<x-app-layout>
    @php
        $initial = strtoupper(mb_substr($user->name ?? 'N', 0, 1));
        $memberSince = $user->created_at
            ? $user->created_at->copy()->locale(app()->getLocale())->translatedFormat('d \\d\\e F \\d\\e Y')
            : null;
        $pedidosCount = $user->pedidos_count ?? $user->pedidos()->count();
        $favoritosCount = $user->favoritos_count ?? $user->favoritos()->count();
        $cartItems = optional($user->carrito)->items
            ? $user->carrito->items->sum('cantidad')
            : 0;
        $emailVerified = ! is_null($user->email_verified_at);
    @endphp

    <section class="relative isolate overflow-hidden bg-gradient-to-r from-[#11BF6E] to-emerald-600 text-white">
        <div class="max-w-6xl mx-auto px-6 py-12 lg:px-10">
            <p class="text-xs font-semibold uppercase tracking-[0.5em] text-white/80">Panel personal</p>
            <h1 class="mt-3 text-4xl font-semibold lg:text-5xl">Mi perfil</h1>
            <p class="mt-4 max-w-2xl text-lg text-white/90">
                Consulta tu información, revisa tus métricas y mantén tu cuenta segura con el estilo fresco de NutriShop.
            </p>
        </div>
        <div class="pointer-events-none absolute -right-16 top-0 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
    </section>

    <section class="-mt-16 bg-gray-50 pb-16 pt-4 sm:pt-8">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 sm:px-6 lg:grid-cols-[1.05fr,1.95fr] lg:px-8">
            <div class="rounded-3xl border border-emerald-100 bg-white p-8 shadow-xl">
                <div class="text-center">
                    <div class="mx-auto flex h-28 w-28 items-center justify-center rounded-full bg-emerald-50 text-4xl font-semibold text-emerald-600 shadow-inner">
                        {{ $initial }}
                    </div>
                    <h2 class="mt-6 text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-slate-500">{{ $user->email }}</p>
                    <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-white/30 bg-emerald-500/10 px-4 py-1 text-xs font-semibold uppercase tracking-widest text-white">
                        {{ $emailVerified ? 'Correo verificado' : 'Verificación pendiente' }}
                    </div>
                </div>

                <div class="mt-8 space-y-4 rounded-2xl bg-slate-50 p-5">
                    <div class="flex items-center justify-between text-sm text-slate-500">
                        <span class="font-semibold text-slate-600">Miembro desde</span>
                        <span class="text-base font-semibold text-slate-900">{{ $memberSince ?? '---' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-slate-500">
                        <span class="font-semibold text-slate-600">Rol</span>
                        <span class="text-base font-semibold text-slate-900">{{ $user->rol === 'admin' ? 'Administrador' : 'Cliente' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-slate-500">
                        <span class="font-semibold text-slate-600">Última actualización</span>
                        <span class="text-base font-semibold text-slate-900">{{ optional($user->updated_at)->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4 text-center">
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Pedidos</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-800">{{ $pedidosCount }}</p>
                        <p class="text-xs text-emerald-600">completados</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 bg-white p-4 text-center shadow">
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Favoritos</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-800">{{ $favoritosCount }}</p>
                        <p class="text-xs text-emerald-600">productos guardados</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 bg-white p-4 text-center shadow">
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Carrito</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-800">{{ $cartItems }}</p>
                        <p class="text-xs text-emerald-600">artículos</p>
                    </div>
                </div>

                <div class="mt-8 rounded-2xl border border-dashed border-emerald-200 bg-white/80 p-4 text-sm text-slate-600">
                    Mantén tu información al día para disfrutar de entregas rápidas y experiencias personalizadas.
                </div>

                <div class="mt-6 text-center">
                    <a href="#editar-perfil" class="inline-flex items-center justify-center rounded-2xl bg-[#11BF6E] px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-200 transition hover:scale-[1.02]">
                        Editar perfil
                    </a>
                </div>
            </div>

            <div class="space-y-8">
                <div id="editar-perfil" class="rounded-3xl border border-emerald-100 bg-white p-8 shadow-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="rounded-3xl border border-emerald-100 bg-white p-8 shadow-xl">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="rounded-3xl border border-red-100 bg-white p-8 shadow-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
