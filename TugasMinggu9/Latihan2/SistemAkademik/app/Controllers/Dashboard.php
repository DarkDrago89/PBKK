<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\KrsModel;
use App\Models\MahasiswaModel;
use App\Models\MatakuliahModel;

/**
 * Satu URL /dashboard, isi berbeda sesuai role yang login.
 */
class Dashboard extends BaseController
{
    public function index(): string
    {
        $ta   = config('Akademik')->tahunAjaran;
        $data = ['title' => 'Dashboard', 'ta' => $ta];

        switch (session('role')) {
            case 'admin':
                $data['jumlah'] = [
                    'mahasiswa'  => (new MahasiswaModel())->countAllResults(),
                    'dosen'      => (new DosenModel())->countAllResults(),
                    'matakuliah' => (new MatakuliahModel())->countAllResults(),
                    'krs'        => (new KrsModel())->where('tahun_ajaran', $ta)->countAllResults(),
                ];
                break;

            case 'dosen':
                $data['kelas'] = (new MatakuliahModel())->diampu((int) session('refId'), $ta);
                break;

            case 'mahasiswa':
                $krs               = new KrsModel();
                $data['mhs']       = (new MahasiswaModel())->find(session('refId'));
                $data['sksAktif']  = $krs->totalSks((int) session('refId'), $ta);
                $data['ipk']       = hitung_ip($krs->milikMahasiswa((int) session('refId')));
                $data['maxSks']    = config('Akademik')->maxSks;
                break;
        }

        return view('dashboard/' . session('role'), $data);
    }
}
