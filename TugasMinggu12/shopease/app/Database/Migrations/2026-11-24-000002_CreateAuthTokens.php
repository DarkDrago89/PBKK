<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Token "Ingat saya" (remember me) dengan pola selector + validator:
 * - selector disimpan apa adanya (untuk mencari baris),
 * - validator hanya disimpan HASH-nya, sehingga kebocoran database
 *   tidak bisa langsung dipakai untuk login.
 */
class CreateAuthTokens extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'id_user'        => ['type' => 'INT', 'unsigned' => true],
            'selector'       => ['type' => 'VARCHAR', 'constraint' => 24],
            'validator_hash' => ['type' => 'VARCHAR', 'constraint' => 64],
            'expires_at'     => ['type' => 'DATETIME'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('selector');
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('auth_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('auth_tokens');
    }
}
