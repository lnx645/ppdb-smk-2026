@php $mobile = $mobile ?? false; @endphp

@if(session('admin'))
    <a href="{{ route('admin.dashboard') }}" class="{{ $mobile ? 'block px-3 py-2.5 rounded-lg' : 'px-3.5 py-2 rounded-lg' }} text-slate-600 hover:text-brand-700 hover:bg-brand-50 transition">Dashboard Admin</a>
    <form action="{{ route('admin.logout') }}" method="POST" class="{{ $mobile ? 'pt-1' : 'inline' }}">
        @csrf
        <button type="submit" class="{{ $mobile ? 'w-full text-left px-3 py-2.5 rounded-lg bg-slate-900' : 'px-3.5 py-2 rounded-lg bg-slate-900' }} text-white hover:bg-slate-700 transition">Logout</button>
    </form>
@elseif(Auth::check())
    <a href="{{ route('siswa.dashboard') }}" class="{{ $mobile ? 'block px-3 py-2.5 rounded-lg' : 'px-3.5 py-2 rounded-lg' }} text-slate-600 hover:text-brand-700 hover:bg-brand-50 transition">Dashboard Saya</a>
    <a href="{{ route('ppdb.form') }}" class="{{ $mobile ? 'block px-3 py-2.5 rounded-lg text-center' : 'px-4 py-2 rounded-lg' }} bg-brand-600 text-white shadow-lg shadow-brand-600/25 hover:bg-brand-700 transition">Daftar PPDB</a>
    <form action="{{ route('logout') }}" method="POST" class="{{ $mobile ? 'pt-1' : 'inline' }}">
        @csrf
        <button type="submit" class="{{ $mobile ? 'w-full text-left px-3 py-2.5 rounded-lg' : 'px-3.5 py-2 rounded-lg' }} bg-slate-900 text-white hover:bg-slate-700 transition">Keluar</button>
    </form>
@else
    <a href="{{ route('ppdb.index') }}" class="{{ $mobile ? 'block px-3 py-2.5 rounded-lg' : 'px-3.5 py-2 rounded-lg' }} text-slate-600 hover:text-brand-700 hover:bg-brand-50 transition">Beranda</a>
    <a href="{{ route('login') }}" class="{{ $mobile ? 'block px-3 py-2.5 rounded-lg' : 'px-3.5 py-2 rounded-lg' }} text-slate-600 hover:text-brand-700 hover:bg-brand-50 transition">Masuk</a>
    <a href="{{ route('admin.login') }}" class="{{ $mobile ? 'block px-3 py-2.5 rounded-lg text-xs' : 'px-3.5 py-2 rounded-lg text-xs' }} text-slate-400 hover:text-brand-700 hover:bg-brand-50 transition">Admin</a>
    <a href="{{ route('register') }}" class="{{ $mobile ? 'block px-3 py-2.5 rounded-lg text-center' : 'px-4 py-2 rounded-lg' }} bg-brand-600 text-white shadow-lg shadow-brand-600/25 hover:bg-brand-700 transition">Daftar Akun</a>
@endif
