<?php

namespace App\Models;

/**
 * Model sederhana berbasis array (belum memakai database),
 * karena materi minggu ini fokus pada Router & Controller.
 */
class BuahModel
{
    private array $data = [
        ['slug' => 'apel-malang',      'nama' => 'Apel Malang',      'kategori' => 'lokal', 'harga' => 32000, 'emoji' => '🍎', 'asal' => 'Malang, Jawa Timur',     'deskripsi' => 'Apel hijau kemerahan dengan rasa manis-asam yang segar.'],
        ['slug' => 'jeruk-medan',      'nama' => 'Jeruk Medan',      'kategori' => 'lokal', 'harga' => 25000, 'emoji' => '🍊', 'asal' => 'Karo, Sumatera Utara',  'deskripsi' => 'Jeruk berair banyak, cocok untuk jus.'],
        ['slug' => 'pisang-cavendish', 'nama' => 'Pisang Cavendish', 'kategori' => 'lokal', 'harga' => 18000, 'emoji' => '🍌', 'asal' => 'Lampung',               'deskripsi' => 'Pisang kuning mulus, sumber energi yang praktis.'],
        ['slug' => 'semangka',         'nama' => 'Semangka',         'kategori' => 'lokal', 'harga' => 15000, 'emoji' => '🍉', 'asal' => 'Banyuwangi, Jawa Timur', 'deskripsi' => 'Semangka merah tanpa biji, manis dan menyegarkan.'],
        ['slug' => 'anggur-shine-muscat', 'nama' => 'Anggur Shine Muscat', 'kategori' => 'impor', 'harga' => 120000, 'emoji' => '🍇', 'asal' => 'Jepang',          'deskripsi' => 'Anggur hijau premium beraroma muscat.'],
        ['slug' => 'kiwi-gold',        'nama' => 'Kiwi Gold',        'kategori' => 'impor', 'harga' => 65000, 'emoji' => '🥝', 'asal' => 'Selandia Baru',         'deskripsi' => 'Kiwi berdaging kuning, lebih manis dari kiwi hijau.'],
    ];

    public function all(): array
    {
        return $this->data;
    }

    public function find(string $slug): ?array
    {
        foreach ($this->data as $b) {
            if ($b['slug'] === $slug) {
                return $b;
            }
        }

        return null;
    }

    public function byKategori(string $kategori): array
    {
        return array_values(array_filter($this->data, static fn ($b) => $b['kategori'] === $kategori));
    }

    public function kategori(): array
    {
        return array_values(array_unique(array_column($this->data, 'kategori')));
    }
}
