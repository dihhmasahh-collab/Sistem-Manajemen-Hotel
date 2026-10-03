<?php include 'header.php'; ?>

<?php
// Proses Tambah
if (isset($_POST['tambah'])) {
    $nomor = mysqli_real_escape_string($conn, $_POST['nomor_kamar']);
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe_kamar']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga_per_malam']);
    
    $query = mysqli_query($conn, "INSERT INTO kamar (nomor_kamar, tipe_kamar, harga_per_malam) VALUES ('$nomor', '$tipe', '$harga')");
    if ($query) {
        echo "<div class='alert alert-success'>Data kamar berhasil ditambahkan!</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan data: " . mysqli_error($conn) . "</div>";
    }
}

// Proses Edit
if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id_kamar']);
    $nomor = mysqli_real_escape_string($conn, $_POST['nomor_kamar']);
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe_kamar']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga_per_malam']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $query = mysqli_query($conn, "UPDATE kamar SET nomor_kamar='$nomor', tipe_kamar='$tipe', harga_per_malam='$harga', status='$status' WHERE id_kamar='$id'");
    if ($query) {
        echo "<div class='alert alert-success'>Data kamar berhasil diupdate!</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal mengupdate data: " . mysqli_error($conn) . "</div>";
    }
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    $query = mysqli_query($conn, "DELETE FROM kamar WHERE id_kamar='$id'");
    if ($query) {
        echo "<div class='alert alert-success'>Data kamar berhasil dihapus!</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menghapus data: " . mysqli_error($conn) . "</div>";
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $edit_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kamar WHERE id_kamar='$id'"));
}
?>

<div class="row">
    <!-- Form Tambah/Edit -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle"></i> <?php echo $edit_data ? 'Edit' : 'Tambah'; ?> Data Kamar
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="id_kamar" value="<?php echo $edit_data['id_kamar']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Nomor Kamar</label>
                        <input type="text" class="form-control" name="nomor_kamar" 
                            value="<?php echo $edit_data ? $edit_data['nomor_kamar'] : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipe Kamar</label>
                        <select class="form-select" name="tipe_kamar" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="Standard" <?php echo (isset($edit_data) && $edit_data['tipe_kamar']=='Standard') ? 'selected' : ''; ?>>Standard</option>
                            <option value="Deluxe" <?php echo (isset($edit_data) && $edit_data['tipe_kamar']=='Deluxe') ? 'selected' : ''; ?>>Deluxe</option>
                            <option value="Suite" <?php echo (isset($edit_data) && $edit_data['tipe_kamar']=='Suite') ? 'selected' : ''; ?>>Suite</option>
                            <option value="VIP" <?php echo (isset($edit_data) && $edit_data['tipe_kamar']=='VIP') ? 'selected' : ''; ?>>VIP</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Harga Per Malam</label>
                        <input type="number" class="form-control" name="harga_per_malam" 
                            value="<?php echo $edit_data ? $edit_data['harga_per_malam'] : ''; ?>" required>
                    </div>
                    
                    <?php if ($edit_data): ?>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="Tersedia" <?php echo $edit_data['status']=='Tersedia' ? 'selected' : ''; ?>>Tersedia</option>
                            <option value="Terisi" <?php echo $edit_data['status']=='Terisi' ? 'selected' : ''; ?>>Terisi</option>
                            <option value="Perbaikan" <?php echo $edit_data['status']=='Perbaikan' ? 'selected' : ''; ?>>Perbaikan</option>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <button type="submit" name="<?php echo $edit_data ? 'edit' : 'tambah'; ?>" class="btn btn-primary w-100">
                        <i class="bi bi-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                    </button>
                    
                    <?php if ($edit_data): ?>
                        <a href="kamar.php" class="btn btn-secondary w-100 mt-2">Batal</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Tabel Data -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-table"></i> Daftar Kamar</span>
                <span class="badge bg-primary"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kamar")); ?> Kamar</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nomor Kamar</th>
                                <th>Tipe</th>
                                <th>Harga/Malam</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM kamar ORDER BY nomor_kamar");
                            while ($row = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><strong><?php echo $row['nomor_kamar']; ?></strong></td>
                                <td><?php echo $row['tipe_kamar']; ?></td>
                                <td><?php echo formatRupiah($row['harga_per_malam']); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Tersedia'): ?>
                                        <span class="badge bg-success">Tersedia</span>
                                    <?php elseif ($row['status'] == 'Terisi'): ?>
                                        <span class="badge bg-danger">Terisi</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Perbaikan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="?edit=<?php echo $row['id_kamar']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?hapus=<?php echo $row['id_kamar']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Yakin hapus data ini?')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
