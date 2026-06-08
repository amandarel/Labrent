<?php 
session_start();
if($_SESSION['role'] != 'admin') header("Location: login.php");
require 'config/database.php'; 
include 'header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-secondary"><i class="bi bi-folder-fill"></i> Manajemen Modul & Panduan</h4>
                <button class="btn btn-primary" style="background-color: var(--primary-blue);" data-bs-toggle="modal" data-bs-target="#modalUpload">
                    <i class="bi bi-cloud-arrow-up"></i> Upload File Baru
                </button>
            </div>

<!-- Pesan Notifikasi -->
<?php if(isset($_GET['status'])): ?>
    <?php 
        $status = $_GET['status'];
        // Tentukan warna (Success jika ada kata 'sukses', selain itu Danger)
        $alert_class = (strpos($status, 'sukses') !== false) ? 'success' : 'danger';
        
        // Tentukan pesan berdasarkan status
        $pesan = "Terjadi kesalahan sistem."; // Default
        if($status == 'sukses') $pesan = "File berhasil diunggah!";
        if($status == 'sukses_hapus') $pesan = "Modul berhasil dihapus!";
        if($status == 'error_format') $pesan = "Gagal: Format file tidak didukung (Gunakan PDF/DOCX).";
        if($status == 'error_size') $pesan = "Gagal: Ukuran file terlalu besar (Maks 5MB).";
    ?>
    <div class="alert alert-<?= $alert_class ?> alert-dismissible fade show">
        <?= $pesan ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

            <div class="card card-custom shadow-sm">
                <div class="card-header-blue">Daftar Pengupload Modul</div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="bg-light">
                            <tr class="text-center small">
                                <th width="50">No</th>
                                <th>Nama Pengupload</th>
                                <th>Jumlah File</th>
                                <th>Terakhir Upload</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query Grouping berdasarkan Nama Pengupload
                            $sql = "SELECT nama_pengupload, COUNT(*) as total, MAX(tgl_upload) as tgl_terakhir 
                                    FROM modul 
                                    GROUP BY nama_pengupload 
                                    ORDER BY tgl_terakhir DESC";
                            $stmt = $pdo->query($sql);
                            $groups = $stmt->fetchAll();
                            
                            if ($groups):
                                $no = 1;
                                foreach($groups as $row):
                                    // Ambil detail file untuk dikirim ke Modal (JSON)
                                    $stmt_det = $pdo->prepare("SELECT * FROM modul WHERE nama_pengupload = ?");
                                    $stmt_det->execute([$row['nama_pengupload']]);
                                    $det_json = json_encode($stmt_det->fetchAll(PDO::FETCH_ASSOC));
                            ?>
                            <tr class="text-center align-middle">
                                <td><?= $no++; ?></td>
                                <td class="text-start fw-bold"><?= htmlspecialchars($row['nama_pengupload']); ?></td>
                                <td><span class="badge bg-dark"><?= $row['total']; ?> Dokumen</span></td>
                                <td><?= date('d M Y', strtotime($row['tgl_terakhir'])); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info text-white" onclick='showDetailModul(<?= $det_json ?>, "<?= $row['nama_pengupload'] ?>")'>
                                        <i class="bi bi-list-ul"></i> Lihat File
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada modul yang diupload.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DETAIL FILE -->
<div class="modal fade" id="modalDetailModul" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dokumen dari: <span id="namaUploader" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-striped mb-0 text-center small">
                    <thead class="table-dark">
                        <tr>
                            <th>Judul Modul</th>
                            <th>Kategori</th>
                            <th>Ukuran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="isiDetailModul"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload (Sama dengan sebelumnya) -->
<div class="modal fade" id="modalUpload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="proses_modul.php" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Dokumen Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Judul Modul</label>
                    <input type="text" name="judul" class="form-control" required placeholder="Contoh: Panduan Kimia Dasar">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="Buku Panduan">Buku Panduan</option>
                        <option value="Modul Praktikum">Modul Praktikum</option>
                        <option value="SOP Lab">SOP Lab</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Pilih File (PDF / DOCX)</label>
                    <input type="file" name="berkas" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="upload" class="btn btn-primary w-100">Mulai Upload</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showDetailModul(data, nama) {
    document.getElementById('namaUploader').innerText = nama;
    const list = document.getElementById('isiDetailModul');
    list.innerHTML = "";

    data.forEach(item => {
        const row = `
            <tr class="align-middle">
                <td class="text-start px-3">
                    <strong>${item.judul}</strong><br>
                    <small class="text-muted">${item.tgl_upload}</small>
                </td>
                <td>${item.kategori}</td>
                <td>${item.ukuran} KB</td>
                <td>
                    <a href="uploads/modul/${item.nama_file}" class="btn btn-xs btn-outline-primary p-1" download><i class="bi bi-download"></i></a>
                    <a href="proses_modul.php?hapus=${item.id}&file=${item.nama_file}" class="btn btn-xs btn-outline-danger p-1" onclick="return confirm('Hapus file ini?')"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
        `;
        list.insertAdjacentHTML('beforeend', row);
    });

    new bootstrap.Modal(document.getElementById('modalDetailModul')).show();
}
</script>
</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>