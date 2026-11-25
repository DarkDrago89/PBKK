<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Akun demo. Jalankan: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // [nama, email, password, role, phone, address, hari yang lalu]
        $users = [
            ['Admin ShopEase',    'admin@shopease.test',    'admin123',    'admin',    '081100000001', 'Kantor ShopEase, Surabaya',          14],
            ['Operator Gudang',   'operator@shopease.test', 'operator123', 'operator', '081100000002', 'Gudang ShopEase, Sidoarjo',          10],
            ['Agil Lukman',       'agil@shopease.test',     'customer123', 'customer', '081234567890', 'Jl. Keputih No. 1, Sukolilo, Surabaya', 6],
            ['Siti Rahma',        'siti@shopease.test',     'customer123', 'customer', '081234567891', 'Jl. Ijen No. 5, Malang',             4],
            ['Budi Santoso',      'budi@shopease.test',     'customer123', 'customer', '081234567892', 'Jl. Pahlawan No. 21, Sidoarjo',      4],
            ['Andi Pratama',      'andi@shopease.test',     'customer123', 'customer', '081234567893', 'Jl. Veteran No. 7, Gresik',          2],
            ['Rina Kartika',      'rina@shopease.test',     'customer123', 'customer', null,           null,                                  0],
        ];

        foreach ($users as [$name, $email, $password, $role, $phone, $address, $hari]) {
            $waktu = date('Y-m-d H:i:s', strtotime("-{$hari} days"));
            $this->db->table('users')->insert([
                'name'       => $name,
                'email'      => $email,
                'password'   => password_hash($password, PASSWORD_DEFAULT),
                'role'       => $role,
                'phone'      => $phone,
                'address'    => $address,
                'created_at' => $waktu,
                'updated_at' => $waktu,
            ]);
        }

        $this->call('CatalogSeeder'); // kategori, produk, contoh pesanan
    }
}
