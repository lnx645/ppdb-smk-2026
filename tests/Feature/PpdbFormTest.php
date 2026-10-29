<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PpdbFormTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name'             => 'Calon Siswa',
            'nisn'             => '1234567890',
            'nik'              => '3201010101010001',
            'kk_number'        => '3201010101010002',
            'email'            => 'calon@test.com',
            'phone'            => '081234567890',
            'gender'           => 'L',
            'birth_place'      => 'Bandung',
            'birth_date'       => '2008-05-10',
            'religion'         => 'islam',
            'nationality'      => 'WNI',
            'has_kip'          => '0',
            'address'          => 'Jl. Merdeka No. 10',
            'registration_type'=> 'baru',
            'admission_year'   => '2026',
            'major'            => 'TKJ',
            'jalur'            => 'zonasi',
            'father_name'      => 'Ayah Calon',
            'mother_name'      => 'Ibu Calon',
            'achievements'     => [
                [
                    'jenis'         => 'sains',
                    'tingkat'       => 'kabupaten',
                    'nama'          => 'Lomba Cerdas Cermat',
                    'tahun'         => '2025',
                    'penyelenggara' => 'Panitia O2SN',
                    'peringkat'     => 'Juara 1',
                ],
            ],
            'scholarships'     => [
                [
                    'jenis'         => 'prestasi',
                    'keterangan'    => 'Beasiswa Murid Berprestasi',
                    'tahun_mulai'   => '2024',
                    'tahun_selesai' => '2025',
                ],
            ],
        ], $overrides);
    }

    public function test_nik_invalid_menampilkan_error_dan_isian_lain_tetap_ada(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from('/daftar')
            ->post('/daftar', $this->payload([
                'nik' => '123456789012345', // 15 digit
            ]));

        $response->assertRedirect('/daftar');
        $response->assertSessionHasErrors('nik');
        $response->assertSessionHasInput('name', 'Calon Siswa');
        $response->assertSessionHasInput('nik', '123456789012345');
        $response->assertSessionHasInput('address', 'Jl. Merdeka No. 10');
        $response->assertSessionHasInput('achievements.0.nama', 'Lomba Cerdas Cermat');
        $response->assertSessionHasInput('scholarships.0.keterangan', 'Beasiswa Murid Berprestasi');

        $this->assertDatabaseCount('applicants', 0);
    }

    public function test_pendaftaran_valid_menyimpan_field_fpd_dan_prestasi_beasiswa(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/daftar', $this->payload());

        $response->assertRedirect('/sukses');

        $applicant = Applicant::where('user_id', $user->id)->firstOrFail();

        $this->assertSame('3201010101010001', $applicant->nik);
        $this->assertSame('3201010101010002', $applicant->kk_number);
        $this->assertSame('Bandung', $applicant->birth_place);
        $this->assertSame('Ayah Calon', $applicant->father_name);
        $this->assertSame('Ibu Calon', $applicant->mother_name);
        $this->assertSame('Lomba Cerdas Cermat', $applicant->achievements[0]['nama']);
        $this->assertSame('Beasiswa Murid Berprestasi', $applicant->scholarships[0]['keterangan']);
    }

    public function test_wna_boleh_mengisi_nomor_kitas_bukan_16_digit(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/daftar', $this->payload([
            'nik'                 => 'KITAS-12345',
            'nationality'         => 'WNA',
            'nationality_country' => 'Malaysia',
        ]));

        $response->assertRedirect('/sukses');

        $this->assertDatabaseHas('applicants', [
            'user_id' => $user->id,
            'nik'     => 'KITAS-12345',
        ]);
    }
}
