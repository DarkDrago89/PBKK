<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'username'   => ['type' => 'VARCHAR', 'constraint' => 30],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'password'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'       => ['type' => 'VARCHAR', 'constraint' => 20], // admin | dosen | mahasiswa
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
