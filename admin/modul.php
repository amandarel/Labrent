<?php 
session_start();
if($_SESSION['role'] != 'admin') header("Location: ../login.php");
require '../config/database.php'; 
include '../includes/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-secondary"><i class="bi bi-folder-fill"></i> Manajemen Modul & Panduan</h4>
            </div>

            <!-- Pesan Notifikasi -->
            <?php if(isset($_GET['status'])): ?>
                <?php 
                    $status = $_GET['status'];
                    $alert_class = (strpos($status, 'sukses') !== false) ? 'success' : 'danger';
                    
                    $pesan = "Terjadi kesalahan sistem."; 
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
                            $sql = "SELECT nama_pengupload, COUNT(*) as total, MAX(tgl_upload) as tgl_terakhir 
                                    FROM modul 
                                    GROUP BY nama_pengupload 
                                    ORDER BY tgl_terakhir DESC";
                            $stmt = $pdo->query($sql);
                            $groups = $stmt->fetchAll();
                            
                            if ($groups):
                                $no = 1;
                                foreach($groups as $row):
                                    $stmt_det = $pdo->prepare("SELECT * FROM modul WHERE nama_pengupload = ?");
                                    $stmt_det->execute([$row['nama_pengupload']]);
                                    $det_json = json_encode($stmt_det->fetchAll(PDO::FETCH_ASSOC));
                            ?>
                            <tr class="text-center align-middle">
                                <td><?= $no++; ?></td>
                                <td class="text-start fw-bold"><?= htmlspecialchars($row['nama_pengupload']); ?></td>
                                <td><span class="badge bg-dark"><?= $row['total']; ?> Dokumen</span></td>
                                <td><?= date('d M Y, H:i', strtotime($row['tgl_terakhir'])); ?></td>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dokumen dari: <span id="namaUploader" class="text-primary fw-bold"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0 text-center small">
                        <thead class="table-dark">
                            <tr>
                                <th>Judul Modul</th>
                                <th>Nama Kelas</th>
                                <th>Matakuliah</th>
                                <th>Kategori</th>
                                <th>Ukuran</th>
                                <th class="sticky-col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="isiDetailModul"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showDetailModul(data, nama) {
    document.getElementById('namaUploader').innerText = nama;
    const list = document.getElementById('isiDetailModul');
    list.innerHTML = "";

    data.forEach(item => {
        const tglTanpaDetik = item.tgl_upload.substring(0, 16);

        const row = `
            <tr class="align-middle">
                <td class="text-start px-3" style="min-width: 150px;">
                    <strong>${item.judul}</strong><br>
                    <small class="text-muted" style="font-size: 0.7rem;">${tglTanpaDetik}</small>
                </td>
                <td style="min-width: 100px;">${item.nama_kelas ? item.nama_kelas : '-'}</td>
                <td style="min-width: 120px;">${item.nama_matakuliah ? item.nama_matakuliah : '-'}</td>
                <td style="min-width: 100px;"><span class="badge bg-light text-dark border">${item.kategori}</span></td>
                <td>${item.ukuran} KB</td>
                <td class="sticky-col"> <!-- Tambah kelas sticky-col -->
                    <div class="btn-group">
                        <a href="../uploads/modul/${item.nama_file}" class="btn btn-sm btn-primary" download>
                            <i class="bi bi-download"></i>
                        </a>
                        <a href="../actions/proses_modul.php?hapus=${item.id}&file=${item.nama_file}" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Hapus file ini?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
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