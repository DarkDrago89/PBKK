<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model tabel users (sesuai Hands-On Lab).
 */
class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id_user';
    protected $returnType    = 'object';
    protected $allowedFields = ['name', 'email', 'password', 'role', 'phone', 'address'];
    protected $useTimestamps = true;

    public const ROLES = ['customer', 'admin', 'operator'];

    /** Tujuan redirect setelah login, per role. */
    public const HOME = [
        'admin'    => '/admin',
        'operator' => '/operator',
        'customer' => '/',
    ];

    public function findByEmail(string $email): ?object
    {
        return $this->where('email', strtolower(trim($email)))->first();
    }

    /** Daftar user untuk halaman Kelola User: cari nama/email + filter role. */
    public function search(?string $q, ?string $role, int $perPage = 10): array
    {
        if ($q) {
            $this->groupStart()->like('name', $q)->orLike('email', $q)->groupEnd();
        }
        if ($role && in_array($role, self::ROLES, true)) {
            $this->where('role', $role);
        }

        return $this->orderBy('id_user', 'DESC')->paginate($perPage);
    }

    /** Jumlah user per role, contoh: ['admin' => 1, 'customer' => 5, ...] */
    public function countByRole(): array
    {
        $hasil = array_fill_keys(self::ROLES, 0);
        foreach ($this->select('role, COUNT(*) AS jumlah')->groupBy('role')->findAll() as $r) {
            $hasil[$r->role] = (int) $r->jumlah;
        }

        return $hasil;
    }

    /** Registrasi per hari selama $hari terakhir (untuk grafik dashboard). */
    public function registrasiHarian(int $hari = 7): array
    {
        $mulai = date('Y-m-d', strtotime('-' . ($hari - 1) . ' days'));
        $rows  = $this->select("DATE(created_at) AS tgl, COUNT(*) AS jumlah")
            ->where('created_at >=', $mulai . ' 00:00:00')
            ->groupBy('tgl')
            ->findAll();
        $map = array_column($rows, 'jumlah', 'tgl');

        $hasil = [];
        for ($i = $hari - 1; $i >= 0; $i--) {
            $tgl         = date('Y-m-d', strtotime("-{$i} days"));
            $hasil[$tgl] = (int) ($map[$tgl] ?? 0);
        }

        return $hasil;
    }
}
