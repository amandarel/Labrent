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

        <div class="col-md-10 p-4 text-center">

            <h1 class="display-4 text-primary">Selamat Datang, <?= $_SESSION['nama'] ?></h1>
            <p class="lead">Panel Kendali Administrator - Sistem Informasi Laboratorium</p>
            <hr class="my-4" style="max-width: 800px; margin: auto;">

            <div class="mt-5 p-4 bg-light rounded shadow-sm" style="max-width: 800px; margin: auto;">
                <div class="row text-center">
                    <?php
                        $total_alat = $pdo->query("SELECT SUM(jumlah_alat) FROM alat")->fetchColumn();
                        $total_pinjam = $pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'Menunggu Persetujuan'")->fetchColumn();
                    ?>
                    <div class="col-6 border-end">
                        <small class="text-muted d-block">Stok Alat Tersedia</small>
                        <span class="h4 fw-bold"><?= number_format($total_alat) ?> Unit</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Permintaan ACC Baru</small>
                        <span class="h4 fw-bold text-danger"><?= $total_pinjam ?> Permintaan</span>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-4 justify-content-center">
                <div class="col-md-4">
                    <a href="peminjaman.php" class="btn btn-outline-primary p-5 w-100 shadow-sm border-2">
                        <i class="bi bi-patch-check fs-1 d-block mb-2"></i>
                        <span class="fw-bold">Peminjaman & ACC</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="inventaris.php" class="btn btn-outline-primary p-5 w-100 shadow-sm border-2">
                        <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                        <span class="fw-bold">Manajemen Inventaris</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="laporan.php" class="btn btn-outline-primary p-5 w-100 shadow-sm border-2">
                        <i class="bi bi-file-earmark-bar-graph fs-1 d-block mb-2"></i>
                        <span class="fw-bold">Laporan & Statistik</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="../admin/modul.php" class="btn btn-outline-secondary p-4 w-100 shadow-sm">
                        <i class="bi bi-upload fs-2 d-block mb-2"></i>
                        Upload Modul
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="../mahasiswa/daftar_alat.php" class="btn btn-outline-secondary p-4 w-100 shadow-sm">
                        <i class="bi bi-eye fs-2 d-block mb-2"></i>
                        Lihat Daftar Alat/Bahan
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .btn-outline-primary:hover, .btn-outline-secondary:hover {
        transform: translateY(-5px);
        transition: 0.3s;
    }
    .card-custom {
        border-radius: 15px;
    }
</style>

</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>