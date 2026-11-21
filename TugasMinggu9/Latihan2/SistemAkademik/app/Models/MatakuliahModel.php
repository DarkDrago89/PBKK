<?php

namespace App\Models;

use CodeIgniter\Model;

class MatakuliahModel extends Model
{
    protected $table         = 'matakuliah';
    protected $returnType    = 'object';
    protected $allowedFields = ['kode', 'nama', 'sks', 'semester', 'dosen_id'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'id'       => 'permit_empty|is_natural_no_zero',
        'kode'     => 'required|alpha_numeric|max_length[10]|is_unique[matakuliah.kode,id,{id}]',
        'nama'     => 'required|min_length[3]|max_length[100]',
        'sks'      => 'required|integer|in_list[1,2,3,4,6]',
        'semester' => 'required|integer|greater_than[0]|less_than[9]',
        'dosen_id' => 'permit_empty|is_not_unique[dosen.id]',
    ];

    protected $validationMessages = [
        'kode' => ['is_unique' => 'Kode mata kuliah sudah dipakai.'],
    ];

    /** Query dasar: mata kuliah + nama dosen pengampu (LEFT JOIN). */
    public function withDosen(): static
    {
        return $this->select('matakuliah.*, dosen.nama AS dosen_nama')
            ->join('dosen', 'dosen.id = matakuliah.dosen_id', 'left');
    }

    public function search(?string $q, int $perPage = 10): array
    {
        $this->withDosen();
        if ($q) {
            $this->groupStart()->like('matakuliah.kode', $q)->orLike('matakuliah.nama', $q)->orLike('dosen.nama', $q)->groupEnd();
        }

        return $this->orderBy('matakuliah.semester')->orderBy('matakuliah.kode')->paginate($perPage);
    }

    /** Mata kuliah yang diampu dosen + jumlah peserta pada tahun ajaran tertentu. */
    public function diampu(int $dosenId, string $tahunAjaran): array
    {
        return $this->select('matakuliah.*, COUNT(krs.id) AS peserta, SUM(CASE WHEN krs.nilai_huruf IS NULL AND krs.id IS NOT NULL THEN 1 ELSE 0 END) AS belum_dinilai')
            ->join('krs', 'krs.matakuliah_id = matakuliah.id AND krs.tahun_ajaran = ' . $this->db->escape($tahunAjaran), 'left', false)
            ->where('matakuliah.dosen_id', $dosenId)
            ->groupBy('matakuliah.id')
            ->orderBy('matakuliah.kode')
            ->findAll();
    }
}
