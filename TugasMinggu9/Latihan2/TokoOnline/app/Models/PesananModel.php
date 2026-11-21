<?php

namespace App\Models;

use App\Exceptions\StokException;
use CodeIgniter\Model;
use Throwable;

class PesananModel extends Model
{
    protected $table         = 'pesanan';
    protected $returnType    = 'object';
    protected $allowedFields = ['kode', 'user_id', 'nama_penerima', 'telepon', 'alamat', 'catatan', 'subtotal', 'ongkir', 'total', 'status'];
    protected $useTimestamps = true;

    /**
     * Membuat pesanan dari isi keranjang dalam SATU transaksi:
     *   1. kurangi stok tiap produk (gagal bila stok kurang)
     *   2. simpan header pesanan
     *   3. simpan detail pesanan
     * Bila salah satu langkah gagal, semua perubahan di-rollback.
     *
     * @param list<object> $items hasil service('keranjang')->isi()
     *
     * @throws StokException
     */
    public function buat(int $userId, array $penerima, array $items, array $biaya): string
    {
        $kode = 'INV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

        $this->db->transBegin();

        try {
            foreach ($items as $it) {
                // UPDATE ... SET stok = stok - qty WHERE id = ? AND stok >= qty
                // Pengecekan & pengurangan dalam satu query -> aman dari dua checkout bersamaan
                $this->db->table('produk')
                    ->set('stok', 'stok - ' . (int) $it->qty, false)
                    ->where('id', $it->id)
                    ->where('stok >=', (int) $it->qty)
                    ->update();

                if ($this->db->affectedRows() === 0) {
                    throw StokException::untuk($it->nama);
                }
            }

            $pesananId = $this->insert([
                'kode'          => $kode,
                'user_id'       => $userId,
                'nama_penerima' => $penerima['nama_penerima'],
                'telepon'       => $penerima['telepon'],
                'alamat'        => $penerima['alamat'],
                'catatan'       => ($penerima['catatan'] ?? '') ?: null, // field opsional
                'subtotal'      => $biaya['subtotal'],
                'ongkir'        => $biaya['ongkir'],
                'total'         => $biaya['total'],
                'status'        => 'menunggu',
            ]);

            (new PesananDetailModel())->insertBatch(array_map(static fn ($it) => [
                'pesanan_id'  => $pesananId,
                'produk_id'   => $it->id,
                'nama_produk' => $it->nama,
                'harga'       => $it->harga,
                'qty'         => $it->qty,
                'subtotal'    => $it->subtotal,
            ], $items));

            $this->db->transCommit();
        } catch (Throwable $e) {
            $this->db->transRollback();

            throw $e;
        }

        return $kode;
    }

    /**
     * Aturan perubahan status pesanan.
     * Alur normal: menunggu -> diproses -> dikirim -> selesai
     */
    public static function transisiValid(string $dari, string $ke): bool
    {
        // Status yang tidak ada di daftar ini (selesai, dibatalkan) adalah status akhir
        $boleh = [
            'menunggu' => ['diproses', 'dibatalkan'],
            'diproses' => ['dikirim', 'dibatalkan'],
            'dikirim'  => ['selesai'],
        ];

        return in_array($ke, $boleh[$dari] ?? [], true);
    }

    /**
     * Ubah status. Bila dibatalkan, stok dikembalikan (dalam transaksi).
     */
    public function ubahStatus(object $pesanan, string $ke): bool
    {
        if (! self::transisiValid($pesanan->status, $ke)) {
            return false;
        }

        $this->db->transStart();

        if ($ke === 'dibatalkan') {
            foreach ((new PesananDetailModel())->where('pesanan_id', $pesanan->id)->findAll() as $d) {
                if ($d->produk_id !== null) {
                    $this->db->table('produk')->set('stok', 'stok + ' . (int) $d->qty, false)->where('id', $d->produk_id)->update();
                }
            }
        }

        $this->update($pesanan->id, ['status' => $ke]);

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function milikUser(int $userId): array
    {
        return $this->select('pesanan.*, (SELECT SUM(qty) FROM pesanan_detail WHERE pesanan_detail.pesanan_id = pesanan.id) AS jumlah_barang')
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    public function withUser(): static
    {
        return $this->select('pesanan.*, users.nama AS user_nama, users.email AS user_email')
            ->join('users', 'users.id = pesanan.user_id');
    }
}
