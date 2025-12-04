<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin SMK Assalaam',
            'email' => 'admin@smkassalaambandung.sch.id',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'nip' => '198501012010011001',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $admin->id,
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1985-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Raya Assalaam No. 123',
            'kota' => 'Bandung',
            'provinsi' => 'Jawa Barat',
            'bio' => 'Kepala Jurusan RPL',
        ]);

        // Guru Pembimbing
        $guru1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@smkassalaam.sch.id',
            'password' => Hash::make('password'),
            'user_type' => 'guru',
            'nip' => '199001012015011002',
            'phone' => '081234567891',
            'jurusan' => 'RPL',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $guru1->id,
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-05-15',
            'jenis_kelamin' => 'L',
            'spesialisasi' => 'Web Development',
            'skills' => ['PHP', 'Laravel', 'MySQL'],
            'bio' => 'Guru pembimbing project',
        ]);

        // Guru Penguji
        $guru2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@smkassalaam.sch.id',
            'password' => Hash::make('password'),
            'user_type' => 'guru_penguji',
            'nip' => '198805012016012001',
            'phone' => '081234567892',
            'jurusan' => 'RPL',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $guru2->id,
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1988-08-20',
            'jenis_kelamin' => 'P',
            'spesialisasi' => 'Software Engineering',
            'bio' => 'Penguji ujikom',
        ]);

        // Siswa
        $siswa1 = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@student.smkassalaam.sch.id',
            'password' => Hash::make('password'),
            'user_type' => 'siswa',
            'nisn' => '0051234567',
            'phone' => '081234567893',
            'jurusan' => 'RPL',
            'kelas' => 'XII RPL 1',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $siswa1->id,
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2006-03-10',
            'jenis_kelamin' => 'L',
            'tahun_masuk' => 2021,
            'tahun_lulus' => 2024,
            'skills' => ['HTML', 'CSS', 'JavaScript'],
            'bio' => 'Siswa RPL passionate di web development',
        ]);
    }
}
