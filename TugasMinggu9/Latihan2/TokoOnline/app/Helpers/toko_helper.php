<?php

if (! function_exists('rupiah')) {
    /** 125000 -> "Rp125.000" */
    function rupiah(int|float|string|null $angka): string
    {
        return 'Rp' . number_format((float) $angka, 0, ',', '.');
    }
}

if (! function_exists('gambar_produk')) {
    /**
     * URL gambar produk. Jika belum ada gambar upload, dibuat placeholder
     * SVG (data URI) sehingga tetap tampil tanpa koneksi internet.
     */
    function gambar_produk(object $p): string
    {
        if (! empty($p->gambar)) {
            return base_url('uploads/produk/' . $p->gambar);
        }

        $warna = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'][($p->kategori_id ?? 0) % 6];
        $teks  = htmlspecialchars(mb_strtoupper(mb_substr($p->nama, 0, 2)), ENT_XML1);
        $svg   = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300">'
            . '<rect width="100%" height="100%" fill="' . $warna . '"/>'
            . '<text x="50%" y="54%" font-family="Arial,sans-serif" font-size="96" font-weight="bold" fill="#fff" fill-opacity=".85" text-anchor="middle" dominant-baseline="middle">' . $teks . '</text>'
            . '</svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}

if (! function_exists('status_badge')) {
    function status_badge(string $status): string
    {
        [$label, $warna] = config('Toko')->status[$status] ?? [$status, 'secondary'];

        return '<span class="badge bg-' . $warna . ' badge-' . $warna . '">' . esc($label) . '</span>';
    }
}
