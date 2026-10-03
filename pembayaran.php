<?php include 'header.php'; ?>

<?php
// Proses Tambah
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_metode']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    $query = mysqli_query($conn, "INSERT INTO metode_pembayaran (nama_metode, keterangan) VALUES ('$nama', '$keterangan')");
    if ($query) {
        echo "<div class='alert alert-success'>Metode pembayaran berhasil ditambahkan!</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan data: " . mysqli_error($conn) . "</div>";
    }
}

// Proses Edit
if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id_metode']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_metode']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    $query = mysqli_query($conn, "UPDATE metode_pembayaran SET nama_metode='$nama', keterangan='$keterangan' WHERE id_metode='$id'");
    if ($query) {
        echo "<div class='alert alert-success'>Metode pembayaran berhasil diupdate!</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal mengupdate data: " . mysqli_error($conn) . "</div>";
    }
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    $query = mysqli_query($conn, "DELETE FROM metode_pembayaran WHERE id_metode='$id'");
    if ($query) {
        echo "<div class='alert alert-success'>Metode pembayaran berhasil dihapus!</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menghapus data: " . mysqli_error($conn) . "</div>";
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $edit_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM metode_pembayaran WHERE id_metode='$id'"));
}
?>

<div class="row">
    <!-- Form Tambah/Edit -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle"></i> <?php echo $edit_data ? 'Edit' : 'Tambah'; ?> Metode Pembayaran
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="id_metode" value="<?php echo $edit_data['id_metode']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Metode</label>
                        <input type="text" class="form-control" name="nama_metode" 
                            value="<?php echo $edit_data ? $edit_data['nama_metode'] : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3"><?php echo $edit_data ? $edit_data['keterangan'] : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" name="<?php echo $edit_data ? 'edit' : 'tambah'; ?>" class="btn btn-primary w-100">
                        <i class="bi bi-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                    </button>
                    
                    <?php if ($edit_data): ?>
                        <a href="pembayaran.php" class="btn btn-secondary w-100 mt-2">Batal</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Tabel Data -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-table"></i> Daftar Metode Pembayaran</span>
                <span class="badge bg-primary"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM metode_pembayaran")); ?> Metode</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Metode</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM metode_pembayaran ORDER BY id_metode");
                            while ($row = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['nama_metode']); ?></strong></td>
                                <td><?php echo $row['keterangan']; ?></td>
                                <td>
                                    <a href="?edit=<?php echo $row['id_metode']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?hapus=<?php echo $row['id_metode']; ?>" class="btn btn-sm btn-danger" 
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
