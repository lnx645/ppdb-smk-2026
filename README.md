# PPDB SMK — Aplikasi Laravel

Aplikasi Penerimaan Peserta Didik Baru (PPDB) untuk SMK, dibangun dengan Laravel 12.

## Fitur
- **Akun siswa**: register, login, logout, dashboard status pendaftaran
- Halaman beranda & daftar jurusan
- Form pendaftaran online (validasi NISN/email unik, upload ijazah & bukti prestasi)
- Halaman sukses pendaftaran
- **Admin**: login terpisah, dashboard statistik, filter/pencarian, halaman detail pendaftar, ubah status & catatan, hapus pendaftar

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
- Berkas bisa dibuka langsung (ijazah & bukti prestasi)
- Admin dapat mengembalikan pendaftaran ke siswa kapan saja lewat status + catatan

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
- `app/Models/Applicant.php` — model pendaftar (relasi ke User)
- `app/Http/Controllers/` — Ppdb, SiswaAuth, Siswa, Admin, Auth
- `database/migrations/` — tabel applicants, users, sessions
- `resources/views/` — Blade templates (Tailwind via CDN)
- `storage/app/public/ijazah` & `prestasi` — berkas upload (symlink ke public/storage)

## Catatan
- Database: SQLite (`database/database.sqlite`)
- Tidak butuh Node.js/Vite (Tailwind via CDN)
- File upload max: 2 MB default PHP; ubah `upload_max_filesize` di php.ini bila perlu

## Kepatuhan Standar Kemdikbud (Permendikbud No. 51 Tahun 2018)
- ✅ Tahapan PPDB: pendaftaran → seleksi → pengumuman → daftar ulang
- ✅ Jalur pendaftaran: Zonasi / Prestasi / Perpindahan Tugas Orang Tua-Wali
- ✅ Validasi usia calon ≤ 21 tahun pada 1 Juli tahun berjalan
- ✅ Data ijazah & SHUN
- ✅ Hasil seleksi ditetapkan admin (simulasi keputusan kepala sekolah)
