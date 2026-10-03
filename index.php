<?php include 'header.php'; ?>

<?php
// Statistik
$total_kamar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kamar"))['total'];
$kamar_terisi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kamar WHERE status='Terisi'"))['total'];
$kamar_tersedia = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kamar WHERE status='Tersedia'"))['total'];
$total_tamu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tamu"))['total'];
$total_checkin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE status='Check-In'"))['total'];
$total_checkout = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE status='Check-Out'"))['total'];
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_bayar),0) as total FROM transaksi WHERE status='Check-Out'"))['total'];
?>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stat-icon"><i class="bi bi-door-open"></i></div>
            <h3><?php echo $total_kamar; ?></h3>
            <p>Total Kamar</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stat-icon"><i class="bi bi-person-check"></i></div>
            <h3><?php echo $kamar_terisi; ?></h3>
            <p>Kamar Terisi</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <h3><?php echo $kamar_tersedia; ?></h3>
            <p>Kamar Tersedia</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            <h3><?php echo formatRupiah($total_pendapatan); ?></h3>
            <p>Total Pendapatan</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Tamu Saat Ini -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people"></i> Tamu yang Sedang Menginap</span>
                <a href="checkin.php" class="btn btn-sm btn-primary">+ Check-In Baru</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tamu</th>
                                <th>Kamar</th>
                                <th>Check-In</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($conn, "SELECT t.*, tm.nama_tamu, k.nomor_kamar, k.tipe_kamar 
                                FROM transaksi t 
                                JOIN tamu tm ON t.id_tamu = tm.id_tamu 
                                JOIN kamar k ON t.id_kamar = k.id_kamar 
                                WHERE t.status = 'Check-In' 
                                ORDER BY t.tanggal_checkin DESC LIMIT 5");
                            while ($row = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nama_tamu']); ?></td>
                                <td><?php echo $row['nomor_kamar']; ?> (<?php echo $row['tipe_kamar']; ?>)</td>
                                <td><?php echo formatTanggal($row['tanggal_checkin']); ?></td>
                                <td><span class="badge bg-success">Menginap</span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Info Panel -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-info-circle"></i> Ringkasan</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total Tamu</span>
                        <strong><?php echo $total_tamu; ?></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Aktif Check-In</span>
                        <strong><?php echo $total_checkin; ?></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total Check-Out</span>
                        <strong><?php echo $total_checkout; ?></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Tingkat Hunian</span>
                        <strong><?php echo $total_kamar > 0 ? round(($kamar_terisi/$total_kamar)*100, 1) : 0; ?>%</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
