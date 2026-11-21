<?php

/**
 * Helper akademik. Dimuat lewat $helpers di BaseController,
 * sehingga bisa dipakai di controller maupun view.
 */

if (! function_exists('nilai_huruf')) {
    /**
     * Konversi nilai angka (0-100) ke nilai huruf.
     * Skala: A, AB, B, BC, C, D, E.
     */
    function nilai_huruf(float $angka): string
    {
        return match (true) {
            $angka >= 86 => 'A',
            $angka >= 76 => 'AB',
            $angka >= 66 => 'B',
            $angka >= 61 => 'BC',
            $angka >= 56 => 'C',
            $angka >= 41 => 'D',
            default      => 'E',
        };
    }
}

if (! function_exists('nilai_bobot')) {
    /** Bobot nilai huruf untuk perhitungan IP. */
    function nilai_bobot(?string $huruf): ?float
    {
        return [
            'A' => 4.0, 'AB' => 3.5, 'B' => 3.0, 'BC' => 2.5, 'C' => 2.0, 'D' => 1.0, 'E' => 0.0,
        ][$huruf] ?? null;
    }
}

if (! function_exists('hitung_ip')) {
    /**
     * IP = Σ(sks × bobot) / Σ sks, hanya untuk mata kuliah yang sudah dinilai.
     *
     * @param iterable<object> $rows baris KRS yang punya properti sks & nilai_huruf
     */
    function hitung_ip(iterable $rows): array
    {
        $totalSks = 0;
        $totalMutu = 0.0;

        foreach ($rows as $r) {
            $bobot = nilai_bobot($r->nilai_huruf);
            if ($bobot === null) {
                continue; // belum dinilai, tidak dihitung
            }
            $totalSks  += (int) $r->sks;
            $totalMutu += (int) $r->sks * $bobot;
        }

        return [
            'sks'  => $totalSks,
            'mutu' => $totalMutu,
            'ip'   => $totalSks > 0 ? round($totalMutu / $totalSks, 2) : 0.0,
        ];
    }
}
