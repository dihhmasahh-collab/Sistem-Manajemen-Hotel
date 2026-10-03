# Sistem Informasi Manajemen Hotel

Aplikasi web sederhana untuk mengelola operasional hotel: data kamar, data tamu,
data karyawan, proses check-in dan check-out, pembayaran, serta laporan.

Dibuat untuk **Praktikum Pemrograman Web (PWL)**.

| | |
|---|---|
| **Bahasa** | PHP Native (tanpa framework) |
| **Database** | MySQL (`mysqli`) |
| **Tampilan** | Bootstrap 5 + Bootstrap Icons |
| **Server lokal** | XAMPP (Apache + MySQL) |

---

## Fitur

- **Dashboard** — statistik total kamar, kamar terisi, kamar tersedia, total tamu, dan total pendapatan
- **Data Kamar** — CRUD kamar (nomor, tipe, harga per malam, status)
- **Data Tamu** — CRUD tamu (nama, no. KTP, alamat, no. telepon, email)
- **Data Karyawan** — CRUD karyawan (nama, jabatan, no. telepon, alamat)
- **Check-In** — mendaftarkan tamu ke kamar
- **Check-Out** — menyelesaikan transaksi dan menghitung total bayar
- **Pembayaran** — mencatat metode pembayaran transaksi
- **Laporan Hunian** — rekap tingkat hunian kamar
- **Laporan Pendapatan** — rekap pemasukan hotel

---

## Struktur Folder

```
pwl/
│
├── index.php                  # Dashboard + statistik
├── kamar.php                  # CRUD data kamar
├── tamu.php                   # CRUD data tamu
├── karyawan.php               # CRUD data karyawan
├── checkin.php                # Proses check-in
├── checkout.php               # Proses check-out
├── pembayaran.php             # Transaksi pembayaran
├── laporan_hunian.php         # Laporan tingkat hunian
├── laporan_pendapatan.php     # Laporan pendapatan
│
├── header.php                 # Navbar + pembuka halaman
├── footer.php                 # Penutup halaman
│
├── config/
│   └── koneksi.php            # Koneksi MySQL + fungsi helper
│
├── database.sql               # Struktur tabel + data contoh
├── .htaccess                  # Pengaturan keamanan folder
└── .gitignore
```

---

## Cara Menjalankan

### 1. Salin folder ke `htdocs`

```
C:\xampp\htdocs\pwl
```

### 2. Jalankan Apache dan MySQL

Buka **XAMPP Control Panel**, klik **Start** pada Apache dan MySQL.

### 3. Import database

1. Buka `http://localhost/phpmyadmin`
2. Klik menu **Import**
3. Pilih file `database.sql`
4. Klik **Go**

Database `db_hotel` akan otomatis dibuat beserta 5 tabelnya.

### 4. Cek koneksi

Buka `config/koneksi.php`. Settingan bawaan sudah sesuai XAMPP:

```php
$host     = "localhost";
$username = "root";
$password = "";
$database = "db_hotel";
```

Kalau password MySQL Anda tidak kosong, ubah baris `$password`.

### 5. Jalankan

```
http://localhost/pwl/
```

---

## Isi Database

Database `db_hotel` terdiri dari 5 tabel:

| Tabel | Isi |
|---|---|
| `kamar` | Nomor kamar, tipe (Standard/Deluxe/Suite/VIP), harga per malam, status |
| `tamu` | Nama, no. KTP, alamat, no. telepon, email |
| `karyawan` | Nama, jabatan, no. telepon, alamat |
| `metode_pembayaran` | Daftar metode pembayaran |
| `transaksi` | Data check-in, check-out, dan pembayaran |

> Semua data yang tersimpan adalah **data contoh/fiktif** untuk keperluan
> tugas kuliah, bukan data asli orang sungguhan.

---

## Catatan

- Project ini memakai Bootstrap dari **CDN** (`cdn.jsdelivr`), jadi halaman
  butuh koneksi internet agar tampilannya rapi. Tanpa internet, data tetap
  terbaca tetapi styling-nya hilang.
- File `.htaccess` berfungsi jika modul `mod_rewrite` aktif di Apache.