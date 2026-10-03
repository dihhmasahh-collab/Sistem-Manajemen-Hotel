<?php
session_start();
require_once 'config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Manajemen Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #fff;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-header h5 {
            margin: 0;
            font-size: 1.1rem;
        }
        .sidebar-header small {
            opacity: 0.7;
        }
        .nav-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }
        .nav-menu li {
            margin: 2px 0;
        }
        .nav-menu .menu-header {
            padding: 10px 20px 5px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.5;
            margin-top: 10px;
        }
        .nav-menu a {
            display: block;
            padding: 10px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        .nav-menu a:hover,
        .nav-menu a.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
            border-left: 3px solid #ffc107;
        }
        .nav-menu a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 0;
            min-height: 100vh;
        }
        .top-navbar {
            background: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content-area {
            padding: 30px;
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 10px;
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
            font-weight: 600;
        }
        .btn-primary {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border: none;
        }
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .stat-card .stat-icon {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 3rem;
            opacity: 0.3;
        }
        .stat-card h3 {
            margin: 0;
            font-size: 2rem;
        }
        .stat-card p {
            margin: 5px 0 0;
            opacity: 0.9;
        }
        .table th {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            color: #666;
        }
        .badge-available {
            background-color: #28a745;
        }
        .badge-occupied {
            background-color: #dc3545;
        }
        .badge-maintenance {
            background-color: #ffc107;
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h5><i class="bi bi-building"></i> Hotel Management</h5>
            <small>Sistem Informasi</small>
        </div>
        <ul class="nav-menu">
            <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            
            <li class="menu-header">Master Data</li>
            <li><a href="kamar.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'kamar.php' ? 'active' : ''; ?>"><i class="bi bi-door-open"></i> Data Kamar</a></li>
            <li><a href="tamu.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'tamu.php' ? 'active' : ''; ?>"><i class="bi bi-people"></i> Data Tamu</a></li>
            <li><a href="karyawan.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'karyawan.php' ? 'active' : ''; ?>"><i class="bi bi-person-badge"></i> Data Karyawan</a></li>
            <li><a href="pembayaran.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'pembayaran.php' ? 'active' : ''; ?>"><i class="bi bi-credit-card"></i> Metode Pembayaran</a></li>
            
            <li class="menu-header">Transaksi</li>
            <li><a href="checkin.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'checkin.php' ? 'active' : ''; ?>"><i class="bi bi-box-arrow-in-right"></i> Check-In</a></li>
            <li><a href="checkout.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'checkout.php' ? 'active' : ''; ?>"><i class="bi bi-box-arrow-right"></i> Check-Out</a></li>
            
            <li class="menu-header">Laporan</li>
            <li><a href="laporan_hunian.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'laporan_hunian.php' ? 'active' : ''; ?>"><i class="bi bi-bar-chart"></i> Laporan Hunian</a></li>
            <li><a href="laporan_pendapatan.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'laporan_pendapatan.php' ? 'active' : ''; ?>"><i class="bi bi-graph-up"></i> Laporan Pendapatan</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <h5 class="mb-0">
                <?php
                $page = basename($_SERVER['PHP_SELF']);
                $titles = [
                    'index.php' => 'Dashboard',
                    'kamar.php' => 'Data Kamar',
                    'tamu.php' => 'Data Tamu',
                    'karyawan.php' => 'Data Karyawan',
                    'pembayaran.php' => 'Metode Pembayaran',
                    'checkin.php' => 'Check-In',
                    'checkout.php' => 'Check-Out',
                    'laporan_hunian.php' => 'Laporan Hunian Kamar',
                    'laporan_pendapatan.php' => 'Laporan Pendapatan'
                ];
                echo isset($titles[$page]) ? $titles[$page] : 'Dashboard';
                ?>
            </h5>
            <span class="text-muted"><i class="bi bi-calendar"></i> <?php echo date('d F Y'); ?></span>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
