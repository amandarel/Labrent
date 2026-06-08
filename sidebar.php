<?php 
$currentPage = basename($_SERVER['PHP_SELF']); 
$role = $_SESSION['role'] ?? '';
?>
<div class="col-md-2 sidebar">
    <nav class="nav flex-column">
        <?php if ($role == 'admin'): ?>
            <!-- Menu Khusus Admin -->
            <a class="nav-link <?= ($currentPage == 'index.php') ? 'active' : '' ?>" href="index.php"><i class="bi bi-display"></i> Dashboard</a>
            <a class="nav-link <?= ($currentPage == 'daftar_alat.php') ? 'active' : '' ?>" href="daftar_alat.php"><i class="bi bi-list-ul"></i> Daftar Alat & Bahan</a>
            <a class="nav-link <?= ($currentPage == 'peminjaman.php') ? 'active' : '' ?>" href="peminjaman.php"><i class="bi bi-tools"></i> Daftar Peminjam</a>
            <a class="nav-link <?= ($currentPage == 'inventaris.php') ? 'active' : '' ?>" href="inventaris.php"><i class="bi bi-journal-text"></i> Data Inventaris</a>
            <a class="nav-link <?= ($currentPage == 'modul.php') ? 'active' : '' ?>" href="modul.php"><i class="bi bi-file-earmark-arrow-up"></i> Upload Modul</a>
            <a class="nav-link <?= ($currentPage == 'laporan.php') ? 'active' : '' ?>" href="laporan.php"><i class="bi bi-graph-up-arrow"></i> Laporan</a>
        <?php else: ?>
            <!-- Menu Khusus Mahasiswa -->
            <a class="nav-link <?= ($currentPage == 'home.php') ? 'active' : '' ?>" href="home.php"><i class="bi bi-house"></i> Home</a>
            <a class="nav-link <?= ($currentPage == 'daftar_alat.php') ? 'active' : '' ?>" href="daftar_alat.php"><i class="bi bi-list-ul"></i> Daftar Alat & Bahan</a>
                        <a class="nav-link <?= ($currentPage == 'pinjam.php') ? 'active' : '' ?>" href="pinjam.php"><i class="bi bi-cart-plus"></i> Pinjam Alat & Bahan</a>
            <a class="nav-link <?= ($currentPage == 'modul_user.php') ? 'active' : '' ?>" href="modul_user.php"><i class="bi bi-upload"></i> Upload Modul</a>
            <a class="nav-link <?= ($currentPage == 'about.php') ? 'active' : '' ?>" href="about.php"><i class="bi bi-info-circle"></i> About</a>
        <?php endif; ?>
        
        <a class="nav-link text-danger mt-5" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
</div>