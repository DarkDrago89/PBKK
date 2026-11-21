<?php

namespace App\Controllers;

/**
 * Latihan "Hello World" di CodeIgniter.
 */
class Hello extends BaseController
{
    // URL: /hello
    public function index(): string
    {
        return view('hello', [
            'title' => 'Hello World',
            'nama'  => 'World',
        ]);
    }

    // URL: /hello/Agil  -> segmen URL dikirim router sebagai parameter $nama
    public function nama(string $nama): string
    {
        return view('hello', [
            'title' => 'Hello ' . $nama,
            'nama'  => urldecode($nama),
        ]);
    }
}
