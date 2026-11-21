<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\KrsModel;
use App\Models\MahasiswaModel;

/**
 * KHS (Kartu Hasil Studi): nilai per semester, IPS, dan IPK.
 */
class Khs extends BaseController
{
    public function index(): string
    {
        $mhsId = (int) session('refId');
        $rows  = (new KrsModel())->milikMahasiswa($mhsId);

        // Kelompokkan per tahun ajaran
        $semester = [];
        foreach ($rows as $r) {
            $semester[$r->tahun_ajaran][] = $r;
        }

        $ips = array_map('hitung_ip', $semester);

        return view('mahasiswa/khs', [
            'title'    => 'Kartu Hasil Studi',
            'mhs'      => (new MahasiswaModel())->find($mhsId),
            'semester' => $semester,
            'ips'      => $ips,
            'ipk'      => hitung_ip($rows),
        ]);
    }
}
