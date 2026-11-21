<?php

namespace App\Validation;

/**
 * Rule validasi kustom untuk password.
 * Didaftarkan di app/Config/Validation.php ($ruleSets), dipakai sebagai 'password_kuat'.
 */
class PasswordRules
{
    public function password_kuat(?string $str, ?string &$error = null): bool
    {
        // TODO(human): kembalikan true hanya jika $str memenuhi kebijakan password ShopEase.
        //   Pesan error saat gagal sudah disiapkan: "Password harus mengandung huruf besar,
        //   huruf kecil, dan angka." (lihat $messages di AuthController::store).
        //   Panjang minimal 8 sudah dicek rule min_length[8] sebelum rule ini.
        return true; // placeholder: semua password lolos sampai kebijakan diisi
    }
}
