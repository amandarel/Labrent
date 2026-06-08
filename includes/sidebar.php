<?php 
$currentPage = basename($_SERVER['PHP_SELF']); 
$role = $_SESSION['role'] ?? '';

function renderMenus($role, $currentPage) {
    ?>
    <?php if ($role == 'admin'): ?>
        <a class="nav-link <?= ($currentPage == 'dashboard_admin.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/dashboard_admin.php"><i class="bi bi-display"></i> Dashboard</a>
        <a class="nav-link <?= ($currentPage == 'daftar_alat.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>mahasiswa/daftar_alat.php"><i class="bi bi-list-ul"></i> Daftar Alat & Bahan</a>
        <a class="nav-link <?= ($currentPage == 'peminjaman.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/peminjaman.php"><i class="bi bi-person-check"></i> Daftar Peminjam</a>
        <a class="nav-link <?= ($currentPage == 'inventaris.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/inventaris.php"><i class="bi bi-box-seam"></i> Data Inventaris</a>
        <a class="nav-link <?= ($currentPage == 'modul.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/modul.php"><i class="bi bi-file-earmark-medical"></i> Manajemen Modul</a>
        <a class="nav-link <?= ($currentPage == 'laporan.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/laporan.php"><i class="bi bi-graph-up-arrow"></i> Laporan</a>
    <?php else: ?>
        <a class="nav-link <?= ($currentPage == 'dashboard_user.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>mahasiswa/dashboard_user.php"><i class="bi bi-house"></i> Home</a>
        <a class="nav-link <?= ($currentPage == 'daftar_alat.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>mahasiswa/daftar_alat.php"><i class="bi bi-list-ul"></i> Daftar Alat & Bahan</a>
        <a class="nav-link <?= ($currentPage == 'pinjam.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>mahasiswa/pinjam.php"><i class="bi bi-cart-plus"></i> Pinjam</a>
        <a class="nav-link <?= ($currentPage == 'modul_user.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>mahasiswa/modul_user.php"><i class="bi bi-upload"></i> Upload Modul</a>
    <?php endif; ?>
    <a class="nav-link <?= ($currentPage == 'about.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>about.php"><i class="bi bi-info-circle"></i> About</a>
    <a class="nav-link text-danger mt-md-5" href="<?= BASE_URL ?>logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    <?php
}
?>

<!-- SIDEBAR VERSI DESKTOP -->
<div class="col-md-2 d-none d-md-block sidebar bg-white border-right">
    <nav class="nav flex-column py-3">
        <?php renderMenus($role, $currentPage); ?>
    </nav>
</div>

<!-- SIDEBAR VERSI MOBILE -->
<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarMobile" style="width: 280px;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title text-primary fw-bold">Menu Navigasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="nav flex-column py-3 shadow-none">
            <?php renderMenus($role, $currentPage); ?>
        </nav>
    </div>
</div>

<style>
    .offcanvas-body .nav-link {
        font-weight: 500;
        transition: all 0.3s;
        border-radius: 0;
        display: flex;
        align-items: center;
    }
    .offcanvas-body .nav-link:hover:not(.active) {
        background-color: #f8f9fa;
        color: var(--primary-blue) !important;
        padding-left: 30px;
    }
    .offcanvas-body .nav-link i {
        font-size: 1.2rem;
    }
</style>