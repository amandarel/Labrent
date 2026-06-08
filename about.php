<?php session_start();
require_once 'config/database.php';
include 'includes/header.php'; ?>
<div class="container-fluid"><div class="row">
    <?php include 'includes/sidebar.php'; ?>
    <div class="col-md-10 p-5">
        <div class="card card-custom p-4">
            <h3>Tentang Laboratorium Kimia</h3>
            <p>Laboratorium ini digunakan untuk kegiatan praktikum mahasiswa tingkat dasar hingga lanjut. Dilengkapi dengan peralatan modern dan standar keamanan tinggi.</p>
            <hr>
            <h5>Kontak Laboran:</h5>
            <ul>
                <li>Laboran: (021) 123456</li>
                <li>Email: labkimia@kampus.ac.id</li>
            </ul>
        </div>
    </div>
</div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>