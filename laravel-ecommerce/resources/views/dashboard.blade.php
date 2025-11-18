<x-app-layout>
    @php
        $user = Auth::user()->loadCount(['pedidos', 'favoritos']);
        $user->load(['carrito.items']);
        $cartItems = $user->carrito?->items?->sum('cantidad') ?? 0;
        $memberSince = optional($user->created_at)?->copy()->locale(app()->getLocale())->translatedFormat('d \\d\\e F \\d\\e Y');
        $hour = now()->timezone(config('app.timezone'))->format('H');
        $greeting = match (true) {
            $hour < 12 => 'Buenos días',
            $hour < 18 => 'Buenas tardes',
            default => 'Buenas noches',
        };
        $recommendations = [
            [
                'title' => 'Completar mis datos',
                'description' => 'Actualiza tu información personal para que tus envíos lleguen a la dirección correcta.',
                'route' => route('profile.edit'),
                'emoji' => '📝',
            ],
            [
                'title' => 'Revisar pedidos',
                'description' => 'Consulta el estado de tus compras y descarga los comprobantes.',
                'route' => route('pedidos.index'),
                'emoji' => '📦',
            ],
            [
                'title' => 'Organizar favoritos',
                'description' => 'Guarda los suplementos que más te gustan para comprarlos más rápido.',
                'route' => route('favoritos.index'),
                'emoji' => '⭐',
            ],
        ];
    @endphp

    <section class="relative isolate overflow-hidden bg-gradient-to-r from-[#0fa968] to-emerald-600 text-white">
        <div class="max-w-6xl mx-auto px-6 py-12 lg:px-10">
            <p class="text-xs font-semibold uppercase tracking-[0.5em] text-white/80">Panel personal</p>
            <h1 class="mt-4 text-4xl font-semibold lg:text-5xl">
                {{ $greeting }}, {{ $user->name }}
            </h1>
            <p class="mt-4 max-w-2xl text-lg text-white/90">
                Aquí puedes monitorear tus pedidos, tus favoritos y el avance de tu cuenta con NutriShop.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white/15 px-6 py-3 text-sm font-semibold uppercase tracking-widest text-white backdrop-blur transition hover:bg-white/25">
                    <span>Ver mi perfil</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 4.5 21 12l-7.5 7.5m-9-15L12 12l-6 6" />
                    </svg>
                </a>
                <a href="{{ route('productos.index') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white text-emerald-700 px-6 py-3 text-sm font-semibold shadow-lg shadow-emerald-200 transition hover:-translate-y-0.5">
                    Explorar productos
                </a>
            </div>
        </div>
        <div class="pointer-events-none absolute -right-10 top-6 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
    </section>

    <section class="-mt-16 bg-gray-50 pb-20 pt-6 sm:pt-10">
        <div class="mx-auto max-w-6xl space-y-10 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-lg shadow-emerald-50">
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Pedidos</p>
                    <p class="mt-3 text-4xl font-bold text-slate-900">{{ $user->pedidos_count }}</p>
                    <p class="text-sm text-slate-500">Órdenes registradas</p>
                </div>
                <div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-lg shadow-emerald-50">
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Favoritos</p>
                    <p class="mt-3 text-4xl font-bold text-slate-900">{{ $user->favoritos_count }}</p>
                    <p class="text-sm text-slate-500">Productos guardados</p>
                </div>
                <div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-lg shadow-emerald-50">
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Carrito</p>
                    <p class="mt-3 text-4xl font-bold text-slate-900">{{ $cartItems }}</p>
                    <p class="text-sm text-slate-500">Artículos por comprar</p>
                </div>
                <div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-lg shadow-emerald-50">
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Membresía</p>
                    <p class="mt-3 text-lg font-semibold text-slate-900">{{ $memberSince ?? 'Sin datos' }}</p>
                    <p class="text-sm text-slate-500">Antigüedad en NutriShop</p>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-[1.3fr,0.7fr]">
                <div class="space-y-6 rounded-3xl border border-emerald-100 bg-white p-8 shadow-xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.4em] text-emerald-500">¿Qué sigue?</p>
                    <h2 class="text-3xl font-semibold text-slate-900">Recomendaciones para hoy</h2>
                    <div class="space-y-4">
                        @foreach ($recommendations as $item)
                            <a href="{{ $item['route'] }}" class="flex items-start gap-4 rounded-2xl border border-slate-100 bg-slate-50/70 p-4 transition hover:border-emerald-200 hover:bg-white">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-2xl">
                                    {{ $item['emoji'] }}
                                </div>
                                <div>
                                    <p class="text-lg font-semibold text-slate-900">{{ $item['title'] }}</p>
                                    <p class="text-sm text-slate-500">{{ $item['description'] }}</p>
                                </div>
                                <div class="ms-auto hidden text-emerald-500 sm:block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-6 rounded-3xl border border-emerald-100 bg-gradient-to-br from-white via-white to-emerald-50 p-8 shadow-xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.4em] text-emerald-500">Accesos rápidos</p>
                    <div class="space-y-4 text-slate-600">
                        <a href="{{ route('carrito.index') }}" class="flex items-center justify-between rounded-2xl border border-slate-100 bg-white px-4 py-3 text-sm font-semibold transition hover:border-emerald-200">
                            <span>Ir a mi carrito</span>
                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-700">{{ $cartItems }}</span>
                        </a>
                        <a href="{{ route('favoritos.index') }}" class="flex items-center justify-between rounded-2xl border border-slate-100 bg-white px-4 py-3 text-sm font-semibold transition hover:border-emerald-200">
                            <span>Ver favoritos</span>
                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-700">{{ $user->favoritos_count }}</span>
                        </a>
                        <a href="{{ route('pedidos.index') }}" class="flex items-center justify-between rounded-2xl border border-slate-100 bg-white px-4 py-3 text-sm font-semibold transition hover:border-emerald-200">
                            <span>Mis pedidos</span>
                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-700">{{ $user->pedidos_count }}</span>
                        </a>
                    </div>

                    <div class="rounded-2xl border border-dashed border-emerald-200 bg-white/80 p-4 text-sm text-slate-500">
                        ¿Necesitas ayuda? Escríbenos y con gusto te asesoramos con tus compras.
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
