<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $allowedFields = ['username', 'nama', 'password', 'role'];
    protected $useTimestamps = true;

    /**
     * Mengembalikan user jika username & password cocok, selain itu null.
     */
    public function verify(string $username, string $password): ?object
    {
        $user = $this->where('username', $username)->first();

        if ($user === null || ! password_verify($password, $user->password)) {
            return null;
        }

        return $user;
    }
}
