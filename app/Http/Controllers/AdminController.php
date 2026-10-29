<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private function guard()
    {
        return session('admin') ? null : redirect()->route('admin.login');
    }

    public function dashboard(Request $request)
    {
        if ($redirect = $this->guard()) return $redirect;

        $query = Applicant::latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('major')) {
            $query->where('major', $request->major);
        }
        if ($request->filled('jalur')) {
            $query->where('jalur', $request->jalur);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('nisn', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $applicants = $query->paginate(15);
        $counts = [
            'total'    => Applicant::count(),
            'pending'  => Applicant::where('status', 'pending')->count(),
            'revision' => Applicant::where('status', 'revision')->count(),
            'accepted' => Applicant::where('status', 'accepted')->count(),
            'rejected' => Applicant::where('status', 'rejected')->count(),
        ];

        return view('admin.dashboard', [
            'applicants' => $applicants,
            'majors'     => Applicant::MAJORS,
            'jalurs'     => Applicant::JALUR,
            'statuses'   => Applicant::STATUSES,
            'counts'     => $counts,
            'filters'    => $request->only(['status', 'major', 'jalur', 'search']),
        ]);
    }

    public function show(Applicant $applicant)
    {
        if ($redirect = $this->guard()) return $redirect;

        return view('admin.show', [
            'applicant' => $applicant,
            'majors'    => Applicant::MAJORS,
            'jalurs'    => Applicant::JALUR,
            'statuses'  => Applicant::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Applicant $applicant)
    {
        if ($redirect = $this->guard()) return $redirect;

        $request->validate(['status' => 'required|in:pending,revision,accepted,rejected']);
        $applicant->update(['status' => $request->status, 'notes' => $request->notes]);

        return back()->with('success', 'Status pendaftar diperbarui.');
    }

    public function destroy(Applicant $applicant)
    {
        if ($redirect = $this->guard()) return $redirect;

        foreach (Applicant::DOCUMENT_COLUMNS as $column) {
            if ($applicant->{$column}) {
                \Storage::disk('public')->delete($applicant->{$column});
            }
        }
        $applicant->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Pendaftar dihapus.');
    }
}
