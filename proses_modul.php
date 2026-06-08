<?php
session_start();
require 'config/database.php';

// Pastikan user sudah login
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// 1. FITUR HAPUS (Hanya untuk Admin)
if (isset($_GET['hapus'])) {
    if ($_SESSION['role'] !== 'admin') {
        die("Akses ditolak: Hanya admin yang dapat menghapus modul.");
    }

    $id = $_GET['hapus'];
    $file = $_GET['file'];

    $path = "uploads/modul/" . $file;
    if (file_exists($path)) {
        unlink($path);
    }

    $stmt = $pdo->prepare("DELETE FROM modul WHERE id = ?");
    $stmt->execute([$id]);

    // Sesuaikan nama file redirect ke modul.php (sesuai file yang Anda pakai sebelumnya)
    header("Location: modul.php?status=sukses_hapus");
    exit();
}

// 2. FITUR UPLOAD (Untuk Admin & Mahasiswa)
if (isset($_POST['upload'])) {
    $judul = $_POST['judul'];
    
    // AMBIL NAMA PENGUPLOAD DARI SESSION
    $nama_pengupload = $_SESSION['nama']; 
    
    $kategori = ($_SESSION['role'] === 'admin') ? $_POST['kategori'] : 'Modul Mahasiswa';
    
    $file_name = $_FILES['berkas']['name'];
    $file_size = $_FILES['berkas']['size'];
    $file_tmp  = $_FILES['berkas']['tmp_name'];
    $file_error = $_FILES['berkas']['error'];

    // Redirect target (Sesuaikan jika nama file Anda modul.php atau modul_admin.php)
    $redir_target = ($_SESSION['role'] === 'admin') ? 'modul.php' : 'modul_user.php';

    if ($file_error === 4) {
        header("Location: $redir_target?status=error_no_file");
        exit();
    }

    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['pdf', 'doc', 'docx'];

    if (!in_array($ext, $allowed_ext)) {
        header("Location: $redir_target?status=error_format");
        exit();
    }

    if ($file_size > 5242880) {
        header("Location: $redir_target?status=error_size");
        exit();
    }

    $new_file_name = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $file_name);
    $upload_path = "uploads/modul/" . $new_file_name;

    if (!is_dir('uploads/modul')) {
        mkdir('uploads/modul', 0777, true);
    }

    if (move_uploaded_file($file_tmp, $upload_path)) {
        $size_kb = round($file_size / 1024, 2);
        
        // UPDATE: Tambahkan kolom nama_pengupload di sini
        $sql = "INSERT INTO modul (judul, kategori, nama_pengupload, nama_file, ukuran) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$judul, $kategori, $nama_pengupload, $new_file_name, $size_kb]);

        header("Location: $redir_target?status=sukses");
    } else {
        header("Location: $redir_target?status=error_upload");
    }
}