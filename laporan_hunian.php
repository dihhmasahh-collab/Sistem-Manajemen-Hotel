<?php include 'header.php'; ?>

<?php
// Filter tanggal
$tgl_dari = isset($_GET['dari']) ? $_GET['dari'] : date('Y-m-01');
$tgl_sampai = isset($_GET['sampai']) ? $_GET['sampai'] : date('Y-m-d');
?>

<div class="card mb-4">
    <div class="card-header">
        <i class="bi bi-filter"></i> Filter Laporan
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" class="form-control" name="dari" value="<?php echo $tgl_dari; ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" class="form-control" name="sampai" value="<?php echo $tgl_sampai; ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-success w-100" onclick="window.print()">
                    <i class="bi bi-printer"></i> Cetak
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bar-chart"></i> Laporan Hunian Kamar</span>
        <span class="text-muted">Periode: <?php echo formatTanggal($tgl_dari); ?> - <?php echo formatTanggal($tgl_sampai); ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tamu</th>
                        <th>Kamar</th>
                        <th>Tipe</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Durasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = mysqli_query($conn, "SELECT t.*, tm.nama_tamu, k.nomor_kamar, k.tipe_kamar 
                        FROM transaksi t 
                        JOIN tamu tm ON t.id_tamu = tm.id_tamu 
                        JOIN kamar k ON t.id_kamar = k.id_kamar 
                        WHERE t.tanggal_checkin BETWEEN '$tgl_dari' AND '$tgl_sampai'
                        ORDER BY t.tanggal_checkin DESC");
                    
                    $total_transaksi = 0;
                    while ($row = mysqli_fetch_assoc($query)):
                        $checkin = new DateTime($row['tanggal_checkin']);
                        $checkout = $row['tanggal_checkout'] ? new DateTime($row['tanggal_checkout']) : new DateTime();
                        $durasi = $checkin->diff($checkout)->days;
                        if ($durasi < 1) $durasi = 1;
                        $total_transaksi++;
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nama_tamu']); ?></strong></td>
                        <td><?php echo $row['nomor_kamar']; ?></td>
                        <td><?php echo $row['tipe_kamar']; ?></td>
                        <td><?php echo formatTanggal($row['tanggal_checkin']); ?></td>
                        <td><?php echo $row['tanggal_checkout'] ? formatTanggal($row['tanggal_checkout']) : '-'; ?></td>
                        <td><?php echo $durasi; ?> malam</td>
                        <td>
                            <?php if ($row['status'] == 'Check-In'): ?>
                                <span class="badge bg-success">Menginap</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <strong>Total Transaksi: <?php echo $total_transaksi; ?></strong>
    </div>
</div>

<?php include 'footer.php'; ?>
