<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require '../config/database.php'; 
include '../includes/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-secondary">Manajemen Inventaris Alat & Bahan</h4>
                <button class="btn btn-primary" style="background-color: var(--primary-blue);" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
                    <i class="bi bi-plus-circle"></i> Tambah Barang Baru
                </button>
            </div>

            <?php if(isset($_GET['pesan'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Data berhasil diproses!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card card-custom">
                <div class="card-header-blue d-flex justify-content-between align-items-center">
                    <span>Data Manual yang Dicatat (Inventaris)</span>
                    <form action="" method="GET" class="d-flex">
                        <input type="text" name="cari" class="form-control form-control-sm me-2" placeholder="Cari nama alat...">
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-center bg-light">
                                <th width="50">No</th>
                                <th>Nama Alat/Bahan</th>
                                <th>Kategori</th>
                                <th>Kondisi</th>
                                <th>Merek</th>
                                <th>Jumlah</th>
                                <th>Ukuran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $search = isset($_GET['cari']) ? "%".$_GET['cari']."%" : "%";
                            $sql = "SELECT * FROM alat WHERE nama_alat LIKE ? ORDER BY id DESC";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$search]);
                            $data = $stmt->fetchAll();

                            if ($data):
                                $no = 1;
                                foreach($data as $row):
                                    
                                    if ($row['kondisi_alat'] == 'Baik') {
                                        $badge_class = 'bg-success';
                                    } elseif ($row['kondisi_alat'] == 'Retak') {
                                        $badge_class = 'bg-warning text-dark';
                                    } elseif ($row['kondisi_alat'] == 'Rusak') {
                                        $badge_class = 'bg-danger';
                                    } else {
                                        $badge_class = 'bg-secondary'; 
                                    }
                            ?>
                            <tr class="text-center align-middle">
                                <td><?= $no++; ?></td>
                                <td class="text-start"><?= htmlspecialchars($row['nama_alat']); ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($row['kategori'] ?? 'Alat'); ?></span></td>
                                <td>
                                    <span class="badge <?= $badge_class; ?>">
                                        <?= $row['kondisi_alat'] ? htmlspecialchars($row['kondisi_alat']) : '-'; ?>
                                    </span>
                                </td>
                                <td><?= $row['merek_alat'] ? htmlspecialchars($row['merek_alat']) : '-'; ?></td>
                                <td><?= $row['jumlah_alat'] ? htmlspecialchars($row['jumlah_alat']) : '0'; ?></td>
                                <td><?= $row['ukuran'] ? htmlspecialchars($row['ukuran']) : '-'; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning text-white" 
                                            onclick="editBarang(<?= htmlspecialchars(json_encode($row)); ?>)" 
                                            data-bs-toggle="modal" data-bs-target="#modalEditBarang">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
<form action="../actions/proses_inventaris.php" method="POST" style="display:inline;">
    <input type="hidden" name="aksi" value="hapus">
    <input type="hidden" name="id" value="<?= $row['id']; ?>">

    <button type="submit" class="btn btn-sm btn-danger"
        onclick="return confirm('Yakin hapus barang ini?')">
        <i class="bi bi-trash"></i>
    </button>
</form>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="8" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Barang -->
<div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="../actions/proses_inventaris.php" method="POST" class="modal-content">
            <input type="hidden" name="aksi" value="tambah">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Inventaris</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label>Nama Alat/Bahan <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control" required></div>
                <div class="mb-3">
                    <label>Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select" required>
                        <option value="Alat">Alat</option>
                        <option value="Bahan">Bahan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Kondisi Alat (Opsional)</label>
                    <select name="kondisi" class="form-select">
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="Baik">Baik</option>
                        <option value="Retak">Retak</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>
                <div class="mb-3"><label>Merek (Opsional)</label><input type="text" name="merek" class="form-control"></div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Jumlah (Opsional)</label><input type="number" name="jumlah" class="form-control"></div>
                    <div class="col-md-6 mb-3"><label>Ukuran (Opsional)</label><input type="text" name="ukuran" class="form-control" placeholder="misal: 100ml"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" style="background-color: var(--primary-blue);">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Barang -->
<div class="modal fade" id="modalEditBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="../actions/proses_inventaris.php" method="POST" class="modal-content">
            <input type="hidden" name="aksi" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-header border-0">
                <h5 class="modal-title">Edit Data Inventaris</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label>Nama Alat/Bahan <span class="text-danger">*</span></label><input type="text" name="nama" id="edit_nama" class="form-control" required></div>
                <div class="mb-3">
                    <label>Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" id="edit_kategori" class="form-select" required>
                        <option value="Alat">Alat</option>
                        <option value="Bahan">Bahan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Kondisi Alat (Opsional)</label>
                    <select name="kondisi" id="edit_kondisi" class="form-select">
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="Baik">Baik</option>
                        <option value="Retak">Retak</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>
                <div class="mb-3"><label>Merek (Opsional)</label><input type="text" name="merek" id="edit_merek" class="form-control"></div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Jumlah (Opsional)</label><input type="number" name="jumlah" id="edit_jumlah" class="form-control"></div>
                    <div class="col-md-6 mb-3"><label>Ukuran (Opsional)</label><input type="text" name="ukuran" id="edit_ukuran" class="form-control"></div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning text-white">Update Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editBarang(data) {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_nama').value = data.nama_alat;
        document.getElementById('edit_kondisi').value = data.kondisi_alat ? data.kondisi_alat : "";
        document.getElementById('edit_merek').value = data.merek_alat;
        document.getElementById('edit_jumlah').value = data.jumlah_alat;
        document.getElementById('edit_ukuran').value = data.ukuran;
        document.getElementById('edit_kategori').value = data.kategori;
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>