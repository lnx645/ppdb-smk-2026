<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PPDB SMK')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui'] },
                    colors: {
                        brand: {
                            50:'#eef4ff',100:'#dce7ff',200:'#c0d4ff',300:'#94b7ff',
                            400:'#6190ff',500:'#3b66ff',600:'#2547f5',700:'#1c34e1',
                            800:'#1d2fb6',900:'#1d2f8f',950:'#161f57'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak]{display:none!important}
        .hero-gradient{background:linear-gradient(135deg,#1c34e1 0%,#3b66ff 55%,#6190ff 100%)}
        .hero-grid{background-image:linear-gradient(rgba(255,255,255,.07) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.07) 1px,transparent 1px);background-size:28px 28px}
        .fade-up{animation:fadeUp .5s ease both}
        @keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 min-h-screen flex flex-col">
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-lg border-b border-slate-200/70">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between gap-3">
            <a href="{{ route('ppdb.index') }}" class="flex items-center gap-2.5 shrink-0">
                <span class="w-9 h-9 rounded-xl hero-gradient text-white grid place-items-center font-extrabold shadow-lg shadow-brand-600/30">P</span>
                <span class="leading-tight">
                    <span class="block text-sm font-extrabold tracking-tight text-slate-900">PPDB SMK</span>
                    <span class="block text-[10px] font-medium text-slate-500">Sistem Penerimaan Siswa Baru</span>
                </span>
            </a>

            <!-- Desktop nav -->
            <nav class="hidden md:flex items-center gap-1.5 text-sm font-semibold">
                @include('partials.nav', ['mobile' => false])
            </nav>

            <!-- Mobile toggle -->
            <button type="button" onclick="document.getElementById('mobile-nav').classList.toggle('hidden')"
                class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition" aria-label="Buka menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Mobile nav -->
        <nav id="mobile-nav" class="hidden md:hidden border-t border-slate-200 bg-white px-4 py-3 space-y-1 text-sm font-semibold">
            @include('partials.nav', ['mobile' => true])
        </nav>
    </header>

    @if(session('success'))
        <div class="max-w-6xl mx-auto px-4 mt-5 w-full">
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium">
                <span class="text-lg">✅</span>{{ session('success') }}
            </div>
        </div>
    @endif

    <main class="flex-1 w-full">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="max-w-6xl mx-auto px-4 py-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-slate-500">
            <p class="font-semibold">© {{ date('Y') }} PPDB SMK · Sistem Penerimaan Peserta Didik Baru</p>
            <p class="text-xs">Mengacu pada Permendikbud No. 51 Tahun 2018</p>
        </div>
    </footer>
</body>
</html>
