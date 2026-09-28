CREATE DATABASE IF NOT EXISTS ikan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ikan_db;

CREATE TABLE IF NOT EXISTS ikan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL UNIQUE,
    jenis VARCHAR(60) NOT NULL,
    habitat VARCHAR(80) NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO ikan (nama, jenis, habitat, harga, stok) VALUES
('Ikan Koi', 'Cyprinus rubrofuscus', 'Air tawar', 150000.00, 12),
('Ikan Cupang', 'Betta splendens', 'Air tawar', 50000.00, 25),
('Ikan Gurami', 'Osphronemus goramy', 'Air tawar', 85000.00, 18),
('Ikan Kakap Merah', 'Lutjanus campechanus', 'Air laut', 120000.00, 10);
