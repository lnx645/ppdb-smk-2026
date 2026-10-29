<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'nisn', 'nik', 'kk_number', 'email', 'phone', 'home_phone',
        'gender', 'birth_place', 'birth_date', 'birth_certificate_no', 'religion',
        'nationality', 'nationality_country', 'special_needs', 'has_kip', 'receives_kip',
        'kip_number', 'address', 'rt', 'rw', 'dusun', 'kelurahan', 'kecamatan',
        'postal_code', 'latitude', 'longitude', 'residence_type', 'transportation',
        'distance_to_school', 'distance_km', 'travel_time_hours', 'travel_time_minutes',
        'height_cm', 'weight_kg', 'head_circumference', 'child_order', 'siblings_count',
        'step_siblings_count', 'hobby', 'other_skills', 'father_name', 'father_nik',
        'father_birth_year', 'father_education', 'father_job', 'father_income',
        'mother_name', 'mother_nik', 'mother_birth_year', 'mother_education',
        'mother_job', 'mother_income', 'guardian_name', 'guardian_relation',
        'guardian_nik', 'guardian_birth_year', 'guardian_education', 'guardian_job',
        'guardian_income', 'major', 'previous_school', 'school_address',
        'registration_type', 'nis', 'un_participant_no', 'ijazah_serial_no',
        'ijazah_date', 'shun', 'shun_average', 'admission_year', 'jalur', 'welfare_type',
        'welfare_card_no', 'welfare_card_name', 'achievements', 'scholarships',
        'ijazah_path', 'akta_kelahiran_path', 'kartu_keluarga_path', 'ktp_orang_tua_path',
        'pas_foto_path', 'kip_kks_path', 'prestasi_proof', 'status', 'notes',
    ];

    protected $casts = [
        'birth_date'   => 'date',
        'ijazah_date'  => 'date',
        'has_kip'      => 'boolean',
        'receives_kip' => 'boolean',
        'achievements' => 'array',
        'scholarships' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public const MAJORS = [
        'TKJ' => 'Teknik Komputer & Jaringan',
        'RPL' => 'Rekayasa Perangkat Lunak',
        'MM'  => 'Multimedia',
        'AK'  => 'Akuntansi',
        'OTKP'=> 'Otomatisasi & Tata Kelola Perkantoran',
    ];

    // Standar Kemdikbud PPDB: jalur pendaftaran (Pasal 16 Permendikbud No.51/2018)
    public const JALUR = [
        'zonasi'      => 'Zonasi',
        'prestasi'    => 'Prestasi',
        'perpindahan' => 'Perpindahan Tugas Orang Tua/Wali',
    ];

    // Usia maksimal calon PPDB SMK/Kelas 10 pada tanggal 1 Juli tahun berjalan (Pasal 7 Permendikbud No.51/2018)
    public const MAX_AGE_MONTHS = 21; // dalam tahun

    public const STATUSES = [
        'pending'   => 'Menunggu Verifikasi',
        'revision'  => 'Perlu Perbaikan Berkas',
        'accepted'  => 'Diterima',
        'rejected'  => 'Ditolak',
    ];

    // Pilihan field mengikuti Formulir Peserta Didik (F-PD) Dapodik
    public const AGAMA = [
        'islam'       => 'Islam',
        'kristen'     => 'Kristen / Protestan',
        'katolik'     => 'Katolik',
        'hindu'       => 'Hindu',
        'buddha'      => 'Buddha',
        'khonghucu'   => 'Khonghucu',
        'kepercayaan' => 'Kepercayaan kepada Tuhan YME',
    ];

    public const PENDIDIKAN = [
        'tidak_sekolah' => 'Tidak sekolah',
        'putus_sd'      => 'Putus SD',
        'sd'            => 'SD Sederajat',
        'smp'           => 'SMP Sederajat',
        'sma'           => 'SMA Sederajat',
        'd1'            => 'D1',
        'd2'            => 'D2',
        'd3'            => 'D3',
        'd4_s1'         => 'D4 / S1',
        's2'            => 'S2',
        's3'            => 'S3',
    ];

    public const PEKERJAAN = [
        'tidak_bekerja'   => 'Tidak bekerja',
        'nelayan'         => 'Nelayan',
        'petani'          => 'Petani',
        'peternak'        => 'Peternak',
        'pns_tni_polri'   => 'PNS / TNI / Polri',
        'karyawan_swasta' => 'Karyawan Swasta',
        'pedagang_kecil'  => 'Pedagang Kecil',
        'pedagang_besar'  => 'Pedagang Besar',
        'wiraswasta'      => 'Wiraswasta',
        'wirausaha'       => 'Wirausaha',
        'buruh'           => 'Buruh',
        'pensiunan'       => 'Pensiunan',
        'meninggal'       => 'Meninggal Dunia',
    ];

    public const PENGHASILAN = [
        'lt_500'  => '< Rp 500.000',
        '500_999' => 'Rp 500.000 - Rp 999.999',
        '1_1_9'   => 'Rp 1.000.000 - Rp 1.999.999',
        '2_4_9'   => 'Rp 2.000.000 - Rp 4.999.999',
        '5_20'    => 'Rp 5.000.000 - Rp 20.000.000',
        'gt_20'   => '> Rp 20.000.000',
        'none'    => 'Tidak Berpenghasilan',
    ];

    public const TEMPAT_TINGGAL = [
        'orang_tua' => 'Bersama orang tua',
        'wali'      => 'Wali',
        'kos'       => 'Kos',
        'asrama'    => 'Asrama',
        'panti'     => 'Panti Asuhan',
    ];

    public const TRANSPORTASI = [
        'jalan_kaki'        => 'Jalan kaki',
        'kendaraan_pribadi' => 'Kendaraan pribadi',
        'kendaraan_umum'    => 'Kendaraan umum / angkot',
        'jemputan_sekolah'  => 'Jemputan sekolah',
        'kereta_api'        => 'Kereta api',
        'ojek'              => 'Ojek',
        'andong'            => 'Andong / Bendi / Sado / Dokar / Delman / Beca',
        'perahu'            => 'Perahu penyebrangan / Rakit / Getek',
        'lainnya'           => 'Lainnya',
    ];

    public const KEBUTUHAN_KHUSUS = [
        'tidak'             => 'Tidak',
        'netra'             => 'Netra (A)',
        'rungu'             => 'Rungu (B)',
        'grahita_ringan'    => 'Grahita ringan (C)',
        'grahita_sedang'    => 'Grahita sedang (C1)',
        'daksa_ringan'      => 'Daksa ringan (D)',
        'daksa_sedang'      => 'Daksa sedang (D1)',
        'wicara'            => 'Wicara (F)',
        'tuna_ganda'        => 'Tuna ganda (G)',
        'hiperaktif'        => 'Hiper aktif (H)',
        'cerdas_istimewa'   => 'Cerdas istimewa (I)',
        'bakat_istimewa'    => 'Bakat istimewa (J)',
        'kesulitan_belajar' => 'Kesulitan belajar (K)',
        'narkoba'           => 'Narkoba (N)',
        'indigo'            => 'Indigo (O)',
        'down_syndrome'     => 'Down syndrome (P)',
        'autis'             => 'Autis (Q)',
    ];

    public const KESEJAHTERAAN = [
        'tidak'           => 'Tidak ada',
        'pkh'             => 'PKH',
        'pip'             => 'PIP',
        'kps'             => 'Kartu Perlindungan Sosial',
        'kks'             => 'Kartu Keluarga Sejahtera',
        'kartu_kesehatan' => 'Kartu Kesehatan',
    ];

    public const JENIS_PENDAFTARAN = [
        'baru'     => 'Siswa Baru',
        'pindahan' => 'Pindahan',
        'kembali'  => 'Kembali Bersekolah',
    ];

    public const JARAK_SEKOLAH = [
        'kurang_1' => 'Kurang dari 1 km',
        'lebih_1'  => 'Lebih dari 1 km',
    ];

    public const JENIS_PRESTASI = [
        'sains'     => 'Sains',
        'seni'      => 'Seni',
        'olahraga'  => 'Olahraga',
        'lainnya'   => 'Lain-lain',
    ];

    public const TINGKAT_PRESTASI = [
        'sekolah'       => 'Sekolah',
        'kecamatan'     => 'Kecamatan',
        'kabupaten'     => 'Kabupaten',
        'provinsi'      => 'Provinsi',
        'nasional'      => 'Nasional',
        'internasional' => 'Internasional',
    ];

    public const JENIS_BEASISWA = [
        'prestasi'   => 'Anak berprestasi',
        'miskin'     => 'Anak miskin',
        'pendidikan' => 'Pendidikan',
        'unggulan'   => 'Unggulan',
    ];

    public const DOCUMENT_COLUMNS = [
        'ijazah_path',
        'akta_kelahiran_path',
        'kartu_keluarga_path',
        'ktp_orang_tua_path',
        'pas_foto_path',
        'kip_kks_path',
        'prestasi_proof',
    ];

    /**
     * Ambil label dari konstanta pilihan, fallback ke nilai asli.
     */
    public static function label(array $map, ?string $value, string $fallback = '-'): string
    {
        if ($value === null || $value === '') {
            return $fallback;
        }

        return $map[$value] ?? $value;
    }
}
