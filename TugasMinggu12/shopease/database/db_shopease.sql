-- =============================================================
-- ShopEase - Authentication + Dashboard + Product Management (Tugas Minggu 12)
-- Database MySQL untuk XAMPP / phpMyAdmin.
-- Alternatif: php spark migrate --all && php spark db:seed DatabaseSeeder
-- Password akun demo: admin123, operator123, customer123
-- =============================================================

CREATE DATABASE IF NOT EXISTS db_shopease CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_shopease;

-- Tabel users (Hands-On Lab minggu 11)
CREATE TABLE users (
    id_user INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer','admin','operator') DEFAULT 'customer',
    phone VARCHAR(20),
    address TEXT,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB;

-- Token "Ingat saya": validator hanya disimpan hash SHA-256-nya
CREATE TABLE auth_tokens (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user INT UNSIGNED NOT NULL,
    selector VARCHAR(24) NOT NULL UNIQUE,
    validator_hash VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME NULL,
    CONSTRAINT fk_auth_tokens_user FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Kategori produk
CREATE TABLE categories (
    id_category INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(60) NOT NULL UNIQUE,
    slug VARCHAR(80) NOT NULL UNIQUE,
    icon VARCHAR(40) NOT NULL DEFAULT 'tag',
    color VARCHAR(7) NOT NULL DEFAULT '#6d28d9',
    description TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB;

-- Produk (deleted_at = soft delete)
CREATE TABLE products (
    id_product INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_category INT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(170) NOT NULL UNIQUE,
    description TEXT NULL,
    price INT UNSIGNED NOT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    image VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_by INT UNSIGNED NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    deleted_at DATETIME NULL,
    KEY idx_products_category (id_category),
    CONSTRAINT fk_products_category FOREIGN KEY (id_category) REFERENCES categories(id_category) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_products_creator FOREIGN KEY (created_by) REFERENCES users(id_user) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Pesanan (nama & harga produk disalin saat pesanan dibuat)
CREATE TABLE orders (
    id_order INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice VARCHAR(30) NOT NULL UNIQUE,
    id_user INT UNSIGNED NOT NULL,
    id_product INT UNSIGNED NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    price INT UNSIGNED NOT NULL,
    qty INT UNSIGNED NOT NULL,
    total INT UNSIGNED NOT NULL,
    status ENUM('menunggu','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu',
    shipping_address TEXT NOT NULL,
    notes VARCHAR(255) NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    KEY idx_orders_user (id_user),
    CONSTRAINT fk_orders_user FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_orders_product FOREIGN KEY (id_product) REFERENCES products(id_product) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------
-- Data contoh
-- ---------------------------------------------------------------
INSERT INTO users (id_user, name, email, password, role, phone, address, created_at, updated_at) VALUES
(1, 'Admin ShopEase', 'admin@shopease.test', '$2y$12$8o9GHfeblQ8u2RcKC8RrXe/BhY3uZZz4qbVqzRU0mpIeKhvXyWazK', 'admin', '081100000001', 'Kantor ShopEase, Surabaya', NOW(), NOW()),
(2, 'Operator Gudang', 'operator@shopease.test', '$2y$12$ohCyvPeFo.uiN2BPBBilgOS3n4uMLWZaMX6Amy9jgt7pCB5l3ioGG', 'operator', '081100000002', 'Gudang ShopEase, Sidoarjo', NOW(), NOW()),
(3, 'Agil Lukman', 'agil@shopease.test', '$2y$12$WiJNXYjhAu3xrVzwrhPR4OWaU6wqyO0axZV/nrHuB/kAocoi/auwW', 'customer', '081234567890', 'Jl. Keputih No. 1, Sukolilo, Surabaya', NOW(), NOW()),
(4, 'Siti Rahma', 'siti@shopease.test', '$2y$12$/kb9FXaEOxM.ptzpXQIRNuPPwuQQ5rUJHjbBi61pGP2wmpJzsTpCC', 'customer', '081234567891', 'Jl. Ijen No. 5, Malang', NOW(), NOW());

INSERT INTO categories (id_category, name, slug, icon, color, description, created_at, updated_at) VALUES
(1, 'Elektronik',   'elektronik',   'laptop',       '#6d28d9', 'Gadget, aksesoris komputer, dan perangkat audio.', NOW(), NOW()),
(2, 'Fashion',      'fashion',      'bag-heart',    '#db2777', 'Tas, pakaian, dan perlengkapan gaya sehari-hari.', NOW(), NOW()),
(3, 'Aksesoris',    'aksesoris',    'smartwatch',   '#0891b2', 'Jam tangan pintar, case HP, dan pelengkap gadget.', NOW(), NOW()),
(4, 'Rumah Tangga', 'rumah-tangga', 'house-heart',  '#ea580c', 'Perabot dan peralatan rumah yang praktis.', NOW(), NOW()),
(5, 'Kecantikan',   'kecantikan',   'droplet-half', '#c026d3', 'Perawatan kulit dan kosmetik.', NOW(), NOW());

INSERT INTO products (id_product, id_category, name, slug, description, price, stock, is_active, created_by, created_at, updated_at) VALUES
(1,  1, 'Headphone Wireless ANC',  'headphone-wireless-anc',  'Headphone over-ear dengan Active Noise Cancelling, baterai 30 jam, dan pengisian cepat USB-C.', 899000, 15, 1, 1, NOW(), NOW()),
(2,  3, 'Smartwatch Fit Pro',      'smartwatch-fit-pro',      'Pantau detak jantung, langkah, dan tidur. Tahan air 5 ATM dengan layar AMOLED 1,4 inci.', 649000, 20, 1, 2, NOW(), NOW()),
(3,  2, 'Tas Ransel Urban',        'tas-ransel-urban',        'Ransel 22 liter dengan kompartemen laptop 15 inci dan bahan tahan air.', 289000, 25, 1, 1, NOW(), NOW()),
(4,  1, 'Laptop Stand Aluminium',  'laptop-stand-aluminium',  'Dudukan laptop lipat dengan 6 tingkat ketinggian.', 199000, 4, 1, 2, NOW(), NOW()),
(5,  4, 'Tumbler Stainless 500ml', 'tumbler-stainless-500ml', 'Menjaga minuman tetap panas 12 jam dan dingin 24 jam. Bebas BPA.', 129000, 40, 1, 1, NOW(), NOW()),
(6,  3, 'Case HP MagSafe',         'case-hp-magsafe',         'Case transparan anti-kuning dengan magnet MagSafe bawaan.', 99000, 3, 1, 2, NOW(), NOW()),
(7,  5, 'Serum Vitamin C',         'serum-vitamin-c',         'Serum 20 ml dengan 10% vitamin C.', 159000, 30, 1, 1, NOW(), NOW()),
(8,  4, 'Lampu Meja LED',          'lampu-meja-led',          'Lampu belajar dengan 3 mode warna dan port USB.', 175000, 12, 1, 2, NOW(), NOW());

INSERT INTO orders (invoice, id_user, id_product, product_name, price, qty, total, status, shipping_address, created_at, updated_at) VALUES
('INV-DEMO-0001', 3, 1, 'Headphone Wireless ANC',  899000, 1, 899000, 'selesai',  'Jl. Keputih No. 1, Sukolilo, Surabaya', NOW() - INTERVAL 6 DAY, NOW() - INTERVAL 6 DAY),
('INV-DEMO-0002', 3, 5, 'Tumbler Stainless 500ml', 129000, 2, 258000, 'dikirim',  'Jl. Keputih No. 1, Sukolilo, Surabaya', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),
('INV-DEMO-0003', 3, 7, 'Serum Vitamin C',         159000, 1, 159000, 'menunggu', 'Jl. Keputih No. 1, Sukolilo, Surabaya', NOW(), NOW()),
('INV-DEMO-0004', 4, 2, 'Smartwatch Fit Pro',      649000, 1, 649000, 'menunggu', 'Jl. Ijen No. 5, Malang', NOW(), NOW());
