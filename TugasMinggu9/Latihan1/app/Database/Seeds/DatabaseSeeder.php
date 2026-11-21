<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Jalankan: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('users')->insertBatch([
            [
                'username'   => 'admin',
                'nama'       => 'Administrator',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'username'   => 'user',
                'nama'       => 'Mahasiswa Biasa',
                'password'   => password_hash('user123', PASSWORD_DEFAULT),
                'role'       => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $this->db->table('biodata')->insertBatch([
            ['nim' => '5025241001', 'nama' => 'Budi Santoso',  'jenis_kelamin' => 'L', 'tempat_lahir' => 'Surabaya', 'tanggal_lahir' => '2005-03-12', 'prodi' => 'Teknik Informatika', 'email' => 'budi@student.its.ac.id',  'alamat' => 'Jl. Keputih No. 10, Surabaya',  'foto' => null, 'created_at' => $now, 'updated_at' => $now],
            ['nim' => '5025241002', 'nama' => 'Siti Aminah',   'jenis_kelamin' => 'P', 'tempat_lahir' => 'Malang',   'tanggal_lahir' => '2005-07-25', 'prodi' => 'Sistem Informasi',   'email' => 'siti@student.its.ac.id',  'alamat' => 'Jl. Ijen No. 5, Malang',         'foto' => null, 'created_at' => $now, 'updated_at' => $now],
            ['nim' => '5025241003', 'nama' => 'Andi Wijaya',   'jenis_kelamin' => 'L', 'tempat_lahir' => 'Sidoarjo', 'tanggal_lahir' => '2004-11-02', 'prodi' => 'Teknik Informatika', 'email' => 'andi@student.its.ac.id',  'alamat' => 'Jl. Pahlawan No. 21, Sidoarjo', 'foto' => null, 'created_at' => $now, 'updated_at' => $now],
            ['nim' => '5025241004', 'nama' => 'Rina Sari',     'jenis_kelamin' => 'P', 'tempat_lahir' => 'Gresik',   'tanggal_lahir' => '2005-01-18', 'prodi' => 'Sistem Informasi',   'email' => 'rina@student.its.ac.id',  'alamat' => 'Jl. Veteran No. 7, Gresik',     'foto' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
