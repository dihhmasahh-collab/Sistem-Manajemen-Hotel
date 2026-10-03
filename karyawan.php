<?php include 'header.php'; ?>

<?php
// Proses Tambah
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_karyawan']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    $query = mysqli_query($conn, "INSERT INTO karyawan (nama_karyawan, jabatan, no_telepon, alamat) VALUES ('$nama', '$jabatan', '$telepon', '$alamat')");
    if ($query) {
        echo "<div class='alert alert-success'>Data karyawan berhasil ditambahkan!</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan data: " . mysqli_error($conn) . "</div>";
    }
}

// Proses Edit
if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id_karyawan']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_karyawan']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    $query = mysqli_query($conn, "UPDATE karyawan SET nama_karyawan='$nama', jabatan='$jabatan', no_telepon='$telepon', alamat='$alamat' WHERE id_karyawan='$id'");
    if ($query) {
        echo "<div class='alert alert-success'>Data karyawan berhasil diupdate!</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal mengupdate data: " . mysqli_error($conn) . "</div>";
    }
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    $query = mysqli_query($conn, "DELETE FROM karyawan WHERE id_karyawan='$id'");
    if ($query) {
        echo "<div class='alert alert-success'>Data karyawan berhasil dihapus!</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menghapus data: " . mysqli_error($conn) . "</div>";
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $edit_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM karyawan WHERE id_karyawan='$id'"));
}
?>

<div class="row">
    <!-- Form Tambah/Edit -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle"></i> <?php echo $edit_data ? 'Edit' : 'Tambah'; ?> Data Karyawan
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="id_karyawan" value="<?php echo $edit_data['id_karyawan']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Karyawan</label>
                        <input type="text" class="form-control" name="nama_karyawan" 
                            value="<?php echo $edit_data ? $edit_data['nama_karyawan'] : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <select class="form-select" name="jabatan" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="Resepsionis" <?php echo (isset($edit_data) && $edit_data['jabatan']=='Resepsionis') ? 'selected' : ''; ?>>Resepsionis</option>
                            <option value="Manager" <?php echo (isset($edit_data) && $edit_data['jabatan']=='Manager') ? 'selected' : ''; ?>>Manager</option>
                            <option value="Admin" <?php echo (isset($edit_data) && $edit_data['jabatan']=='Admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="Housekeeping" <?php echo (isset($edit_data) && $edit_data['jabatan']=='Housekeeping') ? 'selected' : ''; ?>>Housekeeping</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" name="no_telepon" 
                            value="<?php echo $edit_data ? $edit_data['no_telepon'] : ''; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" rows="2"><?php echo $edit_data ? $edit_data['alamat'] : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" name="<?php echo $edit_data ? 'edit' : 'tambah'; ?>" class="btn btn-primary w-100">
                        <i class="bi bi-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                    </button>
                    
                    <?php if ($edit_data): ?>
                        <a href="karyawan.php" class="btn btn-secondary w-100 mt-2">Batal</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Tabel Data -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-table"></i> Daftar Karyawan</span>
                <span class="badge bg-primary"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM karyawan")); ?> Karyawan</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM karyawan ORDER BY id_karyawan DESC");
                            while ($row = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['nama_karyawan']); ?></strong></td>
                                <td><span class="badge bg-info"><?php echo $row['jabatan']; ?></span></td>
                                <td><?php echo $row['no_telepon']; ?></td>
                                <td><?php echo $row['alamat']; ?></td>
                                <td>
                                    <a href="?edit=<?php echo $row['id_karyawan']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?hapus=<?php echo $row['id_karyawan']; ?>" class="btn btn-sm btn-danger" 
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
