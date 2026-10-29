# PPDB SMK — Aplikasi Laravel

Aplikasi Penerimaan Peserta Didik Baru (PPDB) untuk SMK, dibangun dengan Laravel 12.

## Fitur
- **Akun siswa**: register, login, logout, dashboard status pendaftaran
- Halaman beranda & daftar jurusan
- **Formulir PPDB sesuai Formulir Peserta Didik (F-PD) Dapodik/Kemdikbud**: data pribadi lengkap (NIK, KK, akta, agama, kewarganegaraan, KIP), alamat & tempat tinggal, data periodik, data ayah/ibu/wali, kontak, pendidikan & registrasi, prestasi, beasiswa, dan kesejahteraan
- Upload **7 berkas persyaratan**: ijazah/STT, akta kelahiran, KK, KTP orang tua, pas foto, KIP/KKS, bukti prestasi
- Validasi NISN/NIK/email unik, validasi usia, validasi pilihan wilayah administratif
- Validasi format: NIK 16 digit (WNI)/KITAS (WNA), NISN 10 digit, KK 16 digit, kode pos 5 digit
- Isian form **tetap tersimpan** saat ada kesalahan validasi (old input), termasuk baris prestasi/beasiswa
- Halaman sukses pendaftaran
- **Admin**: login terpisah, dashboard statistik, filter/pencarian (nama/NISN/NIK/email), halaman detail pendaftar lengkap, verifikasi berkas, ubah status & catatan, hapus pendaftar

## Formulir yang Diikuti
Formulir mengacu pada **F-PD (Formulir Peserta Didik)** versi Dapodik yang dipakai sekolah-sekolah, ditambah persyaratan berkas dari contoh formulir PPDB SMK. Bagian yang diadopsi:

1. **Data Pribadi** — nama, NISN, NIK/KITAS, No. KK, tempat/tanggal lahir, No. registrasi akta, agama, kewarganegaraan, berkebutuhan khusus, KIP
2. **Alamat & Tempat Tinggal** — alamat jalan, RT/RW, dusun, kelurahan/desa, kecamatan, kode pos, lintang/bujur, tempat tinggal, moda transportasi, jarak & waktu tempuh
3. **Data Periodik** — tinggi/berat badan, lingkar kepala, anak keberapa, jumlah saudara kandung/tiri, hobi, kemampuan lain
4. **Data Ayah Kandung** — nama, NIK, tahun lahir, pendidikan, pekerjaan, penghasilan
5. **Data Ibu Kandung** — sama dengan data ayah
6. **Data Wali** — opsional, untuk siswa yang tinggal bersama wali
7. **Kontak** — email, No. HP, telepon rumah
8. **Pendidikan & Pilihan** — sekolah asal, alamat sekolah, jenis pendaftaran, NIS, nomor peserta UN, No. seri ijazah, tanggal ijazah, SHUN/SKHUN, rata-rata SHUN, jurusan, jalur, tahun penerimaan
9. **Prestasi & Beasiswa** — multi-baris (jenis, tingkat, nama, tahun, peringkat, penyelenggara; beasiswa: jenis, keterangan, tahun mulai/selesai)
10. **Kesejahteraan** — PKH/PIP/KPS/KKS/kartu kesehatan, nomor & nama pada kartu
11. **Berkas Persyaratan** — 7 dokumen di atas

Detail field ada di `database/migrations/2026_10_29_06_0000_add_formulir_peserta_didik_fields_to_applicants.php` dan tampilan form di `resources/views/ppdb/form.blade.php`.


## Alur
1. Siswa **buat akun** (`/register`)
2. Login siswa (`/login`)
3. Isi formulir PPDB (`/daftar`)
4. Pantau status di **dashboard siswa** (`/siswa`)
5. Admin verifikasi & tetapkan status lewat dashboard admin
6. Jika **berkas kurang/tidak jelas**, admin pilih status **"Perlu Perbaikan Berkas"** + catatan
7. Siswa melihat peringatan di dashboard, klik **"Upload Ulang Berkas"**, perbaiki data/berkas, lalu kirim ulang
8. Status kembali **"Menunggu Verifikasi"** dan admin memverifikasi ulang

## Verifikasi Berkas
- Admin membuka **Detail pendaftar** → bagian **Verifikasi Berkas**
- Terlihat status tiap dokumen: ✅ terunggah / ❌ belum diunggah
- **5 berkas wajib**: ijazah/STT, akta kelahiran, KK, KTP orang tua/wali, pas foto 3×4
- **2 berkas opsional**: KIP/KKS/PKH/KIS dan bukti prestasi
- Badge **Lengkap** / **N berkas wajib kurang** membantu admin menentukan status
- Semua berkas bisa dibuka langsung
- Admin dapat mengembalikan pendaftaran ke siswa kapan saja lewat status **"Perlu Perbaikan Berkas"** + catatan

## Kredensial Admin (demo)
- URL: `/admin/login`
- Username: `admin`
- Password: `admin123`

> Ganti dengan autentikasi resmi (Laravel Breeze/Fortify) untuk produksi.

## Cara Menjalankan

```bash
cd /root/ppdb-smk
php artisan serve --host=0.0.0.0 --port=8000
```

Buka http://localhost:8000

## Struktur
- `app/Models/Applicant.php` — model pendaftar (relasi ke User, konstanta pilihan F-PD)
- `app/Http/Controllers/` — Ppdb, SiswaAuth, Siswa, Admin, Auth
- `database/migrations/` — tabel applicants, users, sessions
- `resources/views/` — Blade templates (Tailwind via CDN)
- `resources/views/partials/applicant-data.blade.php` — tampilan data lengkap F-PD (dipakai siswa & admin)
- `resources/views/partials/applicant-documents.blade.php` — checklist berkas & status kelengkapan
- `storage/app/public/{ijazah,akta,kk,ktp,foto,kip,prestasi}` — berkas upload (symlink ke public/storage)

## Catatan
- Database: SQLite (`database/database.sqlite`)
- Tidak butuh Node.js/Vite (Tailwind via CDN)
- Upload: maks. **2 MB per berkas** dan **total 7 MB** (agar aman terhadap `post_max_size` bawaan PHP 8 MB)
- Jika ingin batas lebih besar, naikkan `upload_max_filesize` dan `post_max_size` di `php.ini`, lalu sesuaikan `max:2048` di `PpdbController` dan batas total di `resources/views/ppdb/form.blade.php`
- Contoh menjalankan server dengan batas lebih besar:
  ```bash
  php -d upload_max_filesize=4M -d post_max_size=20M -S 0.0.0.0:8000 -t public public/index.php
  ```

## Kepatuhan Standar Kemdikbud
- ✅ Formulir mengikuti **F-PD Dapodik** (data pribadi, alamat, periodik, orang tua/wali, kontak, registrasi)
- ✅ Tahapan PPDB: pendaftaran → seleksi → pengumuman → daftar ulang
- ✅ Jalur pendaftaran: Zonasi / Prestasi / Perpindahan Tugas Orang Tua-Wali (Permendikbud No. 51 Tahun 2018)
- ✅ Validasi usia calon ≤ 21 tahun pada 1 Juli tahun berjalan
- ✅ Data ijazah, SHUN/SKHUN, nomor peserta UN, dan rata-rata SHUN
- ✅ Persyaratan berkas contoh formulir PPDB SMK (ijazah, STT, SKHUN, NISN/akta, pas foto, KK/KTP, KIP/PKH/KKS/KIS)
- ✅ Hasil seleksi ditetapkan admin (simulasi keputusan kepala sekolah)
