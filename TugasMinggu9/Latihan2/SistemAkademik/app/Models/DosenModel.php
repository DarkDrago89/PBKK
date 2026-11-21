<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table         = 'dosen';
    protected $returnType    = 'object';
    protected $allowedFields = ['user_id', 'nip', 'nama', 'email'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'id'    => 'permit_empty|is_natural_no_zero',
        'nip'   => 'required|numeric|exact_length[18]|is_unique[dosen.nip,id,{id}]',
        'nama'  => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|max_length[100]',
    ];

    protected $validationMessages = [
        'nip' => ['is_unique' => 'NIP sudah terdaftar.', 'exact_length' => 'NIP harus 18 digit.'],
    ];

    public function search(?string $q, int $perPage = 10): array
    {
        if ($q) {
            $this->groupStart()->like('nip', $q)->orLike('nama', $q)->groupEnd();
        }

        return $this->orderBy('nama')->paginate($perPage);
    }

    /** Untuk dropdown pengampu mata kuliah: [id => nama]. */
    public function dropdown(): array
    {
        return array_column($this->orderBy('nama')->findAll(), 'nama', 'id');
    }
}
