<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model tabel orders (pesanan customer).
 */
class OrderModel extends Model
{
    protected $table         = 'orders';
    protected $primaryKey    = 'id_order';
    protected $returnType    = 'object';
    protected $useTimestamps = true;
    protected $allowedFields = ['invoice', 'id_user', 'id_product', 'product_name', 'price', 'qty', 'total', 'status', 'shipping_address', 'notes'];

    public const STATUS = ['menunggu', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];

    /** [label, kelas warna badge, ikon] untuk tampilan. */
    public const BADGE = [
        'menunggu'   => ['Menunggu',   'warning',   'hourglass-split'],
        'diproses'   => ['Diproses',   'info',      'box-seam'],
        'dikirim'    => ['Dikirim',    'primary',   'truck'],
        'selesai'    => ['Selesai',    'success',   'check2-circle'],
        'dibatalkan' => ['Dibatalkan', 'secondary', 'x-circle'],
    ];

    /** Alur status yang boleh dilakukan admin/operator: status sekarang => status berikutnya. */
    public const TRANSISI = [
        'menunggu' => ['diproses', 'dibatalkan'],
        'diproses' => ['dikirim', 'dibatalkan'],
        'dikirim'  => ['selesai'],
    ];

    public static function bolehBerubah(string $dari, string $ke): bool
    {
        return in_array($ke, self::TRANSISI[$dari] ?? [], true);
    }

    /**
     * Apakah customer boleh membatalkan pesanannya sendiri?
     * (Kepemilikan pesanan sudah dijamin oleh query milikUser(); di sini cukup aturan bisnisnya.)
     */
    public static function bisaDibatalkan(object $order): bool
    {
        // TODO(human): tentukan kapan customer boleh membatalkan pesanannya sendiri.
        //   Data yang tersedia: $order->status ('menunggu'|'diproses'|'dikirim'|'selesai'|'dibatalkan'),
        //   $order->created_at ('Y-m-d H:i:s'), $order->total.
        //   Dipakai di dua tempat: tombol "Batalkan Pesanan" (order_detail.php) dan
        //   OrderController::cancel() -> stok dikembalikan otomatis jika pembatalan disetujui.
        return false; // placeholder: pembatalan oleh customer dinonaktifkan sampai aturan diisi
    }

    public static function buatInvoice(): string
    {
        return 'INV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }

    /** Pesanan milik satu user (dipakai untuk mencegah customer membuka pesanan orang lain). */
    public function milikUser(int $idUser): static
    {
        return $this->where('orders.id_user', $idUser);
    }

    /** Ringkasan pesanan untuk Dashboard Customer. */
    public function ringkasanUser(int $idUser): array
    {
        $hasil = array_fill_keys(self::STATUS, 0) + ['total_pesanan' => 0, 'total_belanja' => 0];
        $rows  = $this->select('status, COUNT(*) AS jumlah, SUM(total) AS nilai')
            ->where('id_user', $idUser)->groupBy('status')->findAll();

        foreach ($rows as $r) {
            $hasil[$r->status] = (int) $r->jumlah;
            $hasil['total_pesanan'] += (int) $r->jumlah;
            if ($r->status !== 'dibatalkan') {
                $hasil['total_belanja'] += (int) $r->nilai;
            }
        }

        return $hasil;
    }

    /** Daftar pesanan untuk admin/operator, dengan nama customer. */
    public function search(?string $q, ?string $status, int $perPage): array
    {
        $this->select('orders.*, users.name AS customer, users.email')
            ->join('users', 'users.id_user = orders.id_user');
        if ($q) {
            $this->groupStart()->like('orders.invoice', $q)->orLike('users.name', $q)->orLike('orders.product_name', $q)->groupEnd();
        }
        if ($status && in_array($status, self::STATUS, true)) {
            $this->where('orders.status', $status);
        }

        return $this->orderBy('orders.id_order', 'DESC')->paginate($perPage);
    }

    /** Jumlah pesanan per status (semua customer). */
    public function countByStatus(): array
    {
        $hasil = array_fill_keys(self::STATUS, 0);
        foreach ($this->select('status, COUNT(*) AS jumlah')->groupBy('status')->findAll() as $r) {
            $hasil[$r->status] = (int) $r->jumlah;
        }

        return $hasil;
    }

    /** Omzet pesanan selesai per hari, $hari terakhir (grafik dashboard admin). */
    public function omzetHarian(int $hari = 7): array
    {
        $mulai = date('Y-m-d', strtotime('-' . ($hari - 1) . ' days'));
        $rows  = $this->select('DATE(created_at) AS tgl, SUM(total) AS nilai')
            ->where('status !=', 'dibatalkan')
            ->where('created_at >=', $mulai . ' 00:00:00')
            ->groupBy('tgl')->findAll();
        $map = array_column($rows, 'nilai', 'tgl');

        $hasil = [];
        for ($i = $hari - 1; $i >= 0; $i--) {
            $tgl         = date('Y-m-d', strtotime("-{$i} days"));
            $hasil[$tgl] = (int) ($map[$tgl] ?? 0);
        }

        return $hasil;
    }
}
