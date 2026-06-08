<?php 
session_start();
// Proteksi halaman: Hanya mahasiswa yang boleh akses
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: login.php");
    exit();
}
require 'config/database.php';
include 'header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>
        
        <div class="col-md-10 p-4 text-center">
            <h4 class="text-secondary mb-4 text-start">Kirim Modul Praktikum</h4>
            
            <div class="card card-custom m-auto" style="max-width: 600px; border-radius: 10px;">
                <div class="card-body p-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/338/338910.png" width="80" class="mb-3">
                    <h5 class="mb-3">Upload Dokumen</h5>
                    <p class="text-muted small mb-4">Unggah modul, laporan, atau tugas praktikum Anda di sini dalam format PDF atau Word.</p>

                    <!-- Bagian Alert Notifikasi -->
                    <?php if(isset($_GET['status'])): ?>
                        <?php 
                            $status = $_GET['status'];
                            $alertClass = "alert-danger";
                            $pesan = "Terjadi kesalahan.";

                            if($status == 'sukses') {
                                $alertClass = "alert-success";
                                $pesan = "Modul berhasil dikirim ke Admin!";
                            } elseif($status == 'error_format') {
                                $pesan = "Format file tidak didukung! Gunakan PDF, DOC, atau DOCX.";
                            } elseif($status == 'error_size') {
                                $pesan = "File terlalu besar! Maksimal ukuran adalah 5MB.";
                            } elseif($status == 'error_no_file') {
                                $pesan = "Silahkan pilih file terlebih dahulu.";
                            } elseif($status == 'error_upload') {
                                $pesan = "Gagal mengunggah file ke server.";
                            }
                        ?>
                        <div class="alert <?= $alertClass ?> alert-dismissible fade show small" role="alert">
                            <?= $pesan ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="proses_modul.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 text-start">
                            <label class="form-label small fw-bold text-dark">Judul Modul / Tugas</label>
                            <input type="text" name="judul" class="form-control" required placeholder="Masukkan judul dokumen...">
                        </div>
                        <div class="mb-4 text-start">
                            <label class="form-label small fw-bold text-dark">Pilih File (PDF / Word)</label>
                            <input type="file" name="berkas" class="form-control" required>
                            <div class="form-text mt-2" style="font-size: 0.75rem;">Maksimal ukuran file: 5 MB</div>
                        </div>
                        <button type="submit" name="upload" class="btn btn-primary w-100 py-2 fw-bold" style="background-color: var(--primary-blue); border: none;">
                            <i class="bi bi-cloud-arrow-up me-2"></i> Upload Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <!-- Info tambahan untuk user -->
            <div class="mt-4 text-muted small" style="max-width: 600px; margin-left: auto; margin-right: auto;">
                <i class="bi bi-info-circle me-1"></i> Catatan: File yang telah diunggah akan ditinjau oleh Admin Lab. Anda tidak dapat menghapus file yang sudah terkirim.
            </div>
        </div>
    </div>
</div>

<!-- Pastikan memanggil JS Bootstrap agar tombol 'close' pada alert berfungsi -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>