<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $returnType    = 'object';
    protected $allowedFields = ['username', 'nama', 'email', 'password', 'role', 'telepon', 'alamat'];
    protected $useTimestamps = true;

    /** Login bisa memakai username atau email. */
    public function verify(string $login, string $password): ?object
    {
        $user = $this->where('username', $login)->orWhere('email', $login)->first();

        return ($user !== null && password_verify($password, $user->password)) ? $user : null;
    }
}
