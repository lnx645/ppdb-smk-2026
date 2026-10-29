@extends('layouts.app')
@php
    $applicant = $applicant ?? null;
    $editing = (bool) $applicant;
    $achievements = old('achievements', $editing ? ($applicant->achievements ?? []) : []);
    if (!is_array($achievements) || count($achievements) === 0) {
        $achievements = [[]];
    }
    $scholarships = old('scholarships', $editing ? ($applicant->scholarships ?? []) : []);
    if (!is_array($scholarships) || count($scholarships) === 0) {
        $scholarships = [[]];
    }
    $hasKip = (string) old('has_kip', $editing ? ($applicant->has_kip ? '1' : '0') : '0');
    $receivesKip = (string) old('receives_kip', $editing ? ($applicant->receives_kip ? '1' : '0') : '0');
    $nationality = old('nationality', $editing ? ($applicant->nationality ?? 'WNI') : 'WNI');
    $fieldClass = 'w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition';
    $labelClass = 'block text-xs font-bold text-slate-600 mb-1.5';
    $v = fn (string $field, $default = null) => old($field, $editing ? ($applicant?->{$field} ?? $default) : $default);
@endphp
@section('title', $editing ? 'Perbarui Pendaftaran — PPDB SMK' : 'Form Pendaftaran — PPDB SMK')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10 md:py-14">
    <div class="text-center mb-8">
        <span class="inline-flex items-center gap-2 bg-brand-50 text-brand-700 rounded-full px-4 py-1.5 text-xs font-bold">
            {{ $editing ? '✏️ Perbarui Data Pendaftaran' : '📝 Formulir PPDB ' . now()->year . '/' . (now()->year + 1) }}
        </span>
        <h1 class="mt-4 text-3xl font-extrabold tracking-tight">{{ $editing ? 'Perbarui Formulir Pendaftaran' : 'Formulir Pendaftaran Siswa Baru' }}</h1>
        <p class="mt-2 text-sm text-slate-500 max-w-2xl mx-auto">
            Formulir ini mengikuti <strong>Formulir Peserta Didik (F-PD)</strong> Dapodik/Kemdikbud. Isi data dengan benar.
            Field bertanda <span class="text-rose-500 font-bold">*</span> wajib diisi.
        </p>
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

    <form id="ppdb-form" action="{{ $editing ? route('ppdb.update') : route('ppdb.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($editing) @method('PUT') @endif

        <!-- 1. Data Pribadi -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            @include('partials.form-section-header', ['number' => '1', 'title' => 'Data Pribadi', 'subtitle' => 'Identitas calon peserta didik sesuai akta/ijazah'])
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="{{ $labelClass }}">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ $v('name') }}" required placeholder="Sesuai akta/ijazah"
                        class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">NISN <span class="text-rose-500">*</span></label>
                    <input type="text" name="nisn" value="{{ $v('nisn') }}" required maxlength="10" inputmode="numeric" pattern="[0-9]{10}" placeholder="10 digit angka"
                        class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">NIK / No. KITAS <span class="text-rose-500">*</span></label>
                    <input type="text" name="nik" value="{{ $v('nik') }}" required maxlength="20" inputmode="numeric" placeholder="16 digit (WNI)"
                        class="{{ $fieldClass }}">
                    <p class="text-[11px] text-slate-400 mt-1">WNI: 16 digit angka. WNA: isi nomor KITAS.</p>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Nomor Kartu Keluarga (KK) <span class="text-rose-500">*</span></label>
                    <input type="text" name="kk_number" value="{{ $v('kk_number') }}" required maxlength="16" inputmode="numeric" pattern="[0-9]{16}" placeholder="16 digit"
                        class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">No. Registrasi Akta Kelahiran</label>
                    <input type="text" name="birth_certificate_no" value="{{ $v('birth_certificate_no') }}" maxlength="50" placeholder="Sesuai akta kelahiran"
                        class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select name="gender" required class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        <option value="L" @selected((string) $v('gender') === 'L')>Laki-laki</option>
                        <option value="P" @selected((string) $v('gender') === 'P')>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Tempat Lahir <span class="text-rose-500">*</span></label>
                    <input type="text" name="birth_place" value="{{ $v('birth_place') }}" required maxlength="100" placeholder="Kota/Kabupaten"
                        class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Tanggal Lahir <span class="text-rose-500">*</span></label>
                    <input type="date" name="birth_date" value="{{ $v('birth_date', $applicant?->birth_date?->format('Y-m-d')) }}" required
                        class="{{ $fieldClass }}">
                    <p class="text-[11px] text-slate-400 mt-1">Usia maksimal 21 tahun pada 1 Juli tahun penerimaan.</p>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Agama & Kepercayaan <span class="text-rose-500">*</span></label>
                    <select name="religion" required class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::AGAMA as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('religion') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Kewarganegaraan <span class="text-rose-500">*</span></label>
                    <select name="nationality" id="nationality" required class="{{ $fieldClass }}"
                        onchange="document.getElementById('nation-wrap').classList.toggle('hidden', this.value !== 'WNA')">
                        <option value="WNI" @selected($nationality === 'WNI')>Indonesia (WNI)</option>
                        <option value="WNA" @selected($nationality === 'WNA')>Asing (WNA)</option>
                    </select>
                </div>
                <div id="nation-wrap" class="{{ $nationality === 'WNA' ? '' : 'hidden' }}">
                    <label class="{{ $labelClass }}">Nama Negara (untuk WNA)</label>
                    <input type="text" name="nationality_country" value="{{ $v('nationality_country') }}" maxlength="50" placeholder="Contoh: Malaysia"
                        class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Berkebutuhan Khusus</label>
                    <select name="special_needs" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::KEBUTUHAN_KHUSUS as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('special_needs') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Apakah punya KIP? <span class="text-rose-500">*</span></label>
                    <select name="has_kip" id="has_kip" required class="{{ $fieldClass }}" onchange="toggleKip()">
                        <option value="0" @selected($hasKip === '0')>Tidak</option>
                        <option value="1" @selected($hasKip === '1')>Ya</option>
                    </select>
                </div>
                <div id="kip-details" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 {{ $hasKip === '1' ? '' : 'hidden' }}">
                    <div>
                        <label class="{{ $labelClass }}">Nomor KIP</label>
                        <input type="text" name="kip_number" value="{{ $v('kip_number') }}" maxlength="50" placeholder="Nomor Kartu Indonesia Pintar"
                            class="{{ $fieldClass }}">
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Sudah menerima KIP secara fisik?</label>
                        <select name="receives_kip" class="{{ $fieldClass }}">
                            <option value="0" @selected($receivesKip === '0')>Belum / Tidak</option>
                            <option value="1" @selected($receivesKip === '1')>Sudah</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Alamat -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            @include('partials.form-section-header', ['number' => '2', 'title' => 'Alamat & Tempat Tinggal', 'subtitle' => 'Sesuai Kartu Keluarga dan domisili saat ini'])
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="{{ $labelClass }}">Alamat Jalan <span class="text-rose-500">*</span></label>
                    <textarea name="address" required rows="2" placeholder="Jalan, gang, kompleks, blok, nomor rumah"
                        class="{{ $fieldClass }}">{{ $v('address') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4 md:col-span-2">
                    <div>
                        <label class="{{ $labelClass }}">RT</label>
                        <input type="text" name="rt" value="{{ $v('rt') }}" maxlength="5" placeholder="005" class="{{ $fieldClass }}">
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">RW</label>
                        <input type="text" name="rw" value="{{ $v('rw') }}" maxlength="5" placeholder="011" class="{{ $fieldClass }}">
                    </div>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Nama Dusun / Kampung</label>
                    <input type="text" name="dusun" value="{{ $v('dusun') }}" maxlength="100" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Kelurahan / Desa</label>
                    <input type="text" name="kelurahan" value="{{ $v('kelurahan') }}" maxlength="100" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ $v('kecamatan') }}" maxlength="100" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Kode Pos</label>
                    <input type="text" name="postal_code" value="{{ $v('postal_code') }}" maxlength="5" inputmode="numeric" pattern="[0-9]{5}" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Tempat Tinggal</label>
                    <select name="residence_type" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::TEMPAT_TINGGAL as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('residence_type') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Moda Transportasi ke Sekolah</label>
                    <select name="transportation" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::TRANSPORTASI as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('transportation') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Jarak Tempat Tinggal ke Sekolah</label>
                    <select name="distance_to_school" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::JARAK_SEKOLAH as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('distance_to_school') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Jarak (km, jika lebih dari 1 km)</label>
                    <input type="number" name="distance_km" value="{{ $v('distance_km') }}" min="0" max="1000" placeholder="Contoh: 3"
                        class="{{ $fieldClass }}">
                </div>
                <div class="grid grid-cols-2 gap-4 md:col-span-2">
                    <div>
                        <label class="{{ $labelClass }}">Waktu Tempuh (jam)</label>
                        <input type="number" name="travel_time_hours" value="{{ $v('travel_time_hours') }}" min="0" max="24" placeholder="0"
                            class="{{ $fieldClass }}">
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Waktu Tempuh (menit)</label>
                        <input type="number" name="travel_time_minutes" value="{{ $v('travel_time_minutes') }}" min="0" max="59" placeholder="25"
                            class="{{ $fieldClass }}">
                    </div>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Lintang (opsional)</label>
                    <input type="text" name="latitude" value="{{ $v('latitude') }}" maxlength="30" placeholder="-6.123456" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Bujur (opsional)</label>
                    <input type="text" name="longitude" value="{{ $v('longitude') }}" maxlength="30" placeholder="106.123456" class="{{ $fieldClass }}">
                </div>
            </div>
        </div>

        <!-- 3. Data Periodik -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            @include('partials.form-section-header', ['number' => '3', 'title' => 'Data Periodik', 'subtitle' => 'Kondisi fisik dan data keluarga'])
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $labelClass }}">Tinggi Badan (cm)</label>
                    <input type="number" name="height_cm" value="{{ $v('height_cm') }}" min="0" max="300" placeholder="Contoh: 165" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Berat Badan (kg)</label>
                    <input type="number" name="weight_kg" value="{{ $v('weight_kg') }}" min="0" max="300" placeholder="Contoh: 55" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Lingkar Kepala (cm)</label>
                    <input type="number" name="head_circumference" value="{{ $v('head_circumference') }}" min="0" max="100" placeholder="Contoh: 54" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Anak Keberapa</label>
                    <input type="number" name="child_order" value="{{ $v('child_order') }}" min="1" max="20" placeholder="Contoh: 2" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Jumlah Saudara Kandung</label>
                    <input type="number" name="siblings_count" value="{{ $v('siblings_count') }}" min="0" max="20" placeholder="0 jika anak tunggal" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Jumlah Saudara Tiri</label>
                    <input type="number" name="step_siblings_count" value="{{ $v('step_siblings_count') }}" min="0" max="20" placeholder="0 jika tidak ada" class="{{ $fieldClass }}">
                </div>
                <div class="md:col-span-2">
                    <label class="{{ $labelClass }}">Hobi</label>
                    <input type="text" name="hobby" value="{{ $v('hobby') }}" maxlength="100" placeholder="Contoh: Membaca, sepak bola" class="{{ $fieldClass }}">
                </div>
                <div class="md:col-span-2">
                    <label class="{{ $labelClass }}">Kemampuan Lain-lain</label>
                    <textarea name="other_skills" rows="2" maxlength="1000" placeholder="Contoh: Mengelas, desain grafis, bermain musik"
                        class="{{ $fieldClass }}">{{ $v('other_skills') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 4. Data Ayah -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            @include('partials.form-section-header', ['number' => '4', 'title' => 'Data Ayah Kandung', 'subtitle' => 'Sesuai dokumen resmi, tanpa gelar'])
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $labelClass }}">Nama Ayah <span class="text-rose-500">*</span></label>
                    <input type="text" name="father_name" value="{{ $v('father_name') }}" required maxlength="100" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">NIK Ayah</label>
                    <input type="text" name="father_nik" value="{{ $v('father_nik') }}" maxlength="20" inputmode="numeric" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Tahun Lahir</label>
                    <input type="number" name="father_birth_year" value="{{ $v('father_birth_year') }}" min="1900" max="{{ now()->year }}" placeholder="1975" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Pendidikan Terakhir</label>
                    <select name="father_education" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::PENDIDIKAN as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('father_education') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Pekerjaan</label>
                    <select name="father_job" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::PEKERJAAN as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('father_job') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Penghasilan Bulanan</label>
                    <select name="father_income" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::PENGHASILAN as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('father_income') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 5. Data Ibu -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            @include('partials.form-section-header', ['number' => '5', 'title' => 'Data Ibu Kandung', 'subtitle' => 'Sesuai dokumen resmi, tanpa gelar'])
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $labelClass }}">Nama Ibu <span class="text-rose-500">*</span></label>
                    <input type="text" name="mother_name" value="{{ $v('mother_name') }}" required maxlength="100" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">NIK Ibu</label>
                    <input type="text" name="mother_nik" value="{{ $v('mother_nik') }}" maxlength="20" inputmode="numeric" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Tahun Lahir</label>
                    <input type="number" name="mother_birth_year" value="{{ $v('mother_birth_year') }}" min="1900" max="{{ now()->year }}" placeholder="1978" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Pendidikan Terakhir</label>
                    <select name="mother_education" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::PENDIDIKAN as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('mother_education') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Pekerjaan</label>
                    <select name="mother_job" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::PEKERJAAN as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('mother_job') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Penghasilan Bulanan</label>
                    <select name="mother_income" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::PENGHASILAN as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('mother_income') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 6. Data Wali -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            @include('partials.form-section-header', ['number' => '6', 'title' => 'Data Wali', 'subtitle' => 'Kosongkan jika calon peserta didik tinggal bersama orang tua'])
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $labelClass }}">Nama Wali</label>
                    <input type="text" name="guardian_name" value="{{ $v('guardian_name') }}" maxlength="100" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Hubungan dengan Peserta Didik</label>
                    <input type="text" name="guardian_relation" value="{{ $v('guardian_relation') }}" maxlength="50" placeholder="Contoh: Paman, Kakek" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">NIK Wali</label>
                    <input type="text" name="guardian_nik" value="{{ $v('guardian_nik') }}" maxlength="20" inputmode="numeric" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Tahun Lahir</label>
                    <input type="number" name="guardian_birth_year" value="{{ $v('guardian_birth_year') }}" min="1900" max="{{ now()->year }}" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Pendidikan Terakhir</label>
                    <select name="guardian_education" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::PENDIDIKAN as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('guardian_education') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Pekerjaan</label>
                    <select name="guardian_job" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::PEKERJAAN as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('guardian_job') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Penghasilan Bulanan</label>
                    <select name="guardian_income" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::PENGHASILAN as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('guardian_income') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 7. Kontak -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            @include('partials.form-section-header', ['number' => '7', 'title' => 'Kontak', 'subtitle' => 'Nomor yang dapat dihubungi panitia'])
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $labelClass }}">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ $v('email') }}" required placeholder="nama@email.com" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Nomor HP <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" value="{{ $v('phone') }}" required maxlength="20" placeholder="08xxxxxxxxxx" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Nomor Telepon Rumah</label>
                    <input type="text" name="home_phone" value="{{ $v('home_phone') }}" maxlength="20" placeholder="Tanpa tanda baca" class="{{ $fieldClass }}">
                </div>
            </div>
        </div>

        <!-- 8. Pendidikan & Pilihan -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            @include('partials.form-section-header', ['number' => '8', 'title' => 'Pendidikan & Pilihan', 'subtitle' => 'Asal sekolah, data ijazah, dan pilihan jurusan'])
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $labelClass }}">Sekolah Asal</label>
                    <input type="text" name="previous_school" value="{{ $v('previous_school') }}" maxlength="100" placeholder="SMP/MTs asal" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Alamat Sekolah Asal</label>
                    <input type="text" name="school_address" value="{{ $v('school_address') }}" maxlength="255" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Jenis Pendaftaran <span class="text-rose-500">*</span></label>
                    <select name="registration_type" required class="{{ $fieldClass }}">
                        @foreach(\App\Models\Applicant::JENIS_PENDAFTARAN as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('registration_type', 'baru') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">NIS / Nomor Induk PD (jika ada)</label>
                    <input type="text" name="nis" value="{{ $v('nis') }}" maxlength="20" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Nomor Peserta UN SMP/MTs</label>
                    <input type="text" name="un_participant_no" value="{{ $v('un_participant_no') }}" maxlength="30" placeholder="Format 20 digit" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">No. Seri Ijazah SMP/MTs</label>
                    <input type="text" name="ijazah_serial_no" value="{{ $v('ijazah_serial_no') }}" maxlength="30" placeholder="16 digit" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Tanggal Ijazah</label>
                    <input type="date" name="ijazah_date" value="{{ $v('ijazah_date', $applicant?->ijazah_date?->format('Y-m-d')) }}" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">No. SHUN / SKHUN</label>
                    <input type="text" name="shun" value="{{ $v('shun') }}" maxlength="50" placeholder="Nomor SHUN SMP" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Rata-rata SHUN</label>
                    <input type="number" name="shun_average" value="{{ $v('shun_average') }}" min="0" max="100" step="0.01" placeholder="Contoh: 82.50" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Tahun Penerimaan <span class="text-rose-500">*</span></label>
                    <input type="number" name="admission_year" value="{{ $v('admission_year', now()->year) }}" min="2000" max="{{ now()->year + 1 }}" required class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Jurusan Pilihan <span class="text-rose-500">*</span></label>
                    <select name="major" required class="{{ $fieldClass }}">
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach($majors as $code => $label)
                            <option value="{{ $code }}" @selected((string) $v('major') === $code)>{{ $code }} — {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Jalur Pendaftaran <span class="text-rose-500">*</span></label>
                    <select name="jalur" required class="{{ $fieldClass }}">
                        <option value="">-- Pilih Jalur --</option>
                        @foreach(\App\Models\Applicant::JALUR as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('jalur') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Zonasi 90% · Prestasi 5% · Perpindahan 5%</p>
                </div>
            </div>
        </div>

        <!-- 9. Prestasi & Beasiswa -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            @include('partials.form-section-header', ['number' => '9', 'title' => 'Prestasi & Beasiswa', 'subtitle' => 'Isi jika pernah meraih prestasi atau menerima beasiswa'])

            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-extrabold">Prestasi</h3>
                    <button type="button" onclick="addAchievement()" class="text-xs font-bold text-brand-700 bg-brand-50 hover:bg-brand-100 rounded-lg px-3 py-1.5 transition">+ Tambah Prestasi</button>
                </div>
                <div id="achievement-rows" class="space-y-3">
                    @foreach($achievements as $i => $row)
                        @php $row = (array) $row; @endphp
                        <div class="achievement-row rounded-xl border border-slate-200 p-4 space-y-3 bg-slate-50/40">
                            <div class="flex items-center justify-between">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Prestasi</p>
                                <button type="button" onclick="this.closest('.achievement-row').remove()" class="text-xs font-bold text-rose-500 hover:text-rose-700">Hapus</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="{{ $labelClass }}">Jenis Prestasi</label>
                                    <select name="achievements[{{ $i }}][jenis]" class="{{ $fieldClass }}">
                                        <option value="">-- Pilih --</option>
                                        @foreach(\App\Models\Applicant::JENIS_PRESTASI as $k => $label)
                                            <option value="{{ $k }}" @selected(($row['jenis'] ?? '') === $k)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="{{ $labelClass }}">Tingkat</label>
                                    <select name="achievements[{{ $i }}][tingkat]" class="{{ $fieldClass }}">
                                        <option value="">-- Pilih --</option>
                                        @foreach(\App\Models\Applicant::TINGKAT_PRESTASI as $k => $label)
                                            <option value="{{ $k }}" @selected(($row['tingkat'] ?? '') === $k)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="{{ $labelClass }}">Nama Prestasi</label>
                                    <input type="text" name="achievements[{{ $i }}][nama]" value="{{ $row['nama'] ?? '' }}" maxlength="150" placeholder="Contoh: Lomba Cerdas Cermat" class="{{ $fieldClass }}">
                                </div>
                                <div>
                                    <label class="{{ $labelClass }}">Tahun</label>
                                    <input type="number" name="achievements[{{ $i }}][tahun]" value="{{ $row['tahun'] ?? '' }}" min="1900" max="{{ now()->year }}" placeholder="2025" class="{{ $fieldClass }}">
                                </div>
                                <div>
                                    <label class="{{ $labelClass }}">Peringkat</label>
                                    <input type="text" name="achievements[{{ $i }}][peringkat]" value="{{ $row['peringkat'] ?? '' }}" maxlength="30" placeholder="Contoh: Juara 1" class="{{ $fieldClass }}">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="{{ $labelClass }}">Penyelenggara</label>
                                    <input type="text" name="achievements[{{ $i }}][penyelenggara]" value="{{ $row['penyelenggara'] ?? '' }}" maxlength="150" placeholder="Contoh: Panitia O2SN Kabupaten" class="{{ $fieldClass }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-extrabold">Beasiswa</h3>
                    <button type="button" onclick="addScholarship()" class="text-xs font-bold text-brand-700 bg-brand-50 hover:bg-brand-100 rounded-lg px-3 py-1.5 transition">+ Tambah Beasiswa</button>
                </div>
                <div id="scholarship-rows" class="space-y-3">
                    @foreach($scholarships as $i => $row)
                        @php $row = (array) $row; @endphp
                        <div class="scholarship-row rounded-xl border border-slate-200 p-4 space-y-3 bg-slate-50/40">
                            <div class="flex items-center justify-between">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Beasiswa</p>
                                <button type="button" onclick="this.closest('.scholarship-row').remove()" class="text-xs font-bold text-rose-500 hover:text-rose-700">Hapus</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="{{ $labelClass }}">Jenis Beasiswa</label>
                                    <select name="scholarships[{{ $i }}][jenis]" class="{{ $fieldClass }}">
                                        <option value="">-- Pilih --</option>
                                        @foreach(\App\Models\Applicant::JENIS_BEASISWA as $k => $label)
                                            <option value="{{ $k }}" @selected(($row['jenis'] ?? '') === $k)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="{{ $labelClass }}">Keterangan</label>
                                    <input type="text" name="scholarships[{{ $i }}][keterangan]" value="{{ $row['keterangan'] ?? '' }}" maxlength="200" placeholder="Nama beasiswa / keterangan" class="{{ $fieldClass }}">
                                </div>
                                <div>
                                    <label class="{{ $labelClass }}">Tahun Mulai</label>
                                    <input type="number" name="scholarships[{{ $i }}][tahun_mulai]" value="{{ $row['tahun_mulai'] ?? '' }}" min="1900" max="{{ now()->year }}" class="{{ $fieldClass }}">
                                </div>
                                <div>
                                    <label class="{{ $labelClass }}">Tahun Selesai</label>
                                    <input type="number" name="scholarships[{{ $i }}][tahun_selesai]" value="{{ $row['tahun_selesai'] ?? '' }}" min="1900" max="{{ now()->year + 10 }}" class="{{ $fieldClass }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 10. Kesejahteraan -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            @include('partials.form-section-header', ['number' => '10', 'title' => 'Kesejahteraan Peserta Didik', 'subtitle' => 'Bantuan sosial yang pernah diterima (jika ada)'])
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $labelClass }}">Jenis Kesejahteraan</label>
                    <select name="welfare_type" class="{{ $fieldClass }}">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Applicant::KESEJAHTERAAN as $k => $label)
                            <option value="{{ $k }}" @selected((string) $v('welfare_type') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}">No. Kartu</label>
                    <input type="text" name="welfare_card_no" value="{{ $v('welfare_card_no') }}" maxlength="50" class="{{ $fieldClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Nama pada Kartu</label>
                    <input type="text" name="welfare_card_name" value="{{ $v('welfare_card_name') }}" maxlength="50" class="{{ $fieldClass }}">
                </div>
            </div>
        </div>

        <!-- 11. Berkas -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            @include('partials.form-section-header', ['number' => '11', 'title' => 'Berkas Persyaratan', 'subtitle' => 'Format PDF/JPG/PNG, maksimal 2 MB per berkas dan total 7 MB'])
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach([
                    ['ijazah', 'Ijazah / STT SMP', 'ijazah_path', 'Wajib', 'Berkas utama verifikasi.'],
                    ['akta_kelahiran', 'Akta Kelahiran', 'akta_kelahiran_path', 'Wajib', 'Kutipan akta kelahiran.'],
                    ['kartu_keluarga', 'Kartu Keluarga (KK)', 'kartu_keluarga_path', 'Wajib', 'Sesuai domisili saat ini.'],
                    ['ktp_orang_tua', 'KTP Orang Tua / Wali', 'ktp_orang_tua_path', 'Wajib', 'KTP ayah/ibu/wali.'],
                    ['pas_foto', 'Pas Foto 3 × 4', 'pas_foto_path', 'Wajib', 'Latar belakang berwarna.'],
                    ['kip_kks', 'KIP / KKS / PKH / KIS', 'kip_kks_path', 'Opsional', 'Jika memiliki kartu bantuan.'],
                    ['prestasi_proof', 'Bukti Prestasi', 'prestasi_proof', 'Opsional', 'Untuk pendaftar jalur prestasi.'],
                ] as [$field, $label, $column, $badge, $hint])
                    <div>
                        <label class="{{ $labelClass }}">
                            {{ $label }}
                            <span class="ml-1 text-[10px] font-bold {{ $badge === 'Wajib' ? 'text-rose-500' : 'text-slate-400' }}">({{ $badge }})</span>
                        </label>
                        @if($editing && $applicant->{$column})
                            <p class="text-xs text-emerald-700 font-semibold mb-2">✅ Sudah ada —
                                <a href="{{ asset('storage/' . $applicant->{$column}) }}" target="_blank" class="text-brand-700 hover:underline">lihat berkas</a>
                            </p>
                        @endif
                        <input type="file" name="{{ $field }}" accept=".pdf,image/*"
                            class="block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2.5 file:text-xs file:font-bold file:text-brand-700 hover:file:bg-brand-100 transition">
                        <p class="text-[11px] text-slate-400 mt-1">{{ $hint }} @if($editing) Kosongkan jika tidak diganti. @endif</p>
                    </div>
                @endforeach
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

<template id="achievement-template">
    <div class="achievement-row rounded-xl border border-slate-200 p-4 space-y-3 bg-slate-50/40">
        <div class="flex items-center justify-between">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Prestasi</p>
            <button type="button" onclick="this.closest('.achievement-row').remove()" class="text-xs font-bold text-rose-500 hover:text-rose-700">Hapus</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="{{ $labelClass }}">Jenis Prestasi</label>
                <select name="achievements[__INDEX__][jenis]" class="{{ $fieldClass }}">
                    <option value="">-- Pilih --</option>
                    @foreach(\App\Models\Applicant::JENIS_PRESTASI as $k => $label)
                        <option value="{{ $k }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $labelClass }}">Tingkat</label>
                <select name="achievements[__INDEX__][tingkat]" class="{{ $fieldClass }}">
                    <option value="">-- Pilih --</option>
                    @foreach(\App\Models\Applicant::TINGKAT_PRESTASI as $k => $label)
                        <option value="{{ $k }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}">Nama Prestasi</label>
                <input type="text" name="achievements[__INDEX__][nama]" maxlength="150" placeholder="Contoh: Lomba Cerdas Cermat" class="{{ $fieldClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}">Tahun</label>
                <input type="number" name="achievements[__INDEX__][tahun]" min="1900" max="{{ now()->year }}" placeholder="2025" class="{{ $fieldClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}">Peringkat</label>
                <input type="text" name="achievements[__INDEX__][peringkat]" maxlength="30" placeholder="Contoh: Juara 1" class="{{ $fieldClass }}">
            </div>
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}">Penyelenggara</label>
                <input type="text" name="achievements[__INDEX__][penyelenggara]" maxlength="150" placeholder="Contoh: Panitia O2SN Kabupaten" class="{{ $fieldClass }}">
            </div>
        </div>
    </div>
</template>

<template id="scholarship-template">
    <div class="scholarship-row rounded-xl border border-slate-200 p-4 space-y-3 bg-slate-50/40">
        <div class="flex items-center justify-between">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Beasiswa</p>
            <button type="button" onclick="this.closest('.scholarship-row').remove()" class="text-xs font-bold text-rose-500 hover:text-rose-700">Hapus</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="{{ $labelClass }}">Jenis Beasiswa</label>
                <select name="scholarships[__INDEX__][jenis]" class="{{ $fieldClass }}">
                    <option value="">-- Pilih --</option>
                    @foreach(\App\Models\Applicant::JENIS_BEASISWA as $k => $label)
                        <option value="{{ $k }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $labelClass }}">Keterangan</label>
                <input type="text" name="scholarships[__INDEX__][keterangan]" maxlength="200" placeholder="Nama beasiswa / keterangan" class="{{ $fieldClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}">Tahun Mulai</label>
                <input type="number" name="scholarships[__INDEX__][tahun_mulai]" min="1900" max="{{ now()->year }}" class="{{ $fieldClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}">Tahun Selesai</label>
                <input type="number" name="scholarships[__INDEX__][tahun_selesai]" min="1900" max="{{ now()->year + 10 }}" class="{{ $fieldClass }}">
            </div>
        </div>
    </div>
</template>

<script>
    let achievementIndex = {{ count($achievements) }};
    let scholarshipIndex = {{ count($scholarships) }};

    function addAchievement() {
        const template = document.getElementById('achievement-template');
        const html = template.innerHTML.split('__INDEX__').join(achievementIndex++);
        document.getElementById('achievement-rows').insertAdjacentHTML('beforeend', html);
    }

    function addScholarship() {
        const template = document.getElementById('scholarship-template');
        const html = template.innerHTML.split('__INDEX__').join(scholarshipIndex++);
        document.getElementById('scholarship-rows').insertAdjacentHTML('beforeend', html);
    }

    function toggleKip() {
        const show = document.getElementById('has_kip').value === '1';
        document.getElementById('kip-details').classList.toggle('hidden', !show);
    }

    // Cegah POST terlalu besar: PHP membuang seluruh isian bila melewati post_max_size (biasanya 8 MB).
    document.getElementById('ppdb-form').addEventListener('submit', function (event) {
        const nationality = document.getElementById('nationality');

        // Validasi NIK di browser supaya isian tidak hilang karena bolak-balik server.
        if (nationality.value === 'WNI') {
            const nikFields = [
                ['input[name="nik"]', 'NIK'],
                ['input[name="father_nik"]', 'NIK ayah'],
                ['input[name="mother_nik"]', 'NIK ibu'],
                ['input[name="guardian_nik"]', 'NIK wali'],
            ];

            for (const [selector, label] of nikFields) {
                const field = this.querySelector(selector);
                if (field && field.value && !/^\d{16}$/.test(field.value)) {
                    event.preventDefault();
                    alert(label + ' harus terdiri dari 16 digit angka.');
                    field.focus();
                    return;
                }
            }
        }

        const maxFileSize = 2 * 1024 * 1024;
        const maxTotalSize = 7 * 1024 * 1024;
        let totalSize = 0;

        for (const input of this.querySelectorAll('input[type="file"]')) {
            for (const file of input.files) {
                if (file.size > maxFileSize) {
                    event.preventDefault();
                    alert('Berkas "' + file.name + '" melebihi batas 2 MB. Silakan kompres atau pilih berkas lain.');
                    input.focus();
                    return;
                }
                totalSize += file.size;
            }
        }

        if (totalSize > maxTotalSize) {
            event.preventDefault();
            alert('Total ukuran semua berkas ' + (totalSize / 1024 / 1024).toFixed(1) + ' MB, melebihi batas 7 MB. Silakan kompres berkas terlebih dahulu.');
        }
    });
</script>
@endsection
