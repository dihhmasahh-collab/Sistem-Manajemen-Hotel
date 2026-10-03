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

<!-- Summary Cards -->
<?php
$summary = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
    COUNT(*) as total_transaksi,
    COALESCE(SUM(total_bayar), 0) as total_pendapatan
    FROM transaksi 
    WHERE status = 'Check-Out' 
    AND tanggal_checkout BETWEEN '$tgl_dari' AND '$tgl_sampai'"));

$per_metode = mysqli_query($conn, "SELECT mp.nama_metode, COUNT(t.id_transaksi) as jumlah, COALESCE(SUM(t.total_bayar), 0) as total
    FROM transaksi t 
    LEFT JOIN metode_pembayaran mp ON t.id_metode = mp.id_metode
    WHERE t.status = 'Check-Out' 
    AND t.tanggal_checkout BETWEEN '$tgl_dari' AND '$tgl_sampai'
    GROUP BY mp.nama_metode");
?>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h3><?php echo $summary['total_transaksi']; ?></h3>
            <p>Total Transaksi Selesai</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <h3><?php echo formatRupiah($summary['total_pendapatan']); ?></h3>
            <p>Total Pendapatan</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <h3><?php echo $summary['total_transaksi'] > 0 ? formatRupiah($summary['total_pendapatan'] / $summary['total_transaksi']) : formatRupiah(0); ?></h3>
            <p>Rata-rata per Transaksi</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Detail Transaksi -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-graph-up"></i> Detail Pendapatan</span>
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
                                <th>Check-Out</th>
                                <th>Metode</th>
                                <th>Total Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $grand_total = 0;
                            $query = mysqli_query($conn, "SELECT t.*, tm.nama_tamu, k.nomor_kamar, mp.nama_metode
                                FROM transaksi t 
                                JOIN tamu tm ON t.id_tamu = tm.id_tamu 
                                JOIN kamar k ON t.id_kamar = k.id_kamar 
                                LEFT JOIN metode_pembayaran mp ON t.id_metode = mp.id_metode
                                WHERE t.status = 'Check-Out' 
                                AND t.tanggal_checkout BETWEEN '$tgl_dari' AND '$tgl_sampai'
                                ORDER BY t.tanggal_checkout DESC");
                            while ($row = mysqli_fetch_assoc($query)):
                                $grand_total += $row['total_bayar'];
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['nama_tamu']); ?></td>
                                <td><?php echo $row['nomor_kamar']; ?></td>
                                <td><?php echo formatTanggal($row['tanggal_checkout']); ?></td>
                                <td><span class="badge bg-info"><?php echo $row['nama_metode'] ?? '-'; ?></span></td>
                                <td><strong><?php echo formatRupiah($row['total_bayar']); ?></strong></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <th colspan="5" class="text-end">Grand Total:</th>
                                <th><?php echo formatRupiah($grand_total); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pendapatan per Metode -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pie-chart"></i> Pendapatan per Metode Pembayaran
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Metode</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($per_metode)): ?>
                        <tr>
                            <td><?php echo $row['nama_metode'] ?? 'Tidak diketahui'; ?></td>
                            <td class="text-center"><?php echo $row['jumlah']; ?></td>
                            <td class="text-end"><?php echo formatRupiah($row['total']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
