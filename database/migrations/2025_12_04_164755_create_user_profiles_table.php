<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Personal Information
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();

            // Academic Info (Siswa)
            $table->string('nama_ortu')->nullable();
            $table->string('no_hp_ortu')->nullable();
            $table->year('tahun_masuk')->nullable();
            $table->year('tahun_lulus')->nullable();

            // Professional Info (Guru)
            $table->string('pendidikan_terakhir')->nullable(); // S1, S2, S3
            $table->string('universitas')->nullable();
            $table->string('jurusan_kuliah')->nullable();
            $table->string('gelar')->nullable();
            $table->string('spesialisasi')->nullable(); // Programming, Database, Network, etc
            $table->year('tahun_mengajar')->nullable();

            // Social Media & Links
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('instagram')->nullable();

            // Additional
            $table->text('skills')->nullable(); // JSON: ['PHP', 'Laravel', 'Vue.js']
            $table->text('certifications')->nullable(); // JSON: [{'name': 'AWS', 'year': 2023}]
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();

            // Preferences
            $table->boolean('show_email')->default(false);
            $table->boolean('show_phone')->default(false);
            $table->string('theme')->default('light'); // light, dark

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
