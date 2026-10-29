@php
    $documentTitle = $title ?? 'Berkas Pendukung';
    $requiredDocuments = [
        'ijazah_path'          => 'Ijazah / STT SMP',
        'akta_kelahiran_path'  => 'Akta Kelahiran',
        'kartu_keluarga_path'  => 'Kartu Keluarga (KK)',
        'ktp_orang_tua_path'   => 'KTP Orang Tua / Wali',
        'pas_foto_path'        => 'Pas Foto 3 × 4',
    ];
    $optionalDocuments = [
        'kip_kks_path'   => 'KIP / KKS / PKH / KIS',
        'prestasi_proof' => 'Bukti Prestasi',
    ];
    $missingDocuments = collect($requiredDocuments)->filter(fn ($label, $column) => blank($applicant->{$column}))->count();
    $isComplete = $missingDocuments === 0;
@endphp

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-3.5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-2">
        <h3 class="font-extrabold text-sm">{{ $documentTitle }}</h3>
        @if($isComplete)
            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 rounded-lg px-2.5 py-1">Lengkap</span>
        @else
            <span class="text-[11px] font-bold text-amber-700 bg-amber-50 rounded-lg px-2.5 py-1">{{ $missingDocuments }} berkas wajib kurang</span>
        @endif
    </div>
    <div class="p-5 divide-y divide-slate-100 text-sm">
        @foreach($requiredDocuments as $column => $label)
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 py-2.5 first:pt-0 last:pb-0">
                <div>
                    @if($applicant->{$column})
                        <span class="font-semibold text-emerald-700">✅ {{ $label }}</span>
                    @else
                        <span class="font-semibold text-rose-600">❌ {{ $label }} — belum diunggah</span>
                    @endif
                </div>
                @if($applicant->{$column})
                    <a href="{{ asset('storage/' . $applicant->{$column}) }}" target="_blank" class="text-xs font-bold text-brand-700 hover:underline whitespace-nowrap">Buka berkas →</a>
                @endif
            </div>
        @endforeach
        @foreach($optionalDocuments as $column => $label)
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 py-2.5 first:pt-0 last:pb-0">
                <div>
                    @if($applicant->{$column})
                        <span class="font-semibold text-emerald-700">✅ {{ $label }}</span>
                    @else
                        <span class="text-slate-400">— {{ $label }} (opsional) — belum diunggah</span>
                    @endif
                </div>
                @if($applicant->{$column})
                    <a href="{{ asset('storage/' . $applicant->{$column}) }}" target="_blank" class="text-xs font-bold text-brand-700 hover:underline whitespace-nowrap">Buka berkas →</a>
                @endif
            </div>
        @endforeach
    </div>
</div>
