<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'nisn', 'email', 'phone', 'gender', 'birth_date',
        'address', 'major', 'previous_school', 'ijazah_path', 'status', 'notes',
        'admission_year', 'jalur', 'shun', 'prestasi_proof',
    ];

    protected $casts = [
        'birth_date' => 'date',
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
}
