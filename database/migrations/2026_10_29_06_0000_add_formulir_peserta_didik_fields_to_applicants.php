<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            // Data pribadi (F-PD)
            $table->string('nik', 20)->nullable();
            $table->string('kk_number', 20)->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->string('birth_certificate_no', 50)->nullable();
            $table->string('religion', 30)->nullable();
            $table->string('nationality', 10)->default('WNI');
            $table->string('nationality_country', 50)->nullable();
            $table->string('special_needs', 50)->nullable();
            $table->boolean('has_kip')->default(false);
            $table->boolean('receives_kip')->default(false);
            $table->string('kip_number', 50)->nullable();

            // Alamat & tempat tinggal (F-PD)
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('dusun', 100)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('latitude', 30)->nullable();
            $table->string('longitude', 30)->nullable();
            $table->string('residence_type', 30)->nullable();
            $table->string('transportation', 50)->nullable();
            $table->string('distance_to_school', 20)->nullable();
            $table->unsignedInteger('distance_km')->nullable();
            $table->unsignedTinyInteger('travel_time_hours')->nullable();
            $table->unsignedTinyInteger('travel_time_minutes')->nullable();

            // Data periodik (F-PD)
            $table->unsignedInteger('height_cm')->nullable();
            $table->unsignedInteger('weight_kg')->nullable();
            $table->unsignedInteger('head_circumference')->nullable();
            $table->unsignedInteger('child_order')->nullable();
            $table->unsignedInteger('siblings_count')->nullable();
            $table->unsignedInteger('step_siblings_count')->nullable();
            $table->string('hobby', 100)->nullable();
            $table->text('other_skills')->nullable();

            // Data ayah kandung (F-PD)
            $table->string('father_name', 100)->nullable();
            $table->string('father_nik', 20)->nullable();
            $table->unsignedSmallInteger('father_birth_year')->nullable();
            $table->string('father_education', 30)->nullable();
            $table->string('father_job', 40)->nullable();
            $table->string('father_income', 30)->nullable();

            // Data ibu kandung (F-PD)
            $table->string('mother_name', 100)->nullable();
            $table->string('mother_nik', 20)->nullable();
            $table->unsignedSmallInteger('mother_birth_year')->nullable();
            $table->string('mother_education', 30)->nullable();
            $table->string('mother_job', 40)->nullable();
            $table->string('mother_income', 30)->nullable();

            // Data wali (F-PD)
            $table->string('guardian_name', 100)->nullable();
            $table->string('guardian_relation', 50)->nullable();
            $table->string('guardian_nik', 20)->nullable();
            $table->unsignedSmallInteger('guardian_birth_year')->nullable();
            $table->string('guardian_education', 30)->nullable();
            $table->string('guardian_job', 40)->nullable();
            $table->string('guardian_income', 30)->nullable();

            // Kontak (F-PD)
            $table->string('home_phone', 20)->nullable();

            // Registrasi & pendidikan (F-PD)
            $table->text('school_address')->nullable();
            $table->string('registration_type', 20)->default('baru');
            $table->string('nis', 20)->nullable();
            $table->string('un_participant_no', 30)->nullable();
            $table->string('ijazah_serial_no', 30)->nullable();
            $table->date('ijazah_date')->nullable();
            $table->decimal('shun_average', 5, 2)->nullable();

            // Kesejahteraan peserta didik (F-PD)
            $table->string('welfare_type', 30)->nullable();
            $table->string('welfare_card_no', 50)->nullable();
            $table->string('welfare_card_name', 50)->nullable();

            // Prestasi & beasiswa (F-PD, multi-baris disimpan sebagai JSON)
            $table->text('achievements')->nullable();
            $table->text('scholarships')->nullable();

            // Berkas persyaratan (contoh formulir PPDB SMK)
            $table->string('akta_kelahiran_path')->nullable();
            $table->string('kartu_keluarga_path')->nullable();
            $table->string('ktp_orang_tua_path')->nullable();
            $table->string('pas_foto_path')->nullable();
            $table->string('kip_kks_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 'kk_number', 'birth_place', 'birth_certificate_no', 'religion',
                'nationality', 'nationality_country', 'special_needs', 'has_kip',
                'receives_kip', 'kip_number', 'rt', 'rw', 'dusun', 'kelurahan',
                'kecamatan', 'postal_code', 'latitude', 'longitude', 'residence_type',
                'transportation', 'distance_to_school', 'distance_km', 'travel_time_hours',
                'travel_time_minutes', 'height_cm', 'weight_kg', 'head_circumference',
                'child_order', 'siblings_count', 'step_siblings_count', 'hobby',
                'other_skills', 'father_name', 'father_nik', 'father_birth_year',
                'father_education', 'father_job', 'father_income', 'mother_name',
                'mother_nik', 'mother_birth_year', 'mother_education', 'mother_job',
                'mother_income', 'guardian_name', 'guardian_relation', 'guardian_nik',
                'guardian_birth_year', 'guardian_education', 'guardian_job',
                'guardian_income', 'home_phone', 'school_address', 'registration_type',
                'nis', 'un_participant_no', 'ijazah_serial_no', 'ijazah_date',
                'shun_average', 'welfare_type', 'welfare_card_no', 'welfare_card_name',
                'achievements', 'scholarships', 'akta_kelahiran_path',
                'kartu_keluarga_path', 'ktp_orang_tua_path', 'pas_foto_path',
                'kip_kks_path',
            ]);
        });
    }
};
