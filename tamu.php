<?php include 'header.php'; ?>

<?php
// Proses Tambah
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_tamu']);
    $ktp = mysqli_real_escape_string($conn, $_POST['no_ktp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $query = mysqli_query($conn, "INSERT INTO tamu (nama_tamu, no_ktp, alamat, no_telepon, email) VALUES ('$nama', '$ktp', '$alamat', '$telepon', '$email')");
    if ($query) {
        echo "<div class='alert alert-success'>Data tamu berhasil ditambahkan!</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan data: " . mysqli_error($conn) . "</div>";
    }
}

// Proses Edit
if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id_tamu']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_tamu']);
    $ktp = mysqli_real_escape_string($conn, $_POST['no_ktp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $query = mysqli_query($conn, "UPDATE tamu SET nama_tamu='$nama', no_ktp='$ktp', alamat='$alamat', no_telepon='$telepon', email='$email' WHERE id_tamu='$id'");
    if ($query) {
        echo "<div class='alert alert-success'>Data tamu berhasil diupdate!</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal mengupdate data: " . mysqli_error($conn) . "</div>";
    }
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    $query = mysqli_query($conn, "DELETE FROM tamu WHERE id_tamu='$id'");
    if ($query) {
        echo "<div class='alert alert-success'>Data tamu berhasil dihapus!</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menghapus data: " . mysqli_error($conn) . "</div>";
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $edit_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tamu WHERE id_tamu='$id'"));
}
?>

<div class="row">
    <!-- Form Tambah/Edit -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle"></i> <?php echo $edit_data ? 'Edit' : 'Tambah'; ?> Data Tamu
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="id_tamu" value="<?php echo $edit_data['id_tamu']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Tamu</label>
                        <input type="text" class="form-control" name="nama_tamu" 
                            value="<?php echo $edit_data ? $edit_data['nama_tamu'] : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">No. KTP</label>
                        <input type="text" class="form-control" name="no_ktp" maxlength="16"
                            value="<?php echo $edit_data ? $edit_data['no_ktp'] : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" rows="2" required><?php echo $edit_data ? $edit_data['alamat'] : ''; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" name="no_telepon" 
                            value="<?php echo $edit_data ? $edit_data['no_telepon'] : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" 
                            value="<?php echo $edit_data ? $edit_data['email'] : ''; ?>">
                    </div>
                    
                    <button type="submit" name="<?php echo $edit_data ? 'edit' : 'tambah'; ?>" class="btn btn-primary w-100">
                        <i class="bi bi-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                    </button>
                    
                    <?php if ($edit_data): ?>
                        <a href="tamu.php" class="btn btn-secondary w-100 mt-2">Batal</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Tabel Data -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-table"></i> Daftar Tamu</span>
                <span class="badge bg-primary"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tamu")); ?> Tamu</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>No. KTP</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM tamu ORDER BY id_tamu DESC");
                            while ($row = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['nama_tamu']); ?></strong></td>
                                <td><?php echo $row['no_ktp']; ?></td>
                                <td><?php echo $row['no_telepon']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <a href="?edit=<?php echo $row['id_tamu']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?hapus=<?php echo $row['id_tamu']; ?>" class="btn btn-sm btn-danger" 
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
