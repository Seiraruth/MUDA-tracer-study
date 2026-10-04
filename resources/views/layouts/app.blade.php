<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tracer Study Alumni SMK')</title>

    <!-- Google Fonts: Plus Jakarta Sans (display) & Nunito (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Plus Jakarta Sans"', 'Nunito', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>

    <!-- FontAwesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Nunito', 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased flex flex-col">

    <!-- Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-4 py-3">
                <!-- Logo & Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0" aria-label="Tracer Study SMK — Beranda">
                    <span class="w-10 h-10 rounded-xl bg-white ring-1 ring-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                        <img src="{{ asset('images/LOGO MD2.jpeg') }}" alt="LOGO MD2" class="w-10 h-10 object-contain">
                    </span>
                    <span class="leading-tight text-left">
                        <span class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">SMK Muhammadiyah 2 Cikampek</span>
                        <span class="block text-base font-bold text-slate-900">Tracer Study</span>
                    </span>
                </a>

                <!-- Navigation Links (mobile dropdown + desktop inline) -->
                <nav id="navbarMenu" aria-label="Navigasi utama"
                    class="hidden absolute inset-x-0 top-full bg-white border-b border-slate-200 shadow-sm flex flex-col gap-1 p-2
                           md:flex md:static md:bg-transparent md:border-b-0 md:shadow-none md:flex-row md:items-center md:gap-1 md:p-0">
                    <a href="{{ route('home') }}" class="rounded-lg px-3.5 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">Beranda</a>
                    <a href="{{ route('loker.index') }}" class="rounded-lg px-3.5 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('loker.index') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">Bursa Kerja (BKK)</a>
                    <a href="{{ url('/admin') }}" target="_blank" class="rounded-lg px-3.5 py-2 text-sm font-semibold transition-colors text-slate-600 hover:bg-slate-100 hover:text-slate-900">Portal Admin</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-2.5">
                    @if(session()->has('alumni_id'))
                        <a href="{{ route('tracer.kuesioner') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-bold text-sm transition-colors focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                            <i class="fa-solid fa-clipboard-check"></i> Kuesioner
                        </a>
                        <a href="{{ route('alumni.logout') }}" class="px-3.5 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 font-semibold text-sm transition-colors">
                            Keluar
                        </a>
                    @else
                        <a href="{{ route('alumni.login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-bold text-sm transition-colors shadow-sm focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                            <i class="fa-solid fa-right-to-bracket"></i> Masuk Alumni
                        </a>
                    @endif

                    <!-- Mobile menu toggle -->
                    <button id="navToggle" type="button" aria-label="Abrir menu navigasi" aria-expanded="false" aria-controls="navbarMenu"
                        class="md:hidden w-10 h-10 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

        <!-- Alert Notifications -->
        @if(session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">
                <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-blue-600 text-base"></i>
                    <span>{{ session('info') }}</span>
                </div>
            </div>
        @endif

        <!-- Main Content Wrapper -->
        <main class="flex-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-7 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Tracer Study SMK &copy; {{ date('Y') }} — Sistem Informasi Alumni & Indikator BMW
                </div>
                <nav aria-label="Footer" class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="transition-colors hover:text-slate-900">Beranda</a>
                    <span class="text-slate-300 select-none">•</span>
                    <a href="{{ route('loker.index') }}" class="transition-colors hover:text-slate-900">Lowongan BKK</a>
                    <span class="text-slate-300 select-none">•</span>
                    <a href="{{ url('/admin') }}" target="_blank" class="transition-colors hover:text-slate-900">Admin BKK</a>
                </nav>
            </div>
        </footer>

    <script>
        // Mobile navigation toggle (vanilla JS, no library)
        document.addEventListener('DOMContentLoaded', function () {
            var btn = document.getElementById('navToggle');
            var menu = document.getElementById('navbarMenu');
            if (!btn || !menu) return;

            var setIcon = function (open) {
                var icon = btn.querySelector('i');
                if (!icon) return;
                icon.classList.toggle('fa-bars', !open);
                icon.classList.toggle('fa-xmark', open);
            };

            btn.addEventListener('click', function () {
                var open = menu.classList.toggle('hidden') === false;
                btn.setAttribute('aria-expanded', String(open));
                setIcon(open);
            });

            Array.prototype.forEach.call(menu.querySelectorAll('a'), function (link) {
                link.addEventListener('click', function () {
                    menu.classList.add('hidden');
                    btn.setAttribute('aria-expanded', 'false');
                    setIcon(false);
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
