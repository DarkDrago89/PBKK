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
        // Kebijakan: minimal satu huruf besar, satu huruf kecil, dan satu angka.
        // (Panjang minimal 8 sudah dicek oleh rule min_length[8].)
        $str = (string) $str;

        return preg_match("/[A-Z]/", $str) === 1
            && preg_match("/[a-z]/", $str) === 1
            && preg_match("/[0-9]/", $str) === 1;
    }
}
