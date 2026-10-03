-- Database untuk Sistem Informasi Manajemen Hotel
CREATE DATABASE IF NOT EXISTS db_hotel;
USE db_hotel;

-- Tabel Kamar
CREATE TABLE IF NOT EXISTS kamar (
    id_kamar INT AUTO_INCREMENT PRIMARY KEY,
    nomor_kamar VARCHAR(10) NOT NULL UNIQUE,
    tipe_kamar ENUM('Standard', 'Deluxe', 'Suite', 'VIP') NOT NULL,
    harga_per_malam DECIMAL(10,2) NOT NULL,
    status ENUM('Tersedia', 'Terisi', 'Perbaikan') DEFAULT 'Tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Tamu
CREATE TABLE IF NOT EXISTS tamu (
    id_tamu INT AUTO_INCREMENT PRIMARY KEY,
    nama_tamu VARCHAR(100) NOT NULL,
    no_ktp VARCHAR(20) NOT NULL UNIQUE,
    alamat TEXT NOT NULL,
    no_telepon VARCHAR(15) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Karyawan
CREATE TABLE IF NOT EXISTS karyawan (
    id_karyawan INT AUTO_INCREMENT PRIMARY KEY,
    nama_karyawan VARCHAR(100) NOT NULL,
    jabatan VARCHAR(50) NOT NULL,
    no_telepon VARCHAR(15),
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Metode Pembayaran
CREATE TABLE IF NOT EXISTS metode_pembayaran (
    id_metode INT AUTO_INCREMENT PRIMARY KEY,
    nama_metode VARCHAR(50) NOT NULL,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Transaksi
CREATE TABLE IF NOT EXISTS transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_tamu INT NOT NULL,
    id_kamar INT NOT NULL,
    id_karyawan INT NOT NULL,
    id_metode INT,
    tanggal_checkin DATE NOT NULL,
    tanggal_checkout DATE,
    total_bayar DECIMAL(10,2) DEFAULT 0,
    status ENUM('Check-In', 'Check-Out') DEFAULT 'Check-In',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tamu) REFERENCES tamu(id_tamu),
    FOREIGN KEY (id_kamar) REFERENCES kamar(id_kamar),
    FOREIGN KEY (id_karyawan) REFERENCES karyawan(id_karyawan),
    FOREIGN KEY (id_metode) REFERENCES metode_pembayaran(id_metode)
);

-- Data Dummy
INSERT INTO kamar (nomor_kamar, tipe_kamar, harga_per_malam, status) VALUES
('101', 'Standard', 350000, 'Tersedia'),
('102', 'Standard', 350000, 'Tersedia'),
('201', 'Deluxe', 550000, 'Tersedia'),
('202', 'Deluxe', 550000, 'Terisi'),
('301', 'Suite', 850000, 'Tersedia'),
('302', 'VIP', 1200000, 'Tersedia');

INSERT INTO tamu (nama_tamu, no_ktp, alamat, no_telepon, email) VALUES
('Budi Santoso', '3201010101010001', 'Jl. Merdeka No. 10, Jakarta', '081234567890', 'budi@email.com'),
('Siti Rahayu', '3201010101010002', 'Jl. Sudirman No. 25, Bandung', '082345678901', 'siti@email.com'),
('Ahmad Hidayat', '3201010101010003', 'Jl. Gatot Subroto No. 5, Surabaya', '083456789012', 'ahmad@email.com');

INSERT INTO karyawan (nama_karyawan, jabatan, no_telepon, alamat) VALUES
('Andi Wijaya', 'Resepsionis', '081111111111', 'Jl. Pahlawan No. 1'),
('Dewi Lestari', 'Resepsionis', '082222222222', 'Jl. Kenanga No. 5'),
('Rudi Hermawan', 'Manager', '083333333333', 'Jl. Melati No. 10');

INSERT INTO metode_pembayaran (nama_metode, keterangan) VALUES
('Tunai', 'Pembayaran dengan uang tunai'),
('Transfer Bank', 'Pembayaran melalui transfer bank'),
('Kartu Kredit', 'Pembayaran dengan kartu kredit'),
('E-Wallet', 'Pembayaran dengan dompet digital');
