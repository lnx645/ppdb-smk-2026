<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->integer('admission_year')->default(2026);
            $table->enum('jalur', ['zonasi', 'prestasi', 'perpindahan'])->default('zonasi');
            $table->string('shun', 50)->nullable();
            $table->string('prestasi_proof', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn(['admission_year', 'jalur', 'shun', 'prestasi_proof']);
        });
    }
};
