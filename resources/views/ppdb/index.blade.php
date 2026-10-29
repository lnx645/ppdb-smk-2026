@extends('layouts.app')
@section('title', 'PPDB SMK — Pendaftaran Peserta Didik Baru')

@section('content')
<!-- HERO -->
<section class="hero-gradient relative overflow-hidden text-white">
    <div class="hero-grid absolute inset-0"></div>
    <div class="max-w-6xl mx-auto px-4 py-16 md:py-24 relative">
        <div class="max-w-2xl fade-up">
            <span class="inline-flex items-center gap-2 bg-white/15 border border-white/20 rounded-full px-4 py-1.5 text-xs font-semibold tracking-wide backdrop-blur">
                🎓 Tahun Ajaran {{ now()->year }}/{{ now()->year + 1 }}
            </span>
            <h1 class="mt-5 text-4xl md:text-5xl font-extrabold leading-tight tracking-tight">
                Daftar Sekarang,<br>Raih Masa Depanmu di SMK!
            </h1>
            <p class="mt-4 text-white/85 text-base md:text-lg leading-relaxed">
                Pendaftaran Peserta Didik Baru (PPDB) SMK secara online — cepat, transparan, dan sesuai standar Kemdikbud.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ auth()->check() ? route('ppdb.form') : route('register') }}" class="px-6 py-3.5 rounded-xl bg-white text-brand-700 font-bold shadow-xl shadow-brand-900/20 hover:bg-brand-50 transition">
                    Mulai Pendaftaran →
                </a>
                <a href="#alur" class="px-6 py-3.5 rounded-xl bg-white/10 border border-white/25 text-white font-semibold hover:bg-white/20 transition">
                    Lihat Alur PPDB
                </a>
            </div>
            <div class="mt-10 flex flex-wrap gap-6 text-sm">
                <div><div class="text-2xl font-extrabold">3</div><div class="text-white/70">Jalur Pendaftaran</div></div>
                <div class="w-px bg-white/20"></div>
                <div><div class="text-2xl font-extrabold">5</div><div class="text-white/70">Kompetensi Keahlian</div></div>
                <div class="w-px bg-white/20"></div>
                <div><div class="text-2xl font-extrabold">100%</div><div class="text-white/70">Online</div></div>
            </div>
        </div>
    </div>
</section>

<!-- ALUR -->
<section id="alur" class="max-w-6xl mx-auto px-4 py-16 scroll-mt-20">
    <div class="text-center max-w-xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Alur Pendaftaran</h2>
        <p class="mt-2 text-slate-500 text-sm">Sesuai tahapan PPDB Permendikbud No. 51 Tahun 2018.</p>
    </div>
    <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach([
            ['1','Buat Akun','Daftar akun siswa dengan email aktif sebagai langkah awal.'],
            ['2','Isi Formulir','Lengkapi data pribadi, asal sekolah, dan pilihan jurusan.'],
            ['3','Verifikasi & Seleksi','Panitia memverifikasi berkas dan melakukan seleksi sesuai jalur.'],
            ['4','Pengumuman','Hasil seleksi dapat dipantau di dashboard siswa.'],
            ['5','Daftar Ulang','Calon yang diterima melakukan daftar ulang di sekolah.'],
        ] as [$no,$judul,$desc])
            <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/60 transition">
                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 font-extrabold grid place-items-center">{{ $no }}</div>
                <h3 class="mt-4 font-bold">{{ $judul }}</h3>
                <p class="mt-1.5 text-sm text-slate-500 leading-relaxed">{{ $desc }}</p>
            </div>
        @endforeach
    </div>
</section>

<!-- JURUSAN -->
<section class="bg-white border-y border-slate-200">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center max-w-xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Kompetensi Keahlian</h2>
            <p class="mt-2 text-slate-500 text-sm">Pilih jurusan yang paling sesuai dengan minat dan bakatmu.</p>
        </div>
        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $icons = ['TKJ'=>'🖥️','RPL'=>'💻','MM'=>'🎨','AK'=>'📊','OTKP'=>'🗂️'];
            @endphp
            @foreach(App\Models\Applicant::MAJORS as $code => $label)
                <a href="{{ auth()->check() ? route('ppdb.form') : route('register') }}" class="group bg-slate-50 border border-slate-200 rounded-2xl p-6 hover:border-brand-300 hover:bg-brand-50/50 transition">
                    <div class="flex items-start justify-between">
                        <span class="text-3xl">{{ $icons[$code] ?? '📚' }}</span>
                        <span class="text-xs font-bold text-brand-700 bg-brand-100 rounded-full px-3 py-1">{{ $code }}</span>
                    </div>
                    <h3 class="mt-4 font-bold group-hover:text-brand-700 transition">{{ $label }}</h3>
                    <p class="mt-1 text-sm text-slate-500">Klik untuk mendaftar jurusan ini →</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="max-w-6xl mx-auto px-4 py-16">
    <div class="rounded-3xl hero-gradient text-white p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
        <div class="hero-grid absolute inset-0"></div>
        <div class="relative">
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Siap jadi bagian dari kami?</h2>
            <p class="mt-2 text-white/80 text-sm">Pendaftaran gratis, cukup isi formulir online dalam 5 menit.</p>
        </div>
        <a href="{{ auth()->check() ? route('ppdb.form') : route('register') }}" class="relative px-6 py-3.5 rounded-xl bg-white text-brand-700 font-bold shadow-xl hover:bg-brand-50 transition whitespace-nowrap">
            Daftar Sekarang →
        </a>
    </div>
</section>
@endsection
