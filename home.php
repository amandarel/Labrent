<?php session_start(); if($_SESSION['role'] != 'mahasiswa') header("Location: login.php"); ?>
<?php include 'header.php'; ?>
<div class="container-fluid"><div class="row">
    <?php include 'sidebar.php'; ?>
    <div class="col-md-10 p-5 text-center">
        <h1 class="display-4 text-primary">Selamat Datang, <?= $_SESSION['nama'] ?></h1>
        <p class="lead">Sistem Informasi Laboratorium Kimia</p>
        <hr class="my-4">
        <div class="row g-4 mt-2">
            <div class="col-md-4"><a href="daftar_alat.php" class="btn btn-outline-primary p-4 w-100">Cek Stok Alat</a></div>
            <div class="col-md-4"><a href="modul_user.php" class="btn btn-outline-primary p-4 w-100">Upload Modul</a></div>
            <div class="col-md-4"><a href="about.php" class="btn btn-outline-primary p-4 w-100">Info Lab</a></div>
        </div>
    </div>
</div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>