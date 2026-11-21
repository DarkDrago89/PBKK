<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * KRS = Kartu Rencana Studi. Satu baris = satu mata kuliah yang diambil
 * mahasiswa pada satu tahun ajaran. Kolom nilai diisi dosen (menjadi KHS).
 */
class CreateKrs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'mahasiswa_id'  => ['type' => 'INTEGER', 'unsigned' => true],
            'matakuliah_id' => ['type' => 'INTEGER', 'unsigned' => true],
            'tahun_ajaran'  => ['type' => 'VARCHAR', 'constraint' => 20],
            'nilai_angka'   => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'nilai_huruf'   => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['mahasiswa_id', 'matakuliah_id', 'tahun_ajaran']);
        $this->forge->addForeignKey('mahasiswa_id', 'mahasiswa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('matakuliah_id', 'matakuliah', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('krs');
    }

    public function down()
    {
        $this->forge->dropTable('krs');
    }
}
