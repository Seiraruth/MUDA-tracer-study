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
                    colors: {
                        brand: {
                            50: '#F1F7EE',
                            100: '#E4F2DB',
                            200: '#CBE8B8',
                            300: '#A9D88E',
                            400: '#7FC25D',
                            DEFAULT: '#5CB92A',
                            600: '#4CA324',
                            700: '#3D821F',
                            800: '#34681F',
                            900: '#2C571E',
                        },
                        cta: {
                            DEFAULT: '#F4C430',
                            600: '#D7A414',
                            700: '#B1850F',
                        },
                        page: '#F7F9F6',
                    },
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
<body class="min-h-screen bg-page text-slate-900 antialiased flex flex-col">

    <!-- Navbar -->
    <header class="bg-brand border-b border-brand-700/40 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-4 py-3">
                <!-- Logo & Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0" aria-label="Tracer Study SMK — Beranda">
                    <span class="w-10 h-10 rounded-xl bg-white ring-1 ring-white/40 flex items-center justify-center overflow-hidden shrink-0">
                        <img src="{{ asset('images/logo_muda.png') }}" alt="logo muda" class="w-10 h-10 object-contain">
                    </span>
                    <span class="leading-tight text-left">
                        <span class="block text-[11px] font-semibold uppercase tracking-wider text-white/70">SMK Muhammadiyah 2 Cikampek</span>
                        <span class="block text-base font-bold text-white">Tracer Study</span>
                    </span>
                </a>

                <!-- Navigation Links (mobile dropdown + desktop inline) -->
                <nav id="navbarMenu" aria-label="Navigasi utama"
                    class="hidden absolute inset-x-0 top-full bg-brand border-b border-brand-800/40 shadow-sm flex flex-col gap-1 p-2
                           md:flex md:static md:bg-transparent md:border-b-0 md:shadow-none md:flex-row md:items-center md:gap-1 md:p-0">
                    <a href="{{ route('home') }}" class="rounded-lg px-3.5 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'bg-white/15 text-white' : 'text-white/85 hover:bg-white/10 hover:text-white' }}">Beranda</a>
                    <a href="{{ route('loker.index') }}" class="rounded-lg px-3.5 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('loker.index') ? 'bg-white/15 text-white' : 'text-white/85 hover:bg-white/10 hover:text-white' }}">BKK</a>
                    <span class="rounded-lg px-3.5 py-2 text-sm font-semibold text-white/50 cursor-default select-none" title="Statistik alumni akan tersedia segera">Statistik</span>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-2.5">
                    @if(session()->has('alumni_id'))
                        <a href="{{ route('tracer.kuesioner') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-cta text-slate-900 hover:bg-cta-600 font-bold text-sm transition-colors focus-visible:ring-2 focus-visible:ring-cta-700 focus-visible:ring-offset-2">
                            <i class="fa-solid fa-clipboard-check"></i> Kuesioner
                        </a>
                        <a href="{{ route('alumni.logout') }}" class="px-3.5 py-2 rounded-lg border border-white/40 text-white hover:bg-white/15 font-semibold text-sm transition-colors">
                            Keluar
                        </a>
                    @else
                        <a href="{{ route('alumni.login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-cta text-slate-900 hover:bg-cta-600 font-bold text-sm transition-colors shadow-sm focus-visible:ring-2 focus-visible:ring-cta-700 focus-visible:ring-offset-2">
                            <i class="fa-solid fa-right-to-bracket"></i> Masuk Alumni
                        </a>
                    @endif

                    <!-- Mobile menu toggle -->
                    <button id="navToggle" type="button" aria-label="Abrir menu navigasi" aria-expanded="false" aria-controls="navbarMenu"
                        class="md:hidden w-10 h-10 rounded-lg border border-white/40 text-white hover:bg-white/15 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

        <!-- Alert Notifications -->
        @if(session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">
                <div class="p-3.5 rounded-xl bg-brand-50 border border-brand-200 text-brand-900 text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-brand-600 text-base"></i>
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">

                    <!-- Column 1: School Identity -->
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-14 h-14 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center overflow-hidden shrink-0">
                                <img src="{{ asset('images/logo_muda.png') }}" alt="Logo SMK Muhammadiyah 2 Cikampek" class="w-14 h-14 object-contain">
                            </span>
                            <div class="leading-tight">
                                <p class="font-display text-base font-extrabold text-[#1F2937]">SMK Muhammadiyah 2 Cikampek</p>
                                <p class="text-sm font-semibold text-brand-700 mt-0.5">Tracer Study Alumni</p>
                            </div>
                        </div>
                        <p class="text-sm text-[#6B7280] leading-relaxed">
                            Sistem informasi penelusuran lulusan untuk memantau indikator keberhasilan alumni
                            (Bekerja, Melanjutkan Kuliah, Wirausaha).
                        </p>
                    </div>

                    <!-- Column 2: Quick Links -->
                    <div>
                        <h3 class="font-display text-sm font-bold uppercase tracking-wider text-[#1F2937] mb-4">Quick Links</h3>
                        <ul class="space-y-2.5">
                            <li>
                                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-[#6B7280] hover:text-brand transition-colors">
                                    <i class="fa-solid fa-chevron-right text-[10px] text-brand-400" aria-hidden="true"></i> Beranda
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loker.index') }}" class="inline-flex items-center gap-2 text-sm text-[#6B7280] hover:text-brand transition-colors">
                                    <i class="fa-solid fa-chevron-right text-[10px] text-brand-400" aria-hidden="true"></i> BKK
                                </a>
                            </li>
                            <li>
                                <span class="inline-flex items-center gap-2 text-sm text-[#6B7280] cursor-default select-none">
                                    <i class="fa-solid fa-chevron-right text-[10px] text-brand-400" aria-hidden="true"></i> Statistik
                                </span>
                            </li>
                            <li>
                                <a href="{{ route('alumni.login') }}" class="inline-flex items-center gap-2 text-sm text-[#6B7280] hover:text-brand transition-colors">
                                    <i class="fa-solid fa-chevron-right text-[10px] text-brand-400" aria-hidden="true"></i> Login Alumni
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 3: Information -->
                    <div>
                        <h3 class="font-display text-sm font-bold uppercase tracking-wider text-[#1F2937] mb-4">Information</h3>
                        <ul class="space-y-2.5">
                            <li>
                                <a href="{{ session()->has('alumni_id') ? route('tracer.kuesioner') : route('alumni.login') }}" class="inline-flex items-center gap-2 text-sm text-[#6B7280] hover:text-brand transition-colors">
                                    <i class="fa-solid fa-chevron-right text-[10px] text-brand-400" aria-hidden="true"></i> Kuesioner Alumni
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loker.index') }}" class="inline-flex items-center gap-2 text-sm text-[#6B7280] hover:text-brand transition-colors">
                                    <i class="fa-solid fa-chevron-right text-[10px] text-brand-400" aria-hidden="true"></i> Lowongan BKK
                                </a>
                            </li>
                            <li>
                                <span class="inline-flex items-center gap-2 text-sm text-[#6B7280] cursor-default select-none">
                                    <i class="fa-solid fa-chevron-right text-[10px] text-brand-400" aria-hidden="true"></i> Statistik Alumni
                                </span>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 4: Social Media -->
                    <div>
                        <h3 class="font-display text-sm font-bold uppercase tracking-wider text-[#1F2937] mb-4">Social Media</h3>
                        <ul class="space-y-2.5">
                            <li class="inline-flex items-center gap-2.5 text-sm text-[#6B7280] cursor-default select-none">
                                <i class="fa-brands fa-instagram text-brand-600 w-4 text-center" aria-hidden="true"></i> Instagram
                            </li>
                            <li class="inline-flex items-center gap-2.5 text-sm text-[#6B7280] cursor-default select-none">
                                <i class="fa-brands fa-youtube text-brand-600 w-4 text-center" aria-hidden="true"></i> YouTube
                            </li>
                            <li class="inline-flex items-center gap-2.5 text-sm text-[#6B7280] cursor-default select-none">
                                <i class="fa-brands fa-tiktok text-brand-600 w-4 text-center" aria-hidden="true"></i> TikTok
                            </li>
                            <li class="inline-flex items-center gap-2.5 text-sm text-[#6B7280] cursor-default select-none">
                                <i class="fa-brands fa-facebook-f text-brand-600 w-4 text-center" aria-hidden="true"></i> Facebook
                            </li>
                        </ul>
                        <p class="text-xs text-[#6B7280] mt-4 leading-relaxed">
                            Akun sosial media sekolah belum ditambahkan.
                        </p>
                    </div>

                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-slate-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                    <p class="text-center text-xs text-[#6B7280]">
                        &copy; {{ date('Y') }} SMK Muhammadiyah 2 Cikampek. All rights reserved.
                    </p>
                </div>
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
