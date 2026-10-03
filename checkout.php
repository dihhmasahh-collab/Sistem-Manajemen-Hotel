<?php include 'header.php'; ?>

<?php
// Proses Check-Out
if (isset($_POST['checkout'])) {
    $id_transaksi = mysqli_real_escape_string($conn, $_POST['id_transaksi']);
    $id_metode = mysqli_real_escape_string($conn, $_POST['id_metode']);
    $tanggal_checkout = mysqli_real_escape_string($conn, $_POST['tanggal_checkout']);
    $total_bayar = mysqli_real_escape_string($conn, $_POST['total_bayar']);
    
    // Ambil id_kamar dari transaksi
    $trans = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_kamar FROM transaksi WHERE id_transaksi='$id_transaksi'"));
    
    // Update transaksi
    $query = mysqli_query($conn, "UPDATE transaksi SET tanggal_checkout='$tanggal_checkout', id_metode='$id_metode', total_bayar='$total_bayar', status='Check-Out' WHERE id_transaksi='$id_transaksi'");
    
    // Update status kamar menjadi tersedia
    mysqli_query($conn, "UPDATE kamar SET status='Tersedia' WHERE id_kamar='" . $trans['id_kamar'] . "'");
    
    if ($query) {
        echo "<div class='alert alert-success'>Check-Out berhasil! Total pembayaran: " . formatRupiah($total_bayar) . "</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal melakukan Check-Out: " . mysqli_error($conn) . "</div>";
    }
}

// Ambil data tamu yang sedang check-in
$query_checkin = mysqli_query($conn, "SELECT t.*, tm.nama_tamu, tm.no_ktp, k.nomor_kamar, k.tipe_kamar, k.harga_per_malam 
    FROM transaksi t 
    JOIN tamu tm ON t.id_tamu = tm.id_tamu 
    JOIN kamar k ON t.id_kamar = k.id_kamar 
    WHERE t.status = 'Check-In' 
    ORDER BY t.tanggal_checkin ASC");

// Ambil data metode pembayaran
$metode = mysqli_query($conn, "SELECT * FROM metode_pembayaran ORDER BY nama_metode");
?>

<div class="row">
    <!-- Form Check-Out -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-box-arrow-right"></i> Form Check-Out
            </div>
            <div class="card-body">
                <form method="POST" id="form_checkout">
                    <input type="hidden" name="id_transaksi" id="id_transaksi">
                    <input type="hidden" name="total_bayar" id="total_bayar_hidden">
                    
                    <div class="mb-3">
                        <label class="form-label">Pilih Tamu yang Check-Out</label>
                        <select class="form-select" id="pilih_tamu" required>
                            <option value="">-- Pilih Tamu --</option>
                            <?php while ($row = mysqli_fetch_assoc($query_checkin)): ?>
                                <option value="<?php echo $row['id_transaksi']; ?>" 
                                    data-tamu="<?php echo htmlspecialchars($row['nama_tamu']); ?>"
                                    data-ktp="<?php echo $row['no_ktp']; ?>"
                                    data-kamar="<?php echo $row['nomor_kamar']; ?>"
                                    data-tipe="<?php echo $row['tipe_kamar']; ?>"
                                    data-harga="<?php echo $row['harga_per_malam']; ?>"
                                    data-checkin="<?php echo $row['tanggal_checkin']; ?>">
                                    <?php echo $row['nama_tamu']; ?> - Kamar <?php echo $row['nomor_kamar']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div id="detail_tamu" style="display:none;">
                        <div class="alert alert-light">
                            <h6>Detail Tamu</h6>
                            <table class="table table-sm mb-0">
                                <tr><td width="40%">Nama Tamu</td><td><strong id="detail_nama"></strong></td></tr>
                                <tr><td>No. KTP</td><td id="detail_ktp"></td></tr>
                                <tr><td>Kamar</td><td id="detail_kamar"></td></tr>
                                <tr><td>Harga/Malam</td><td id="detail_harga"></td></tr>
                                <tr><td>Tgl Check-In</td><td id="detail_checkin"></td></tr>
                                <tr><td>Jumlah Malam</td><td><strong id="detail_malam"></strong></td></tr>
                                <tr class="table-primary"><td>Total Bayar</td><td><strong id="detail_total" class="text-primary"></strong></td></tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Check-Out</label>
                        <input type="date" class="form-control" name="tanggal_checkout" id="tanggal_checkout" 
                            value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-select" name="id_metode" required>
                            <option value="">-- Pilih Metode --</option>
                            <?php while ($row = mysqli_fetch_assoc($metode)): ?>
                                <option value="<?php echo $row['id_metode']; ?>"><?php echo $row['nama_metode']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <button type="submit" name="checkout" class="btn btn-danger w-100" id="btn_checkout" disabled>
                        <i class="bi bi-box-arrow-right"></i> Proses Check-Out
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Riwayat Check-Out -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i> Riwayat Check-Out
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tamu</th>
                                <th>Kamar</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT t.*, tm.nama_tamu, k.nomor_kamar, mp.nama_metode
                                FROM transaksi t 
                                JOIN tamu tm ON t.id_tamu = tm.id_tamu 
                                JOIN kamar k ON t.id_kamar = k.id_kamar 
                                LEFT JOIN metode_pembayaran mp ON t.id_metode = mp.id_metode
                                WHERE t.status = 'Check-Out' 
                                ORDER BY t.tanggal_checkout DESC LIMIT 10");
                            while ($row = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['nama_tamu']); ?></td>
                                <td><?php echo $row['nomor_kamar']; ?></td>
                                <td><?php echo formatTanggal($row['tanggal_checkin']); ?></td>
                                <td><?php echo $row['tanggal_checkout'] ? formatTanggal($row['tanggal_checkout']) : '-'; ?></td>
                                <td><strong><?php echo formatRupiah($row['total_bayar']); ?></strong></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('pilih_tamu').addEventListener('change', function() {
    var selected = this.options[this.selectedIndex];
    var detailDiv = document.getElementById('detail_tamu');
    var btnCheckout = document.getElementById('btn_checkout');
    
    if (this.value) {
        var harga = parseInt(selected.getAttribute('data-harga'));
        var checkin = new Date(selected.getAttribute('data-checkin'));
        var checkout = new Date(document.getElementById('tanggal_checkout').value);
        var malam = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
        
        if (malam < 1) malam = 1;
        
        var total = harga * malam;
        
        document.getElementById('id_transaksi').value = this.value;
        document.getElementById('detail_nama').textContent = selected.getAttribute('data-tamu');
        document.getElementById('detail_ktp').textContent = selected.getAttribute('data-ktp');
        document.getElementById('detail_kamar').textContent = selected.getAttribute('data-kamar') + ' (' + selected.getAttribute('data-tipe') + ')';
        document.getElementById('detail_harga').textContent = 'Rp ' + harga.toLocaleString('id-ID');
        document.getElementById('detail_checkin').textContent = selected.getAttribute('data-checkin');
        document.getElementById('detail_malam').textContent = malam + ' malam';
        document.getElementById('detail_total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('total_bayar_hidden').value = total;
        
        detailDiv.style.display = 'block';
        btnCheckout.disabled = false;
    } else {
        detailDiv.style.display = 'none';
        btnCheckout.disabled = true;
    }
});

document.getElementById('tanggal_checkout').addEventListener('change', function() {
    document.getElementById('pilih_tamu').dispatchEvent(new Event('change'));
});
</script>

<?php include 'footer.php'; ?>
