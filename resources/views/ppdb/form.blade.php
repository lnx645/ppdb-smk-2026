@extends('layouts.app')
@php $editing = isset($applicant) && $applicant; @endphp
@section('title', $editing ? 'Perbarui Pendaftaran — PPDB SMK' : 'Form Pendaftaran — PPDB SMK')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10 md:py-14">
    <div class="text-center mb-8">
        <span class="inline-flex items-center gap-2 bg-brand-50 text-brand-700 rounded-full px-4 py-1.5 text-xs font-bold">
            {{ $editing ? '✏️ Perbarui Data Pendaftaran' : '📝 Formulir PPDB ' . now()->year . '/' . (now()->year + 1) }}
        </span>
        <h1 class="mt-4 text-3xl font-extrabold tracking-tight">{{ $editing ? 'Perbarui Formulir Pendaftaran' : 'Formulir Pendaftaran Siswa Baru' }}</h1>
        <p class="mt-2 text-sm text-slate-500">Isi data dengan benar. Field bertanda <span class="text-rose-500 font-bold">*</span> wajib diisi.</p>
    </div>

    @if($editing && $applicant->status === 'revision' && $applicant->notes)
        <div class="bg-violet-50 border border-violet-200 text-violet-800 px-5 py-4 rounded-2xl mb-6">
            <p class="font-bold text-sm">⚠️ Panitia meminta perbaikan berkas:</p>
            <p class="text-sm mt-1">{{ $applicant->notes }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl mb-6">
            <p class="font-bold text-sm mb-1">Ada data yang perlu diperbaiki:</p>
            <ul class="list-disc list-inside text-sm space-y-0.5">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $editing ? route('ppdb.update') : route('ppdb.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($editing) @method('PUT') @endif

        <!-- Data Pribadi -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            <div class="flex items-center gap-3 mb-5">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-700 grid place-items-center font-extrabold">1</span>
                <div>
                    <h2 class="font-extrabold">Data Pribadi</h2>
                    <p class="text-xs text-slate-500">Identitas calon peserta didik</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $applicant->name ?? '') }}" required placeholder="Sesuai ijazah"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">NISN <span class="text-rose-500">*</span></label>
                    <input type="text" name="nisn" value="{{ old('nisn', $applicant->nisn ?? '') }}" required maxlength="10" placeholder="10 digit"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $applicant->email ?? '') }}" required placeholder="nama@email.com"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">No. HP <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $applicant->phone ?? '') }}" required placeholder="08xxxxxxxxxx"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select name="gender" required class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                        <option value="">-- Pilih --</option>
                        <option value="L" @selected(old('gender', $applicant->gender ?? '')=='L')>Laki-laki</option>
                        <option value="P" @selected(old('gender', $applicant->gender ?? '')=='P')>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Tanggal Lahir <span class="text-rose-500">*</span></label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $applicant?->birth_date?->format('Y-m-d') ?? '') }}" required
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                    <p class="text-[11px] text-slate-400 mt-1">Usia maksimal 21 tahun pada 1 Juli tahun penerimaan.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Alamat Domisili <span class="text-rose-500">*</span></label>
                    <textarea name="address" required rows="3" placeholder="Alamat lengkap sesuai Kartu Keluarga"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">{{ old('address', $applicant->address ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Data Pendidikan -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            <div class="flex items-center gap-3 mb-5">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-700 grid place-items-center font-extrabold">2</span>
                <div>
                    <h2 class="font-extrabold">Data Pendidikan</h2>
                    <p class="text-xs text-slate-500">Asal sekolah & pilihan jurusan</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Asal Sekolah</label>
                    <input type="text" name="previous_school" value="{{ old('previous_school', $applicant->previous_school ?? '') }}" placeholder="SMP/MTs asal"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Nomor SHUN (Opsional)</label>
                    <input type="text" name="shun" value="{{ old('shun', $applicant->shun ?? '') }}" maxlength="50" placeholder="Nomor SHUN SMP"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Jurusan Pilihan <span class="text-rose-500">*</span></label>
                    <select name="major" required class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach($majors as $code => $label)
                            <option value="{{ $code }}" @selected(old('major', $applicant->major ?? '')==$code)>{{ $code }} — {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Jalur Pendaftaran <span class="text-rose-500">*</span></label>
                    <select name="jalur" required class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                        <option value="">-- Pilih Jalur --</option>
                        @foreach(App\Models\Applicant::JALUR as $k => $v)
                            <option value="{{ $k }}" @selected(old('jalur', $applicant->jalur ?? '')==$k)>{{ $v }}</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Zonasi 90% · Prestasi 5% · Perpindahan 5%</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Tahun Penerimaan <span class="text-rose-500">*</span></label>
                    <input type="number" name="admission_year" value="{{ old('admission_year', $applicant->admission_year ?? now()->year) }}" min="2000" max="{{ now()->year + 1 }}" required
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                </div>
            </div>
        </div>

        <!-- Berkas -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            <div class="flex items-center gap-3 mb-5">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-700 grid place-items-center font-extrabold">3</span>
                <div>
                    <h2 class="font-extrabold">Berkas Pendukung</h2>
                    <p class="text-xs text-slate-500">Format PDF/JPG/PNG, maks. 2 MB</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Upload Ijazah / STT SMP</label>
                    @if($editing && $applicant->ijazah_path)
                        <p class="text-xs text-emerald-700 font-semibold mb-2">✅ Sudah ada —
                            <a href="{{ asset('storage/' . $applicant->ijazah_path) }}" target="_blank" class="text-brand-700 hover:underline">lihat berkas</a>
                        </p>
                    @endif
                    <input type="file" name="ijazah" accept=".pdf,image/*"
                        class="block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2.5 file:text-xs file:font-bold file:text-brand-700 hover:file:bg-brand-100 transition">
                    @if($editing)<p class="text-[11px] text-slate-400 mt-1">Kosongkan jika tidak diganti.</p>@endif
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Bukti Prestasi (Opsional)</label>
                    @if($editing && $applicant->prestasi_proof)
                        <p class="text-xs text-emerald-700 font-semibold mb-2">✅ Sudah ada —
                            <a href="{{ asset('storage/' . $applicant->prestasi_proof) }}" target="_blank" class="text-brand-700 hover:underline">lihat berkas</a>
                        </p>
                    @endif
                    <input type="file" name="prestasi_proof" accept=".pdf,image/*"
                        class="block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2.5 file:text-xs file:font-bold file:text-brand-700 hover:file:bg-brand-100 transition">
                    <p class="text-[11px] text-slate-400 mt-1">Untuk pendaftar jalur prestasi.</p>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold py-4 shadow-xl shadow-brand-600/25 transition">
            {{ $editing ? 'Simpan Perubahan & Kirim Ulang' : 'Kirim Pendaftaran' }} →
        </button>
        @if($editing)
            <p class="text-center text-xs text-slate-400">
                <a href="{{ route('siswa.dashboard') }}" class="font-semibold hover:text-brand-600">← Batal dan kembali ke dashboard</a>
            </p>
        @else
            <p class="text-center text-xs text-slate-400">Dengan mengirim, kamu menyatakan data yang diisi benar.</p>
        @endif
    </form>
</div>
@endsection
