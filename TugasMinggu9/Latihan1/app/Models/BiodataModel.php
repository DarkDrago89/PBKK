<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model: semua akses tabel `biodata` ada di sini.
 * Controller tidak pernah menulis query SQL secara langsung.
 */
class BiodataModel extends Model
{
    protected $table         = 'biodata';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'nim', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'prodi', 'email', 'alamat', 'foto',
    ];

    protected $validationRules = [
        'id'            => 'permit_empty|is_natural_no_zero',
        'nim'           => 'required|numeric|min_length[5]|max_length[20]|is_unique[biodata.nim,id,{id}]',
        'nama'          => 'required|min_length[3]|max_length[100]',
        'jenis_kelamin' => 'required|in_list[L,P]',
        'tempat_lahir'  => 'required|max_length[50]',
        'tanggal_lahir' => 'required|valid_date[Y-m-d]',
        'prodi'         => 'required|max_length[50]',
        'email'         => 'required|valid_email|max_length[100]',
        'alamat'        => 'permit_empty|max_length[500]',
    ];

    protected $validationMessages = [
        'nim' => ['is_unique' => 'NIM sudah terdaftar.'],
    ];

    /**
     * Pencarian berdasarkan NIM / nama / prodi, dengan pagination.
     */
    public function search(?string $keyword, int $perPage = 5): array
    {
        if ($keyword !== null && $keyword !== '') {
            $this->groupStart()
                ->like('nim', $keyword)
                ->orLike('nama', $keyword)
                ->orLike('prodi', $keyword)
                ->groupEnd();
        }

        return $this->orderBy('nama', 'ASC')->paginate($perPage);
    }

    /**
     * Ringkasan untuk kartu statistik di dashboard.
     */
    public function statistik(): array
    {
        return [
            'total'     => $this->countAllResults(),
            'laki'      => $this->where('jenis_kelamin', 'L')->countAllResults(),
            'perempuan' => $this->where('jenis_kelamin', 'P')->countAllResults(),
            'prodi'     => $this->select('prodi, COUNT(*) AS jumlah')
                ->groupBy('prodi')
                ->orderBy('jumlah', 'DESC')
                ->findAll(),
        ];
    }
}
