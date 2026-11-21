<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\KrsModel;
use App\Models\MatakuliahModel;

/**
 * Pengisian KRS (Kartu Rencana Studi) untuk tahun ajaran aktif.
 */
class Krs extends BaseController
{
    private KrsModel $krs;
    private int $mhsId;
    private string $ta;
    private int $maxSks;

    public function __construct()
    {
        $this->krs    = new KrsModel();
        $this->mhsId  = (int) session('refId');
        $this->ta     = config('Akademik')->tahunAjaran;
        $this->maxSks = config('Akademik')->maxSks;
    }

    public function index(): string
    {
        $diambil = $this->krs->milikMahasiswa($this->mhsId, $this->ta);
        $mkModel = (new MatakuliahModel())->withDosen();

        if ($diambil !== []) {
            $mkModel->whereNotIn('matakuliah.id', array_column($diambil, 'matakuliah_id'));
        }

        return view('mahasiswa/krs', [
            'title'    => 'Kartu Rencana Studi',
            'ta'       => $this->ta,
            'maxSks'   => $this->maxSks,
            'diambil'  => $diambil,
            'totalSks' => array_sum(array_column($diambil, 'sks')),
            'tersedia' => $mkModel->orderBy('matakuliah.semester')->orderBy('matakuliah.kode')->findAll(),
        ]);
    }

    public function ambil()
    {
        $mk = (new MatakuliahModel())->find((int) $this->request->getPost('matakuliah_id'));

        if ($mk === null) {
            return redirect()->to('mahasiswa/krs')->with('error', 'Mata kuliah tidak ditemukan.');
        }

        $sudah = $this->krs->where([
            'mahasiswa_id'  => $this->mhsId,
            'matakuliah_id' => $mk->id,
            'tahun_ajaran'  => $this->ta,
        ])->countAllResults();

        if ($sudah > 0) {
            return redirect()->to('mahasiswa/krs')->with('error', "{$mk->nama} sudah ada di KRS.");
        }

        $total = $this->krs->totalSks($this->mhsId, $this->ta);
        if ($total + $mk->sks > $this->maxSks) {
            return redirect()->to('mahasiswa/krs')
                ->with('error', "Gagal mengambil {$mk->nama}: total SKS menjadi " . ($total + $mk->sks) . ", melebihi batas {$this->maxSks} SKS.");
        }

        $this->krs->insert([
            'mahasiswa_id'  => $this->mhsId,
            'matakuliah_id' => $mk->id,
            'tahun_ajaran'  => $this->ta,
        ]);

        return redirect()->to('mahasiswa/krs')->with('success', "{$mk->nama} ({$mk->sks} SKS) ditambahkan ke KRS.");
    }

    public function batal(int $krsId)
    {
        $row = $this->krs->find($krsId);

        // Hanya KRS milik sendiri, semester aktif, dan belum dinilai yang boleh dibatalkan
        if ($row === null || (int) $row->mahasiswa_id !== $this->mhsId || $row->tahun_ajaran !== $this->ta) {
            return redirect()->to('mahasiswa/krs')->with('error', 'KRS tidak ditemukan.');
        }
        if ($row->nilai_huruf !== null) {
            return redirect()->to('mahasiswa/krs')->with('error', 'Mata kuliah yang sudah dinilai tidak bisa dibatalkan.');
        }

        $this->krs->delete($krsId);

        return redirect()->to('mahasiswa/krs')->with('success', 'Mata kuliah dibatalkan dari KRS.');
    }
}
