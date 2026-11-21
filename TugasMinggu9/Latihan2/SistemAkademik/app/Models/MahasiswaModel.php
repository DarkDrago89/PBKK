<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table         = 'mahasiswa';
    protected $returnType    = 'object';
    protected $allowedFields = ['user_id', 'nim', 'nama', 'prodi', 'angkatan', 'email'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'id'       => 'permit_empty|is_natural_no_zero',
        'nim'      => 'required|numeric|exact_length[10]|is_unique[mahasiswa.nim,id,{id}]',
        'nama'     => 'required|min_length[3]|max_length[100]',
        'prodi'    => 'required|max_length[50]',
        'angkatan' => 'required|integer|greater_than[2000]|less_than[2100]',
        'email'    => 'required|valid_email|max_length[100]',
    ];

    protected $validationMessages = [
        'nim' => ['is_unique' => 'NIM sudah terdaftar.', 'exact_length' => 'NIM harus 10 digit.'],
    ];

    public function search(?string $q, int $perPage = 10): array
    {
        if ($q) {
            $this->groupStart()->like('nim', $q)->orLike('nama', $q)->orLike('prodi', $q)->groupEnd();
        }

        return $this->orderBy('nim')->paginate($perPage);
    }
}
