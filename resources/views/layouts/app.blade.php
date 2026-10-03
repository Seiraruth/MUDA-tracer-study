<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-800 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tracer Study Alumni SMK')</title>
    
    <!-- Google Fonts: Nunito & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Nunito', 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-800 text-slate-100 md:p-6 p-3 antialiased">

    <!-- Container Utama Sesuai Legacy -->
    <div class="max-w-7xl mx-auto border border-slate-600 rounded-lg bg-slate-800 min-h-[calc(100vh-3rem)] flex flex-col justify-between overflow-hidden shadow-xl">
        
        <!-- Header Navbar Legacy -->
        <header class="bg-slate-700 py-4 px-6 md:px-8 border-b border-slate-600">
            <div class="flex items-center justify-between">
                <!-- Logo & Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-lg bg-slate-600 border border-slate-500 flex items-center justify-center text-white font-bold text-lg">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h1 class="text-white text-lg font-bold uppercase leading-tight">
                            <span class="text-xs text-slate-300 font-semibold block">SMK</span>
                            Tracer Study
                        </h1>
                    </div>
                </a>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-200">
                    <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                    <a href="{{ route('loker.index') }}" class="hover:text-white transition-colors">Bursa Kerja (BKK)</a>
                    <a href="{{ url('/admin') }}" target="_blank" class="hover:text-white transition-colors text-slate-300">Portal Admin</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    @if(session()->has('alumni_id'))
                        <a href="{{ route('tracer.kuesioner') }}" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200 font-bold text-sm transition-all flex items-center gap-2">
                            <i class="fa-solid fa-clipboard-check"></i> Kuesioner
                        </a>
                        <a href="{{ route('alumni.logout') }}" class="px-3 py-2 rounded-lg bg-slate-600 hover:bg-slate-500 text-slate-200 font-semibold text-sm transition-all">
                            Keluar
                        </a>
                    @else
                        <a href="{{ route('alumni.login') }}" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200 font-bold text-sm transition-all flex items-center gap-2">
                            <i class="fa-solid fa-right-to-bracket"></i> Masuk Alumni
                        </a>
                    @endif
                </div>
            </div>
        </header>

        <!-- Alert Notifications -->
        @if(session('info'))
            <div class="px-6 pt-4">
                <div class="p-3.5 rounded-lg bg-slate-700 border border-slate-500 text-slate-200 text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-slate-300 text-base"></i>
                    <span>{{ session('info') }}</span>
                </div>
            </div>
        @endif

        <!-- Main Content Wrapper -->
        <main class="flex-grow p-4 md:p-8">
            @yield('content')
        </main>

        <!-- Footer Legacy -->
        <footer class="bg-slate-700 py-3 px-6 md:px-8 border-t border-slate-600 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-300 gap-2">
            <div>
                Tracer Study SMK &copy; {{ date('Y') }} — Sistem Informasi Alumni & Indikator BMW
            </div>
            <div class="flex items-center gap-4 text-slate-300">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                <span>•</span>
                <a href="{{ route('loker.index') }}" class="hover:text-white">Lowongan BKK</a>
                <span>•</span>
                <a href="{{ url('/admin') }}" target="_blank" class="hover:text-white">Admin BKK</a>
            </div>
        </footer>

    </div>

    @stack('scripts')
</body>
</html>
