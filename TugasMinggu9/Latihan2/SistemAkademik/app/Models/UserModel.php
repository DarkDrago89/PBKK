<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $returnType    = 'object';
    protected $allowedFields = ['username', 'nama', 'password', 'role'];
    protected $useTimestamps = true;

    public function verify(string $username, string $password): ?object
    {
        $user = $this->where('username', $username)->first();

        return ($user !== null && password_verify($password, $user->password)) ? $user : null;
    }

    /**
     * Membuat akun login untuk dosen/mahasiswa baru.
     * Username dan password awal = NIP/NIM.
     */
    public function buatAkun(string $username, string $nama, string $role): int
    {
        return (int) $this->insert([
            'username' => $username,
            'nama'     => $nama,
            'password' => password_hash($username, PASSWORD_DEFAULT),
            'role'     => $role,
        ]);
    }
}
