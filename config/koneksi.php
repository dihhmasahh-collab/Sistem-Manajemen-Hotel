<?php
// Konfigurasi Database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_hotel";

// Koneksi ke database
$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8mb4");

// Fungsi helper untuk format rupiah
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Fungsi helper untuk format tanggal
function formatTanggal($tanggal) {
    return date('d/m/Y', strtotime($tanggal));
}
?>
