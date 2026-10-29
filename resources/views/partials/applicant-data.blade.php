@php
    $mapLabel = fn (array $map, $value) => \App\Models\Applicant::label($map, $value);
    $text = fn ($value, string $suffix = '') => filled($value) ? $value . $suffix : '-';
    $birth = trim(($applicant->birth_place ?? '') . ', ' . ($applicant->birth_date?->format('d M Y') ?? ''), ', ');
    $rtRw = trim(($applicant->rt ? 'RT ' . $applicant->rt : '') . ' / ' . ($applicant->rw ? 'RW ' . $applicant->rw : ''), ' /');
    $jarak = $applicant->distance_to_school
        ? $mapLabel(\App\Models\Applicant::JARAK_SEKOLAH, $applicant->distance_to_school) . ($applicant->distance_km ? ' (' . $applicant->distance_km . ' km)' : '')
        : '-';
    $waktu = ($applicant->travel_time_hours !== null || $applicant->travel_time_minutes !== null)
        ? trim(($applicant->travel_time_hours ?? 0) . ' jam ' . ($applicant->travel_time_minutes ?? 0) . ' menit')
        : '-';
    $koordinat = ($applicant->latitude || $applicant->longitude)
        ? trim(($applicant->latitude ?? '-') . ', ' . ($applicant->longitude ?? '-'))
        : '-';
    $kip = $applicant->has_kip
        ? 'Ya' . ($applicant->kip_number ? ' — No. ' . $applicant->kip_number : '')
        : 'Tidak';

    $sections = [
        [
            'title' => 'Data Pribadi',
            'rows'  => [
                ['Nama Lengkap', $text($applicant->name)],
                ['NISN', $text($applicant->nisn)],
                ['NIK / No. KITAS', $text($applicant->nik)],
                ['No. Kartu Keluarga', $text($applicant->kk_number)],
                ['Jenis Kelamin', $applicant->gender === 'L' ? 'Laki-laki' : ($applicant->gender === 'P' ? 'Perempuan' : '-')],
                ['Tempat, Tanggal Lahir', $birth ?: '-'],
                ['No. Registrasi Akta Lahir', $text($applicant->birth_certificate_no)],
                ['Agama & Kepercayaan', $mapLabel(\App\Models\Applicant::AGAMA, $applicant->religion)],
                ['Kewarganegaraan', $applicant->nationality === 'WNA' ? 'Asing (WNA) — ' . $text($applicant->nationality_country) : 'Indonesia (WNI)'],
                ['Berkebutuhan Khusus', $mapLabel(\App\Models\Applicant::KEBUTUHAN_KHUSUS, $applicant->special_needs)],
                ['Kartu Indonesia Pintar (KIP)', $kip],
                ['Status Penerimaan KIP', $applicant->has_kip ? ($applicant->receives_kip ? 'Sudah menerima' : 'Belum menerima') : '-'],
            ],
        ],
        [
            'title' => 'Alamat & Tempat Tinggal',
            'rows'  => [
                ['Alamat Jalan', $text($applicant->address)],
                ['RT / RW', $rtRw ?: '-'],
                ['Dusun / Kampung', $text($applicant->dusun)],
                ['Kelurahan / Desa', $text($applicant->kelurahan)],
                ['Kecamatan', $text($applicant->kecamatan)],
                ['Kode Pos', $text($applicant->postal_code)],
                ['Tempat Tinggal', $mapLabel(\App\Models\Applicant::TEMPAT_TINGGAL, $applicant->residence_type)],
                ['Moda Transportasi', $mapLabel(\App\Models\Applicant::TRANSPORTASI, $applicant->transportation)],
                ['Jarak ke Sekolah', $jarak],
                ['Waktu Tempuh', $waktu],
                ['Koordinat', $koordinat],
            ],
        ],
        [
            'title' => 'Data Periodik',
            'rows'  => [
                ['Tinggi Badan', $text($applicant->height_cm, ' cm')],
                ['Berat Badan', $text($applicant->weight_kg, ' kg')],
                ['Lingkar Kepala', $text($applicant->head_circumference, ' cm')],
                ['Anak Keberapa', $text($applicant->child_order)],
                ['Jumlah Saudara Kandung', $text($applicant->siblings_count)],
                ['Jumlah Saudara Tiri', $text($applicant->step_siblings_count)],
                ['Hobi', $text($applicant->hobby)],
                ['Kemampuan Lain-lain', $text($applicant->other_skills)],
            ],
        ],
        [
            'title' => 'Data Ayah Kandung',
            'rows'  => [
                ['Nama Ayah', $text($applicant->father_name)],
                ['NIK Ayah', $text($applicant->father_nik)],
                ['Tahun Lahir', $text($applicant->father_birth_year)],
                ['Pendidikan Terakhir', $mapLabel(\App\Models\Applicant::PENDIDIKAN, $applicant->father_education)],
                ['Pekerjaan', $mapLabel(\App\Models\Applicant::PEKERJAAN, $applicant->father_job)],
                ['Penghasilan Bulanan', $mapLabel(\App\Models\Applicant::PENGHASILAN, $applicant->father_income)],
            ],
        ],
        [
            'title' => 'Data Ibu Kandung',
            'rows'  => [
                ['Nama Ibu', $text($applicant->mother_name)],
                ['NIK Ibu', $text($applicant->mother_nik)],
                ['Tahun Lahir', $text($applicant->mother_birth_year)],
                ['Pendidikan Terakhir', $mapLabel(\App\Models\Applicant::PENDIDIKAN, $applicant->mother_education)],
                ['Pekerjaan', $mapLabel(\App\Models\Applicant::PEKERJAAN, $applicant->mother_job)],
                ['Penghasilan Bulanan', $mapLabel(\App\Models\Applicant::PENGHASILAN, $applicant->mother_income)],
            ],
        ],
        [
            'title' => 'Data Wali',
            'rows'  => [
                ['Nama Wali', $text($applicant->guardian_name)],
                ['Hubungan', $text($applicant->guardian_relation)],
                ['NIK Wali', $text($applicant->guardian_nik)],
                ['Tahun Lahir', $text($applicant->guardian_birth_year)],
                ['Pendidikan Terakhir', $mapLabel(\App\Models\Applicant::PENDIDIKAN, $applicant->guardian_education)],
                ['Pekerjaan', $mapLabel(\App\Models\Applicant::PEKERJAAN, $applicant->guardian_job)],
                ['Penghasilan Bulanan', $mapLabel(\App\Models\Applicant::PENGHASILAN, $applicant->guardian_income)],
            ],
        ],
        [
            'title' => 'Kontak',
            'rows'  => [
                ['Email', $text($applicant->email)],
                ['Nomor HP', $text($applicant->phone)],
                ['Telepon Rumah', $text($applicant->home_phone)],
            ],
        ],
        [
            'title' => 'Pendidikan & Pendaftaran',
            'rows'  => [
                ['Jenis Pendaftaran', $mapLabel(\App\Models\Applicant::JENIS_PENDAFTARAN, $applicant->registration_type)],
                ['NIS / Nomor Induk PD', $text($applicant->nis)],
                ['Sekolah Asal', $text($applicant->previous_school)],
                ['Alamat Sekolah Asal', $text($applicant->school_address)],
                ['Nomor Peserta UN', $text($applicant->un_participant_no)],
                ['No. Seri Ijazah', $text($applicant->ijazah_serial_no)],
                ['Tanggal Ijazah', $applicant->ijazah_date?->format('d M Y') ?? '-'],
                ['No. SHUN / SKHUN', $text($applicant->shun)],
                ['Rata-rata SHUN', $text($applicant->shun_average)],
                ['Jurusan Pilihan', $mapLabel($majors, $applicant->major)],
                ['Jalur Pendaftaran', $mapLabel($jalurs, $applicant->jalur)],
                ['Tahun Penerimaan', $text($applicant->admission_year)],
            ],
        ],
        [
            'title' => 'Kesejahteraan Peserta Didik',
            'rows'  => [
                ['Jenis Kesejahteraan', $mapLabel(\App\Models\Applicant::KESEJAHTERAAN, $applicant->welfare_type)],
                ['No. Kartu', $text($applicant->welfare_card_no)],
                ['Nama pada Kartu', $text($applicant->welfare_card_name)],
            ],
        ],
    ];
@endphp

@foreach($sections as $section)
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100">
            <h3 class="font-extrabold text-sm">{{ $section['title'] }}</h3>
        </div>
        <dl class="divide-y divide-slate-100 text-sm">
            @foreach($section['rows'] as [$rowLabel, $value])
                <div class="px-5 py-3 flex flex-col sm:flex-row sm:gap-4">
                    <dt class="sm:w-48 text-xs font-bold text-slate-400 uppercase tracking-wide shrink-0">{{ $rowLabel }}</dt>
                    <dd class="text-slate-700 whitespace-pre-line break-words">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
@endforeach

@if(!empty($applicant->achievements) && count($applicant->achievements))
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100"><h3 class="font-extrabold text-sm">Prestasi</h3></div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-400">
                    <tr>
                        <th class="px-4 py-2.5 text-left font-bold">Jenis</th>
                        <th class="px-4 py-2.5 text-left font-bold">Tingkat</th>
                        <th class="px-4 py-2.5 text-left font-bold">Nama Prestasi</th>
                        <th class="px-4 py-2.5 text-left font-bold">Tahun</th>
                        <th class="px-4 py-2.5 text-left font-bold">Peringkat</th>
                        <th class="px-4 py-2.5 text-left font-bold">Penyelenggara</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($applicant->achievements as $achievement)
                        <tr>
                            <td class="px-4 py-2.5">{{ $mapLabel(\App\Models\Applicant::JENIS_PRESTASI, $achievement['jenis'] ?? null) }}</td>
                            <td class="px-4 py-2.5">{{ $mapLabel(\App\Models\Applicant::TINGKAT_PRESTASI, $achievement['tingkat'] ?? null) }}</td>
                            <td class="px-4 py-2.5">{{ $achievement['nama'] ?? '-' }}</td>
                            <td class="px-4 py-2.5">{{ $achievement['tahun'] ?? '-' }}</td>
                            <td class="px-4 py-2.5">{{ $achievement['peringkat'] ?? '-' }}</td>
                            <td class="px-4 py-2.5">{{ $achievement['penyelenggara'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@if(!empty($applicant->scholarships) && count($applicant->scholarships))
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100"><h3 class="font-extrabold text-sm">Beasiswa</h3></div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-400">
                    <tr>
                        <th class="px-4 py-2.5 text-left font-bold">Jenis</th>
                        <th class="px-4 py-2.5 text-left font-bold">Keterangan</th>
                        <th class="px-4 py-2.5 text-left font-bold">Tahun Mulai</th>
                        <th class="px-4 py-2.5 text-left font-bold">Tahun Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($applicant->scholarships as $scholarship)
                        <tr>
                            <td class="px-4 py-2.5">{{ $mapLabel(\App\Models\Applicant::JENIS_BEASISWA, $scholarship['jenis'] ?? null) }}</td>
                            <td class="px-4 py-2.5">{{ $scholarship['keterangan'] ?? '-' }}</td>
                            <td class="px-4 py-2.5">{{ $scholarship['tahun_mulai'] ?? '-' }}</td>
                            <td class="px-4 py-2.5">{{ $scholarship['tahun_selesai'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
