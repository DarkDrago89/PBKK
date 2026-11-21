<?php

namespace App\Models;

use CodeIgniter\Model;

class KrsModel extends Model
{
    protected $table         = 'krs';
    protected $returnType    = 'object';
    protected $allowedFields = ['mahasiswa_id', 'matakuliah_id', 'tahun_ajaran', 'nilai_angka', 'nilai_huruf'];
    protected $useTimestamps = true;

    /** Daftar mata kuliah yang diambil mahasiswa (KRS/KHS), opsional per tahun ajaran. */
    public function milikMahasiswa(int $mahasiswaId, ?string $tahunAjaran = null): array
    {
        $this->select('krs.*, matakuliah.kode, matakuliah.nama, matakuliah.sks, matakuliah.semester, dosen.nama AS dosen_nama')
            ->join('matakuliah', 'matakuliah.id = krs.matakuliah_id')
            ->join('dosen', 'dosen.id = matakuliah.dosen_id', 'left')
            ->where('krs.mahasiswa_id', $mahasiswaId);

        if ($tahunAjaran !== null) {
            $this->where('krs.tahun_ajaran', $tahunAjaran);
        }

        return $this->orderBy('krs.tahun_ajaran')->orderBy('matakuliah.kode')->findAll();
    }

    public function totalSks(int $mahasiswaId, string $tahunAjaran): int
    {
        $row = $this->selectSum('matakuliah.sks', 'total')
            ->join('matakuliah', 'matakuliah.id = krs.matakuliah_id')
            ->where(['krs.mahasiswa_id' => $mahasiswaId, 'krs.tahun_ajaran' => $tahunAjaran])
            ->get()->getRow();

        return (int) ($row->total ?? 0);
    }

    /** Peserta sebuah kelas (untuk input nilai oleh dosen). */
    public function peserta(int $matakuliahId, string $tahunAjaran): array
    {
        return $this->select('krs.*, mahasiswa.nim, mahasiswa.nama, mahasiswa.prodi')
            ->join('mahasiswa', 'mahasiswa.id = krs.mahasiswa_id')
            ->where(['krs.matakuliah_id' => $matakuliahId, 'krs.tahun_ajaran' => $tahunAjaran])
            ->orderBy('mahasiswa.nim')
            ->findAll();
    }
}
