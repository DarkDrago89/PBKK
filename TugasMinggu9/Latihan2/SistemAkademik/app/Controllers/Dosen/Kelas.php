<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\KrsModel;
use App\Models\MatakuliahModel;

/**
 * Dosen melihat kelas yang diampu dan menginput nilai mahasiswa.
 */
class Kelas extends BaseController
{
    private int $dosenId;
    private string $ta;

    public function __construct()
    {
        $this->dosenId = (int) session('refId');
        $this->ta      = config('Akademik')->tahunAjaran;
    }

    public function index(): string
    {
        return view('dosen/kelas', [
            'title' => 'Kelas Saya',
            'ta'    => $this->ta,
            'kelas' => (new MatakuliahModel())->diampu($this->dosenId, $this->ta),
        ]);
    }

    public function nilai(int $mkId)
    {
        $mk = $this->kelasMilikSaya($mkId);
        if ($mk === null) {
            return redirect()->to('dosen/kelas')->with('error', 'Anda bukan pengampu mata kuliah tersebut.');
        }

        return view('dosen/nilai', [
            'title'   => 'Input Nilai',
            'ta'      => $this->ta,
            'mk'      => $mk,
            'peserta' => (new KrsModel())->peserta($mkId, $this->ta),
        ]);
    }

    public function simpanNilai(int $mkId)
    {
        if ($this->kelasMilikSaya($mkId) === null) {
            return redirect()->to('dosen/kelas')->with('error', 'Anda bukan pengampu mata kuliah tersebut.');
        }

        $krsModel = new KrsModel();
        // Hanya baris KRS milik kelas ini yang boleh diubah (cegah manipulasi id dari form)
        $valid  = array_column($krsModel->peserta($mkId, $this->ta), 'nim', 'id');
        $input  = (array) $this->request->getPost('nilai');
        $errors = [];
        $batch  = [];

        foreach ($input as $krsId => $angka) {
            $angka = trim((string) $angka);
            if ($angka === '' || ! isset($valid[$krsId])) {
                continue;
            }
            if (! is_numeric($angka) || $angka < 0 || $angka > 100) {
                $errors[] = "Nilai NIM {$valid[$krsId]} harus angka 0–100.";

                continue;
            }
            $batch[] = [
                'id'          => (int) $krsId,
                'nilai_angka' => (float) $angka,
                'nilai_huruf' => nilai_huruf((float) $angka),
            ];
        }

        if ($errors !== []) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        if ($batch !== []) {
            $krsModel->updateBatch($batch, 'id');
        }

        return redirect()->to("dosen/kelas/{$mkId}")->with('success', count($batch) . ' nilai berhasil disimpan.');
    }

    private function kelasMilikSaya(int $mkId): ?object
    {
        return (new MatakuliahModel())->where(['id' => $mkId, 'dosen_id' => $this->dosenId])->first();
    }
}
