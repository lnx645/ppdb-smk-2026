<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

class PpdbController extends Controller
{
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

        $data = $request->validate($this->rules());

        if ($error = $this->validateAge($data)) {
            return $error;
        }

        $ijazahPath = $request->hasFile('ijazah')
            ? $request->file('ijazah')->store('ijazah', 'public')
            : null;
        $prestasiPath = $request->hasFile('prestasi_proof')
            ? $request->file('prestasi_proof')->store('prestasi', 'public')
            : null;

        Applicant::create(array_merge($data, [
            'user_id'        => auth()->id(),
            'ijazah_path'    => $ijazahPath,
            'prestasi_proof' => $prestasiPath,
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

        $data = $request->validate($this->rules($applicant));

        if ($error = $this->validateAge($data)) {
            return $error;
        }

        if ($request->hasFile('ijazah')) {
            if ($applicant->ijazah_path) {
                \Storage::disk('public')->delete($applicant->ijazah_path);
            }
            $data['ijazah_path'] = $request->file('ijazah')->store('ijazah', 'public');
        }
        if ($request->hasFile('prestasi_proof')) {
            if ($applicant->prestasi_proof) {
                \Storage::disk('public')->delete($applicant->prestasi_proof);
            }
            $data['prestasi_proof'] = $request->file('prestasi_proof')->store('prestasi', 'public');
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

    private function rules(?Applicant $applicant = null): array
    {
        $id = $applicant?->id;

        return [
            'name'            => 'required|string|max:100',
            'nisn'            => 'required|string|size:10|unique:applicants,nisn' . ($id ? ",{$id}" : ''),
            'email'           => 'required|email|unique:applicants,email' . ($id ? ",{$id}" : ''),
            'phone'           => 'required|string|max:20',
            'gender'          => 'required|in:L,P',
            'birth_date'      => 'required|date',
            'address'         => 'required|string',
            'major'           => 'required|in:' . implode(',', array_keys(Applicant::MAJORS)),
            'previous_school' => 'nullable|string|max:100',
            'admission_year'  => 'required|integer|min:2000|max:' . (now()->year + 1),
            'jalur'           => 'required|in:' . implode(',', array_keys(Applicant::JALUR)),
            'shun'            => 'nullable|string|max:50',
            'ijazah'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'prestasi_proof'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
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
