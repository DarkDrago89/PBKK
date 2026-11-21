<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Semua tabel toko dalam satu migration (urutan penting karena foreign key).
 */
class CreateTokoTables extends Migration
{
    public function up()
    {
        $ts = [
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];
        $id = ['id' => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true]];

        // users -------------------------------------------------------
        $this->forge->addField($id + [
            'username' => ['type' => 'VARCHAR', 'constraint' => 30],
            'nama'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'     => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'customer'], // admin | customer
            'telepon'  => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'alamat'   => ['type' => 'TEXT', 'null' => true],
        ] + $ts);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users');

        // kategori ----------------------------------------------------
        $this->forge->addField($id + [
            'nama' => ['type' => 'VARCHAR', 'constraint' => 50],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 60],
        ] + $ts);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('kategori');

        // produk ------------------------------------------------------
        $this->forge->addField($id + [
            'kategori_id' => ['type' => 'INTEGER', 'unsigned' => true, 'null' => true],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 120],
            'deskripsi'   => ['type' => 'TEXT', 'null' => true],
            'harga'       => ['type' => 'INTEGER', 'unsigned' => true],
            'stok'        => ['type' => 'INTEGER', 'unsigned' => true, 'default' => 0],
            'gambar'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ] + $ts);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addForeignKey('kategori_id', 'kategori', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('produk');

        // pesanan -----------------------------------------------------
        $this->forge->addField($id + [
            'kode'          => ['type' => 'VARCHAR', 'constraint' => 20],
            'user_id'       => ['type' => 'INTEGER', 'unsigned' => true],
            'nama_penerima' => ['type' => 'VARCHAR', 'constraint' => 100],
            'telepon'       => ['type' => 'VARCHAR', 'constraint' => 20],
            'alamat'        => ['type' => 'TEXT'],
            'catatan'       => ['type' => 'TEXT', 'null' => true],
            'subtotal'      => ['type' => 'INTEGER', 'unsigned' => true],
            'ongkir'        => ['type' => 'INTEGER', 'unsigned' => true],
            'total'         => ['type' => 'INTEGER', 'unsigned' => true],
            'status'        => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'menunggu'],
        ] + $ts);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pesanan');

        // pesanan_detail ----------------------------------------------
        // nama & harga produk disalin (snapshot) agar riwayat pesanan tidak
        // berubah walaupun produk diedit/dihapus di kemudian hari.
        $this->forge->addField($id + [
            'pesanan_id'  => ['type' => 'INTEGER', 'unsigned' => true],
            'produk_id'   => ['type' => 'INTEGER', 'unsigned' => true, 'null' => true],
            'nama_produk' => ['type' => 'VARCHAR', 'constraint' => 100],
            'harga'       => ['type' => 'INTEGER', 'unsigned' => true],
            'qty'         => ['type' => 'INTEGER', 'unsigned' => true],
            'subtotal'    => ['type' => 'INTEGER', 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pesanan_id', 'pesanan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('pesanan_detail');
    }

    public function down()
    {
        foreach (['pesanan_detail', 'pesanan', 'produk', 'kategori', 'users'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
