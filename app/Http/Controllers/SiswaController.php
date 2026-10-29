<?php

namespace App\Http\Controllers;

use App\Models\Applicant;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $applicant = Applicant::where('user_id', auth()->id())->first();

        return view('siswa.dashboard', [
            'applicant' => $applicant,
            'majors'    => Applicant::MAJORS,
            'jalurs'    => Applicant::JALUR,
            'statuses'  => Applicant::STATUSES,
        ]);
    }
}
