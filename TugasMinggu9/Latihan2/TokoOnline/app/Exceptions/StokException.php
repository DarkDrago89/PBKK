<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Dilempar saat checkout bila stok produk tidak mencukupi.
 * Ditangkap controller untuk ditampilkan sebagai pesan ke user.
 */
class StokException extends RuntimeException
{
    public static function untuk(string $namaProduk): self
    {
        return new self("Stok \"{$namaProduk}\" tidak mencukupi. Silakan ubah jumlah di keranjang.");
    }
}
