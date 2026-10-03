<?php include 'header.php'; ?>

<?php
// Proses Check-In
if (isset($_POST['checkin'])) {
    $id_tamu = mysqli_real_escape_string($conn, $_POST['id_tamu']);
    $id_kamar = mysqli_real_escape_string($conn, $_POST['id_kamar']);
    $id_karyawan = mysqli_real_escape_string($conn, $_POST['id_karyawan']);
    $tanggal_checkin = mysqli_real_escape_string($conn, $_POST['tanggal_checkin']);
    
    // Cek apakah kamar sudah terisi
    $cek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM kamar WHERE id_kamar='$id_kamar'"));
    if ($cek['status'] == 'Terisi') {
        echo "<div class='alert alert-danger'>Kamar sudah terisi! Pilih kamar lain.</div>";
    } else {
        // Insert transaksi
        $query = mysqli_query($conn, "INSERT INTO transaksi (id_tamu, id_kamar, id_karyawan, tanggal_checkin, status) 
            VALUES ('$id_tamu', '$id_kamar', '$id_karyawan', '$tanggal_checkin', 'Check-In')");
        
        // Update status kamar
        mysqli_query($conn, "UPDATE kamar SET status='Terisi' WHERE id_kamar='$id_kamar'");
        
        if ($query) {
            echo "<div class='alert alert-success'>Check-In berhasil dilakukan!</div>";
        } else {
            echo "<div class='alert alert-danger'>Gagal melakukan Check-In: " . mysqli_error($conn) . "</div>";
        }
    }
}

// Ambil data dropdown
$tamu = mysqli_query($conn, "SELECT * FROM tamu ORDER BY nama_tamu");
$kamar = mysqli_query($conn, "SELECT * FROM kamar WHERE status='Tersedia' ORDER BY nomor_kamar");
$karyawan = mysqli_query($conn, "SELECT * FROM karyawan ORDER BY nama_karyawan");
?>

<div class="row">
    <!-- Form Check-In -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-box-arrow-in-right"></i> Form Check-In
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tamu</label>
                        <select class="form-select" name="id_tamu" required>
                            <option value="">-- Pilih Tamu --</option>
                            <?php while ($row = mysqli_fetch_assoc($tamu)): ?>
                                <option value="<?php echo $row['id_tamu']; ?>">
                                    <?php echo $row['nama_tamu']; ?> - <?php echo $row['no_ktp']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <small class="text-muted">Belum ada tamu? <a href="tamu.php">Tambah Tamu</a></small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kamar</label>
                        <select class="form-select" name="id_kamar" id="id_kamar" required>
                            <option value="">-- Pilih Kamar --</option>
                            <?php while ($row = mysqli_fetch_assoc($kamar)): ?>
                                <option value="<?php echo $row['id_kamar']; ?>" data-harga="<?php echo $row['harga_per_malam']; ?>">
                                    <?php echo $row['nomor_kamar']; ?> - <?php echo $row['tipe_kamar']; ?> (<?php echo formatRupiah($row['harga_per_malam']); ?>/malam)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Check-In</label>
                        <input type="date" class="form-control" name="tanggal_checkin" id="tanggal_checkin" 
                            value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Check-Out (Estimasi)</label>
                        <input type="date" class="form-control" id="tanggal_checkout">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Estimasi Total Biaya</label>
                        <div class="alert alert-info mb-0" id="estimasi_biaya">
                            Pilih kamar dan tanggal untuk melihat estimasi
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Petugas (Karyawan)</label>
                        <select class="form-select" name="id_karyawan" required>
                            <option value="">-- Pilih Petugas --</option>
                            <?php while ($row = mysqli_fetch_assoc($karyawan)): ?>
                                <option value="<?php echo $row['id_karyawan']; ?>">
                                    <?php echo $row['nama_karyawan']; ?> (<?php echo $row['jabatan']; ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <button type="submit" name="checkin" class="btn btn-success w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Proses Check-In
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Daftar Tamu yang sedang Check-In -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-check"></i> Daftar Tamu Sedang Menginap
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tamu</th>
                                <th>Kamar</th>
                                <th>Tgl Check-In</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT t.*, tm.nama_tamu, k.nomor_kamar, k.tipe_kamar, ky.nama_karyawan 
                                FROM transaksi t 
                                JOIN tamu tm ON t.id_tamu = tm.id_tamu 
                                JOIN kamar k ON t.id_kamar = k.id_kamar 
                                JOIN karyawan ky ON t.id_karyawan = ky.id_karyawan 
                                WHERE t.status = 'Check-In' 
                                ORDER BY t.tanggal_checkin DESC");
                            while ($row = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['nama_tamu']); ?></strong></td>
                                <td><?php echo $row['nomor_kamar']; ?> (<?php echo $row['tipe_kamar']; ?>)</td>
                                <td><?php echo formatTanggal($row['tanggal_checkin']); ?></td>
                                <td><?php echo $row['nama_karyawan']; ?></td>
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
// Hitung estimasi biaya
function hitungEstimasi() {
    var kamarSelect = document.getElementById('id_kamar');
    var tglCheckin = document.getElementById('tanggal_checkin').value;
    var tglCheckout = document.getElementById('tanggal_checkout').value;
    var estimasiDiv = document.getElementById('estimasi_biaya');
    
    if (kamarSelect.value && tglCheckin && tglCheckout) {
        var harga = kamarSelect.options[kamarSelect.selectedIndex].getAttribute('data-harga');
        var checkin = new Date(tglCheckin);
        var checkout = new Date(tglCheckout);
        var malam = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
        
        if (malam > 0) {
            var total = harga * malam;
            estimasiDiv.innerHTML = '<strong>' + malam + ' malam x Rp ' + parseInt(harga).toLocaleString('id-ID') + ' = Rp ' + total.toLocaleString('id-ID') + '</strong>';
        } else {
            estimasiDiv.innerHTML = 'Tanggal check-out harus setelah tanggal check-in';
        }
    } else {
        estimasiDiv.innerHTML = 'Pilih kamar dan tanggal untuk melihat estimasi';
    }
}

document.getElementById('id_kamar').addEventListener('change', hitungEstimasi);
document.getElementById('tanggal_checkin').addEventListener('change', hitungEstimasi);
document.getElementById('tanggal_checkout').addEventListener('change', hitungEstimasi);
</script>

<?php include 'footer.php'; ?>
