<?php 
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'mahasiswa') {
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
            <h4 class="text-secondary mb-4 text-start">Kirim Modul Praktikum</h4>
            
            <div class="card card-custom m-auto shadow-sm" style="max-width: 600px; border-radius: 10px;">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <img src="https://cdn-icons-png.flaticon.com/512/338/338910.png" width="60" class="mb-2">
                        <h5>Form Upload Dokumen</h5>
                    </div>

                    <?php if(isset($_GET['status'])): ?>
                        <?php 
                            $status = $_GET['status'];
                            $msg = ($status == 'sukses') ? "Modul berhasil dikirim!" : "Terjadi kesalahan sistem.";
                            $cls = ($status == 'sukses') ? "success" : "danger";
                            if($status == 'error_format') $msg = "Format salah! Gunakan PDF/DOCX.";
                            if($status == 'error_size') $msg = "File terlalu besar! Maks 5MB.";
                        ?>
                        <div class="alert alert-<?= $cls ?> alert-dismissible fade show small">
                            <?= $msg ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="../actions/proses_modul.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 text-start">
                            <label class="form-label small fw-bold">Judul Modul / Tugas <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>
                        
                        <div class="mb-3 text-start">
                            <label class="form-label small fw-bold">Nama Kelas</label>
                            <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: Kimia A 2023">
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label small fw-bold">Nama Matakuliah</label>
                            <input type="text" name="nama_matakuliah" class="form-control" placeholder="Contoh: Kimia Dasar I">
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label small fw-bold">Kategori Dokumen (Opsional)</label>
                            <select name="kategori" class="form-select">
                                <option value="">-- Tidak Dipilih --</option>
                                <option value="Modul Praktikum">Modul Praktikum</option>
                                <option value="Laporan">Laporan</option>
                                <option value="Tugas">Tugas</option>
                                <option value="SOP / Panduan">SOP / Panduan</option>
                            </select>
                        </div>

                        <div class="mb-4 text-start">
                                <label class="form-label small fw-bold text-dark">Pilih File (PDF / Word) <span class="text-danger">*</span></label>
                                <input type="file" name="berkas" class="form-control" required>
                                <div class="form-text mt-2 text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-info-circle"></i> Ukuran maksimal: <strong>5 MB</strong>. Format: PDF, DOCX.
                                </div>
                        </div>
                        
                        <button type="submit" name="upload" class="btn btn-primary w-100 py-2 fw-bold" style="background-color: var(--primary-blue);">
                            <i class="bi bi-cloud-arrow-up"></i> Upload Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>