<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDosen extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'INTEGER', 'unsigned' => true],
            'nip'        => ['type' => 'VARCHAR', 'constraint' => 20],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nip');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dosen');
    }

    public function down()
    {
        $this->forge->dropTable('dosen');
    }
}
