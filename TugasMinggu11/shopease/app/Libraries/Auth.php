<?php

namespace App\Libraries;

use App\Models\AuthTokenModel;
use App\Models\UserModel;
use CodeIgniter\Session\Session;

/**
 * Pusat logika autentikasi ShopEase. Dipanggil lewat service('auth').
 *
 * Session menyimpan: isLoggedIn, id_user, name, email, role
 * Cookie "Ingat saya": shopease_remember = selector:validator
 */
class Auth
{
    public const REMEMBER_COOKIE = 'shopease_remember';
    public const REMEMBER_DAYS   = 30;

    public function __construct(private Session $session)
    {
    }

    /** Cek email + password. Mengembalikan user bila cocok, selain itu null. */
    public function attempt(string $email, string $password): ?object
    {
        $user = (new UserModel())->findByEmail($email);

        // password_verify tetap dipanggil walau user tidak ada agar waktu respons
        // tidak membocorkan email mana yang terdaftar (timing attack)
        $hash = $user->password ?? '$2y$10$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG';

        return (password_verify($password, $hash) && $user !== null) ? $user : null;
    }

    /** Membuat sesi login. $remember = centang "Ingat saya". */
    public function login(object $user, bool $remember = false): void
    {
        $this->session->regenerate(true); // ID session baru -> cegah session fixation
        $this->session->set([
            'isLoggedIn' => true,
            'id_user'    => (int) $user->id_user,
            'name'       => $user->name,
            'email'      => $user->email,
            'role'       => $user->role,
        ]);

        if ($remember) {
            $this->buatTokenIngat((int) $user->id_user);
        }
    }

    public function logout(): void
    {
        $this->hapusTokenIngat();
        // Hapus data login lalu ganti ID session (session lama tidak bisa dipakai lagi).
        // Tidak memakai destroy() agar pesan flash "berhasil logout" tetap bisa ditampilkan.
        $this->session->remove(['isLoggedIn', 'id_user', 'name', 'email', 'role', 'redirect_url']);
        $this->session->regenerate(true);
    }

    public function check(): bool
    {
        return (bool) $this->session->get('isLoggedIn');
    }

    public function role(): ?string
    {
        return $this->session->get('role');
    }

    /** URL tujuan setelah login sesuai role. */
    public function homeFor(?string $role): string
    {
        return site_url(UserModel::HOME[$role] ?? '/');
    }

    /** Perbarui data session setelah profil diubah. */
    public function refresh(object $user): void
    {
        $this->session->set(['name' => $user->name, 'email' => $user->email, 'role' => $user->role]);
    }

    // ------------------------------------------------------------------
    // Remember me
    // ------------------------------------------------------------------

    /**
     * Dipanggil RememberMeFilter saat belum ada session tetapi ada cookie.
     * Token dipakai sekali lalu diganti (rotasi) agar cookie curian cepat kedaluwarsa.
     */
    public function loginDariCookie(?string $cookie): bool
    {
        if (! $cookie || ! str_contains($cookie, ':')) {
            return false;
        }

        [$selector, $validator] = explode(':', $cookie, 2);
        $tokens = new AuthTokenModel();
        $token  = $tokens->findValid($selector);

        if ($token === null || ! hash_equals($token->validator_hash, hash('sha256', $validator))) {
            $this->hapusCookie();

            return false;
        }

        $user = (new UserModel())->find($token->id_user);
        $tokens->delete($token->id);

        if ($user === null) {
            $this->hapusCookie();

            return false;
        }

        $this->login($user, true); // login + token baru

        return true;
    }

    private function buatTokenIngat(int $idUser): void
    {
        $selector  = bin2hex(random_bytes(12));
        $validator = bin2hex(random_bytes(32));

        (new AuthTokenModel())->insert([
            'id_user'        => $idUser,
            'selector'       => $selector,
            'validator_hash' => hash('sha256', $validator),
            'expires_at'     => date('Y-m-d H:i:s', strtotime('+' . self::REMEMBER_DAYS . ' days')),
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        service('response')->setCookie([
            'name'     => self::REMEMBER_COOKIE,
            'value'    => $selector . ':' . $validator,
            'expire'   => self::REMEMBER_DAYS * DAY,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    private function hapusTokenIngat(): void
    {
        $cookie = service('request')->getCookie(self::REMEMBER_COOKIE);
        if ($cookie && str_contains($cookie, ':')) {
            (new AuthTokenModel())->where('selector', explode(':', $cookie, 2)[0])->delete();
        }
        $this->hapusCookie();
    }

    private function hapusCookie(): void
    {
        service('response')->deleteCookie(self::REMEMBER_COOKIE);
    }
}
