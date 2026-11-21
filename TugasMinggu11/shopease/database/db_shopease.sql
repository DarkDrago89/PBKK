-- =============================================================
-- ShopEase - Authentication System (Tugas Minggu 11)
-- Database MySQL untuk XAMPP / phpMyAdmin.
-- Alternatif: php spark migrate && php spark db:seed DatabaseSeeder
-- Password akun demo: admin123, operator123, customer123
-- =============================================================

CREATE DATABASE IF NOT EXISTS db_shopease CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_shopease;

-- Tabel users (sesuai Hands-On Lab)
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer','admin','operator') DEFAULT 'customer',
    phone VARCHAR(20),
    address TEXT,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB;

-- Token "Ingat saya" (pengembangan): validator hanya disimpan hash SHA-256-nya
CREATE TABLE auth_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    selector VARCHAR(24) NOT NULL UNIQUE,
    validator_hash VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME NULL,
    CONSTRAINT fk_auth_tokens_user FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Akun demo (password sudah di-hash dengan password_hash / bcrypt)
INSERT INTO users (name, email, password, role, phone, address, created_at, updated_at) VALUES
('Admin ShopEase', 'admin@shopease.test', '$2y$12$8o9GHfeblQ8u2RcKC8RrXe/BhY3uZZz4qbVqzRU0mpIeKhvXyWazK', 'admin', '081100000001', 'Kantor ShopEase, Surabaya', NOW(), NOW()),
('Operator Gudang', 'operator@shopease.test', '$2y$12$ohCyvPeFo.uiN2BPBBilgOS3n4uMLWZaMX6Amy9jgt7pCB5l3ioGG', 'operator', '081100000002', 'Gudang ShopEase, Sidoarjo', NOW(), NOW()),
('Agil Lukman', 'agil@shopease.test', '$2y$12$WiJNXYjhAu3xrVzwrhPR4OWaU6wqyO0axZV/nrHuB/kAocoi/auwW', 'customer', '081234567890', 'Jl. Keputih No. 1, Sukolilo, Surabaya', NOW(), NOW()),
('Siti Rahma', 'siti@shopease.test', '$2y$12$/kb9FXaEOxM.ptzpXQIRNuPPwuQQ5rUJHjbBi61pGP2wmpJzsTpCC', 'customer', '081234567891', 'Jl. Ijen No. 5, Malang', NOW(), NOW());
