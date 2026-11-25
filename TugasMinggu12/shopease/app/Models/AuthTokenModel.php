<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthTokenModel extends Model
{
    protected $table         = 'auth_tokens';
    protected $returnType    = 'object';
    protected $allowedFields = ['id_user', 'selector', 'validator_hash', 'expires_at', 'created_at'];

    /** Token yang masih berlaku berdasarkan selector. */
    public function findValid(string $selector): ?object
    {
        return $this->where('selector', $selector)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->first();
    }
}
