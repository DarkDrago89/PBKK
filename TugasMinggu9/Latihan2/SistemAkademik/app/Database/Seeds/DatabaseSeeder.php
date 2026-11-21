<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Data contoh. Password setiap akun dosen/mahasiswa = NIP/NIM-nya.
 * Jalankan: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $akun = function (string $username, string $nama, string $role, ?string $password = null) use ($now): int {
            $this->db->table('users')->insert([
                'username'   => $username,
                'nama'       => $nama,
                'password'   => password_hash($password ?? $username, PASSWORD_DEFAULT),
                'role'       => $role,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return (int) $this->db->insertID();
        };

        $akun('admin', 'Administrator Akademik', 'admin', 'admin123');

        // --- Dosen ---
        $dosen = [
            ['198001012005011001', 'Dr. Fajar Nugroho', 'fajar@dosen.example.ac.id'],
            ['198502022010012002', 'Dr. Rina Kartika',  'rina@dosen.example.ac.id'],
            ['199003032015041003', 'Bayu Pratama, M.Kom', 'bayu@dosen.example.ac.id'],
        ];
        $dosenId = [];
        foreach ($dosen as [$nip, $nama, $email]) {
            $this->db->table('dosen')->insert([
                'user_id' => $akun($nip, $nama, 'dosen'), 'nip' => $nip, 'nama' => $nama, 'email' => $email,
                'created_at' => $now, 'updated_at' => $now,
            ]);
            $dosenId[] = (int) $this->db->insertID();
        }

        // --- Mahasiswa ---
        $mhs = [
            ['5025241037', 'Agil Lukman Hakim Muchdi', 'Teknik Informatika', 2024],
            ['5025241001', 'Budi Santoso',             'Teknik Informatika', 2024],
            ['5026241002', 'Siti Aminah',              'Sistem Informasi',   2024],
            ['5025231003', 'Andi Wijaya',              'Teknik Informatika', 2023],
        ];
        $mhsId = [];
        foreach ($mhs as [$nim, $nama, $prodi, $angkatan]) {
            $this->db->table('mahasiswa')->insert([
                'user_id' => $akun($nim, $nama, 'mahasiswa'), 'nim' => $nim, 'nama' => $nama, 'prodi' => $prodi,
                'angkatan' => $angkatan, 'email' => $nim . '@student.example.ac.id',
                'created_at' => $now, 'updated_at' => $now,
            ]);
            $mhsId[] = (int) $this->db->insertID();
        }

        // --- Mata kuliah ---
        $mk = [
            ['IF234101', 'Pemrograman Berbasis Kerangka Kerja', 3, 5, $dosenId[0]],
            ['IF234102', 'Basis Data',                          3, 3, $dosenId[1]],
            ['IF234103', 'Pemrograman Web',                     3, 3, $dosenId[0]],
            ['IF234104', 'Rekayasa Perangkat Lunak',            3, 5, $dosenId[2]],
            ['IF234105', 'Jaringan Komputer',                   4, 5, $dosenId[2]],
            ['IF234106', 'Kecerdasan Buatan',                   3, 5, $dosenId[1]],
            ['IF234107', 'Struktur Data',                       4, 2, $dosenId[1]],
        ];
        $mkId = [];
        foreach ($mk as [$kode, $nama, $sks, $smt, $dsn]) {
            $this->db->table('matakuliah')->insert([
                'kode' => $kode, 'nama' => $nama, 'sks' => $sks, 'semester' => $smt, 'dosen_id' => $dsn,
                'created_at' => $now, 'updated_at' => $now,
            ]);
            $mkId[] = (int) $this->db->insertID();
        }

        // --- KRS semester lalu (sudah dinilai) -> muncul di KHS ---
        $lalu = '2025/2026 Genap';
        $nilaiLalu = [
            // mahasiswa, matakuliah, angka, huruf
            [0, 1, 88, 'A'], [0, 2, 79, 'AB'], [0, 6, 70, 'B'],
            [1, 1, 64, 'BC'], [1, 2, 58, 'C'], [1, 6, 81, 'AB'],
            [2, 1, 90, 'A'], [2, 6, 77, 'AB'],
        ];
        foreach ($nilaiLalu as [$m, $k, $angka, $huruf]) {
            $this->db->table('krs')->insert([
                'mahasiswa_id' => $mhsId[$m], 'matakuliah_id' => $mkId[$k], 'tahun_ajaran' => $lalu,
                'nilai_angka' => $angka, 'nilai_huruf' => $huruf, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // --- KRS semester aktif (belum dinilai) ---
        $aktif = config('Akademik')->tahunAjaran;
        foreach ([[1, 0], [1, 3], [2, 0], [3, 0], [3, 4]] as [$m, $k]) {
            $this->db->table('krs')->insert([
                'mahasiswa_id' => $mhsId[$m], 'matakuliah_id' => $mkId[$k], 'tahun_ajaran' => $aktif,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }
}
