<?php

namespace App\Libraries;

use App\Models\ProdukModel;
use CodeIgniter\Session\Session;

/**
 * Keranjang belanja disimpan di session dalam bentuk [produk_id => qty].
 * Harga & stok TIDAK disimpan di session, selalu diambil ulang dari database
 * agar perubahan harga oleh admin langsung berlaku.
 *
 * Didaftarkan sebagai service: service('keranjang')
 */
class Keranjang
{
    private const KEY = 'keranjang';

    public function __construct(private Session $session)
    {
    }

    /** @return array<int, int> */
    public function raw(): array
    {
        return $this->session->get(self::KEY) ?? [];
    }

    public function qty(int $produkId): int
    {
        return $this->raw()[$produkId] ?? 0;
    }

    public function set(int $produkId, int $qty): void
    {
        $data = $this->raw();
        if ($qty <= 0) {
            unset($data[$produkId]);
        } else {
            $data[$produkId] = $qty;
        }
        $this->session->set(self::KEY, $data);
    }

    public function hapus(int $produkId): void
    {
        $this->set($produkId, 0);
    }

    public function kosongkan(): void
    {
        $this->session->remove(self::KEY);
    }

    /** Jumlah total barang (untuk badge di navbar). */
    public function jumlah(): int
    {
        return array_sum($this->raw());
    }

    /**
     * Isi keranjang lengkap dengan data produk terbaru.
     * Produk yang sudah dihapus admin otomatis dibuang dari keranjang.
     *
     * @return list<object> produk + properti qty & subtotal
     */
    public function isi(): array
    {
        $raw = $this->raw();
        if ($raw === []) {
            return [];
        }

        $produk = (new ProdukModel())->whereIn('id', array_keys($raw))->findAll();
        $hasil  = [];

        foreach ($produk as $p) {
            $p->qty      = $raw[$p->id];
            $p->subtotal = $p->harga * $p->qty;
            $hasil[]     = $p;
        }

        // sinkronkan session dengan produk yang masih ada
        $ada = array_column($hasil, 'qty', 'id');
        if (count($ada) !== count($raw)) {
            $this->session->set(self::KEY, $ada);
        }

        return $hasil;
    }

    /** Ringkasan biaya untuk halaman keranjang & checkout. */
    public function ringkasan(array $isi): array
    {
        $cfg      = config('Toko');
        $subtotal = array_sum(array_column($isi, 'subtotal'));
        $ongkir   = ($subtotal === 0 || $subtotal >= $cfg->gratisOngkirMin) ? 0 : $cfg->ongkir;

        return ['subtotal' => $subtotal, 'ongkir' => $ongkir, 'total' => $subtotal + $ongkir];
    }
}
