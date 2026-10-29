<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PpdbController extends Controller
{
    /**
     * Berkas persyaratan PPDB: input name => [kolom database, folder storage].
     */
    private const DOCUMENTS = [
        'ijazah'         => ['column' => 'ijazah_path', 'dir' => 'ijazah'],
        'akta_kelahiran' => ['column' => 'akta_kelahiran_path', 'dir' => 'akta'],
        'kartu_keluarga' => ['column' => 'kartu_keluarga_path', 'dir' => 'kk'],
        'ktp_orang_tua'  => ['column' => 'ktp_orang_tua_path', 'dir' => 'ktp'],
        'pas_foto'       => ['column' => 'pas_foto_path', 'dir' => 'foto'],
        'kip_kks'        => ['column' => 'kip_kks_path', 'dir' => 'kip'],
        'prestasi_proof' => ['column' => 'prestasi_proof', 'dir' => 'prestasi'],
    ];

    public function index()
    {
        return view('ppdb.index');
    }

    public function form()
    {
        if (Applicant::where('user_id', auth()->id())->exists()) {
            return redirect()->route('siswa.dashboard')
                ->with('success', 'Kamu sudah mendaftar. Lihat status pendaftaran di dashboard.');
        }

        return view('ppdb.form', ['majors' => Applicant::MAJORS]);
    }

    public function store(Request $request)
    {
        if (Applicant::where('user_id', auth()->id())->exists()) {
            return redirect()->route('siswa.dashboard');
        }

        if ($error = $this->validateTotalUploadSize($request)) {
            return $error;
        }

        $data = $request->validate($this->rules());

        if ($error = $this->validateAge($data)) {
            return $error;
        }

        $data = $this->prepareData($data);
        $paths = $this->storeDocuments($request);

        Applicant::create(array_merge($data, $paths, [
            'user_id' => auth()->id(),
        ]));

        return redirect()->route('ppdb.success');
    }

    public function edit()
    {
        $applicant = Applicant::where('user_id', auth()->id())->firstOrFail();

        if (!in_array($applicant->status, ['pending', 'revision'])) {
            return redirect()->route('siswa.dashboard')
                ->with('success', 'Pendaftaran sudah difinalisasi, data tidak dapat diubah.');
        }

        return view('ppdb.form', [
            'majors'    => Applicant::MAJORS,
            'applicant' => $applicant,
        ]);
    }

    public function update(Request $request)
    {
        $applicant = Applicant::where('user_id', auth()->id())->firstOrFail();

        if (!in_array($applicant->status, ['pending', 'revision'])) {
            return redirect()->route('siswa.dashboard')
                ->with('success', 'Pendaftaran sudah difinalisasi, data tidak dapat diubah.');
        }

        if ($error = $this->validateTotalUploadSize($request)) {
            return $error;
        }

        $data = $request->validate($this->rules($applicant));

        if ($error = $this->validateAge($data)) {
            return $error;
        }

        $data = $this->prepareData($data);

        foreach (self::DOCUMENTS as $input => $config) {
            if (!$request->hasFile($input)) {
                continue;
            }

            if ($applicant->{$config['column']}) {
                Storage::disk('public')->delete($applicant->{$config['column']});
            }

            $data[$config['column']] = $request->file($input)->store($config['dir'], 'public');
        }

        // Setelah siswa mengirim ulang, status kembali menunggu verifikasi
        $data['status'] = 'pending';
        $data['notes']  = null;

        $applicant->update($data);

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Data dan berkas berhasil diperbarui. Menunggu verifikasi ulang panitia.');
    }

    public function success()
    {
        return view('ppdb.success');
    }

    /**
     * Aturan NIK: WNI wajib 16 digit angka, WNA boleh No. KITAS (maks. 20 karakter).
     */
    private function nikRules(string $label, bool $required = false): array
    {
        $rules = [$required ? 'required' : 'nullable', 'string', 'max:20'];

        $rules[] = function ($attribute, $value, $fail) use ($label) {
            if (request('nationality', 'WNI') === 'WNI' && filled($value) && !preg_match('/^\d{16}$/', (string) $value)) {
                $fail($label . ' harus terdiri dari 16 digit angka.');
            }
        };

        return $rules;
    }

    private function rules(?Applicant $applicant = null): array
    {
        $id = $applicant?->id;
        $maxYear = now()->year;

        return [
            // Data pribadi
            'name'                 => 'required|string|max:100',
            'nisn'                 => 'required|digits:10|unique:applicants,nisn' . ($id ? ",{$id}" : ''),
            'nik'                  => array_merge(
                $this->nikRules('NIK', true),
                ['unique:applicants,nik' . ($id ? ",{$id}" : '')]
            ),
            'kk_number'            => 'required|digits:16',
            'email'                => 'required|email|unique:applicants,email' . ($id ? ",{$id}" : ''),
            'phone'                => 'required|string|max:20',
            'home_phone'           => 'nullable|string|max:20',
            'gender'               => 'required|in:L,P',
            'birth_place'          => 'required|string|max:100',
            'birth_date'           => 'required|date|before:today',
            'birth_certificate_no' => 'nullable|string|max:50',
            'religion'             => 'required|in:' . implode(',', array_keys(Applicant::AGAMA)),
            'nationality'          => 'required|in:WNI,WNA',
            'nationality_country'  => 'nullable|required_if:nationality,WNA|string|max:50',
            'special_needs'        => 'nullable|in:' . implode(',', array_keys(Applicant::KEBUTUHAN_KHUSUS)),
            'has_kip'              => 'required|in:0,1',
            'receives_kip'         => 'nullable|in:0,1',
            'kip_number'           => 'nullable|string|max:50',

            // Alamat & tempat tinggal
            'address'              => 'required|string|max:500',
            'rt'                   => 'nullable|string|max:5',
            'rw'                   => 'nullable|string|max:5',
            'dusun'                => 'nullable|string|max:100',
            'kelurahan'            => 'nullable|string|max:100',
            'kecamatan'            => 'nullable|string|max:100',
            'postal_code'          => 'nullable|digits:5',
            'latitude'             => 'nullable|numeric|between:-90,90',
            'longitude'            => 'nullable|numeric|between:-180,180',
            'residence_type'       => 'nullable|in:' . implode(',', array_keys(Applicant::TEMPAT_TINGGAL)),
            'transportation'       => 'nullable|in:' . implode(',', array_keys(Applicant::TRANSPORTASI)),
            'distance_to_school'   => 'nullable|in:' . implode(',', array_keys(Applicant::JARAK_SEKOLAH)),
            'distance_km'          => 'nullable|integer|min:0|max:1000',
            'travel_time_hours'    => 'nullable|integer|min:0|max:24',
            'travel_time_minutes'  => 'nullable|integer|min:0|max:59',

            // Data periodik
            'height_cm'            => 'nullable|integer|min:0|max:300',
            'weight_kg'            => 'nullable|integer|min:0|max:300',
            'head_circumference'   => 'nullable|integer|min:0|max:100',
            'child_order'          => 'nullable|integer|min:1|max:20',
            'siblings_count'       => 'nullable|integer|min:0|max:20',
            'step_siblings_count'  => 'nullable|integer|min:0|max:20',
            'hobby'                => 'nullable|string|max:100',
            'other_skills'         => 'nullable|string|max:1000',

            // Data ayah kandung
            'father_name'          => 'required|string|max:100',
            'father_nik'           => $this->nikRules('NIK ayah'),
            'father_birth_year'    => 'nullable|integer|min:1900|max:' . $maxYear,
            'father_education'     => 'nullable|in:' . implode(',', array_keys(Applicant::PENDIDIKAN)),
            'father_job'           => 'nullable|in:' . implode(',', array_keys(Applicant::PEKERJAAN)),
            'father_income'        => 'nullable|in:' . implode(',', array_keys(Applicant::PENGHASILAN)),

            // Data ibu kandung
            'mother_name'          => 'required|string|max:100',
            'mother_nik'           => $this->nikRules('NIK ibu'),
            'mother_birth_year'    => 'nullable|integer|min:1900|max:' . $maxYear,
            'mother_education'     => 'nullable|in:' . implode(',', array_keys(Applicant::PENDIDIKAN)),
            'mother_job'           => 'nullable|in:' . implode(',', array_keys(Applicant::PEKERJAAN)),
            'mother_income'        => 'nullable|in:' . implode(',', array_keys(Applicant::PENGHASILAN)),

            // Data wali (opsional)
            'guardian_name'        => 'nullable|string|max:100',
            'guardian_relation'    => 'nullable|string|max:50',
            'guardian_nik'         => $this->nikRules('NIK wali'),
            'guardian_birth_year'  => 'nullable|integer|min:1900|max:' . $maxYear,
            'guardian_education'   => 'nullable|in:' . implode(',', array_keys(Applicant::PENDIDIKAN)),
            'guardian_job'         => 'nullable|in:' . implode(',', array_keys(Applicant::PEKERJAAN)),
            'guardian_income'      => 'nullable|in:' . implode(',', array_keys(Applicant::PENGHASILAN)),

            // Pendidikan & registrasi
            'previous_school'      => 'nullable|string|max:100',
            'school_address'       => 'nullable|string|max:255',
            'registration_type'    => 'required|in:' . implode(',', array_keys(Applicant::JENIS_PENDAFTARAN)),
            'nis'                  => 'nullable|string|max:20',
            'un_participant_no'    => 'nullable|string|max:30',
            'ijazah_serial_no'     => 'nullable|string|max:30',
            'ijazah_date'          => 'nullable|date',
            'shun'                 => 'nullable|string|max:50',
            'shun_average'         => 'nullable|numeric|min:0|max:100',
            'admission_year'       => 'required|integer|min:2000|max:' . (now()->year + 1),
            'major'                => 'required|in:' . implode(',', array_keys(Applicant::MAJORS)),
            'jalur'                => 'required|in:' . implode(',', array_keys(Applicant::JALUR)),

            // Kesejahteraan
            'welfare_type'         => 'nullable|in:' . implode(',', array_keys(Applicant::KESEJAHTERAAN)),
            'welfare_card_no'      => 'nullable|string|max:50',
            'welfare_card_name'    => 'nullable|string|max:50',

            // Prestasi & beasiswa (multi-baris)
            'achievements'                    => 'nullable|array',
            'achievements.*.jenis'            => 'nullable|in:' . implode(',', array_keys(Applicant::JENIS_PRESTASI)),
            'achievements.*.tingkat'          => 'nullable|in:' . implode(',', array_keys(Applicant::TINGKAT_PRESTASI)),
            'achievements.*.nama'             => 'nullable|string|max:150',
            'achievements.*.tahun'            => 'nullable|digits:4',
            'achievements.*.penyelenggara'    => 'nullable|string|max:150',
            'achievements.*.peringkat'        => 'nullable|string|max:30',
            'scholarships'                    => 'nullable|array',
            'scholarships.*.jenis'            => 'nullable|in:' . implode(',', array_keys(Applicant::JENIS_BEASISWA)),
            'scholarships.*.keterangan'       => 'nullable|string|max:200',
            'scholarships.*.tahun_mulai'      => 'nullable|digits:4',
            'scholarships.*.tahun_selesai'    => 'nullable|digits:4',

            // Berkas
            'ijazah'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'akta_kelahiran'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'kartu_keluarga'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp_orang_tua'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pas_foto'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'kip_kks'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'prestasi_proof'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }

    /**
     * Bersihkan payload: buang file upload, rapikan baris JSON, dan normalisasi KIP.
     */
    private function prepareData(array $data): array
    {
        foreach (array_keys(self::DOCUMENTS) as $input) {
            unset($data[$input]);
        }

        $achievements = $this->cleanRows($data['achievements'] ?? []);
        $scholarships = $this->cleanRows($data['scholarships'] ?? []);

        $data['achievements'] = $achievements ?: null;
        $data['scholarships'] = $scholarships ?: null;

        if ((int) ($data['has_kip'] ?? 0) !== 1) {
            $data['has_kip']      = 0;
            $data['receives_kip'] = 0;
            $data['kip_number']   = null;
        }

        if (($data['nationality'] ?? 'WNI') !== 'WNA') {
            $data['nationality_country'] = null;
        }

        return $data;
    }

    /**
     * Buang baris prestasi/beasiswa yang seluruh kolomnya kosong.
     */
    private function cleanRows(array $rows): array
    {
        $rows = array_map(function ($row) {
            return array_filter((array) $row, fn ($value) => $value !== null && $value !== '');
        }, $rows);

        return array_values(array_filter($rows, fn ($row) => count($row) > 0));
    }

    /**
     * Batasi total ukuran berkas agar tidak melewati post_max_size server.
     */
    private function validateTotalUploadSize(Request $request)
    {
        $totalSize = collect($request->allFiles())
            ->flatten()
            ->sum(fn ($file) => $file instanceof \Illuminate\Http\UploadedFile ? $file->getSize() : 0);

        if ($totalSize > 7 * 1024 * 1024) {
            return back()->withErrors([
                'berkas' => 'Total ukuran semua berkas melebihi 7 MB. Silakan kompres atau unggah berkas yang lebih kecil.',
            ])->withInput();
        }

        return null;
    }

    /**
     * Simpan semua berkas yang diunggah dan kembalikan path per kolom.
     */
    private function storeDocuments(Request $request): array
    {
        $paths = [];

        foreach (self::DOCUMENTS as $input => $config) {
            if ($request->hasFile($input)) {
                $paths[$config['column']] = $request->file($input)->store($config['dir'], 'public');
            }
        }

        return $paths;
    }

    private function validateAge(array $data)
    {
        $admissionYear = (int) ($data['admission_year'] ?? now()->year);
        $admissionDate = \Carbon\Carbon::create($admissionYear, 7, 1);
        $age = (int) $admissionDate->diff(\Carbon\Carbon::parse($data['birth_date']))->y;

        if ($age > Applicant::MAX_AGE_MONTHS) {
            return back()->withErrors([
                'birth_date' => 'Usia calon peserta didik melebihi batas maksimal 21 tahun pada tanggal 1 Juli ' . $admissionYear . '.',
            ])->withInput();
        }

        return null;
    }
}
