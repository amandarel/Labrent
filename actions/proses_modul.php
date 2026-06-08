<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}

// 1. FITUR HAPUS ( Admin)
if (isset($_GET['hapus'])) {
    if ($_SESSION['role'] !== 'admin') { die("Akses ditolak"); }
    $id = $_GET['hapus'];
    $file = $_GET['file'];
    $path = "../uploads/modul/" . $file;
    if (file_exists($path)) { unlink($path); }
    $stmt = $pdo->prepare("DELETE FROM modul WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: ../admin/modul.php?status=sukses_hapus");
    exit();
}

// 2. FITUR UPLOAD
if (isset($_POST['upload'])) {
    $judul = $_POST['judul'];
    $nama_kelas = !empty($_POST['nama_kelas']) ? $_POST['nama_kelas'] : '-';
    $nama_matakuliah = !empty($_POST['nama_matakuliah']) ? $_POST['nama_matakuliah'] : '-';
    $kategori = !empty($_POST['kategori']) ? $_POST['kategori'] : '-';
    $nama_pengupload = $_SESSION['nama'];
    
    date_default_timezone_set('Asia/Makassar'); 
    $tgl_upload = date('Y-m-d H:i');

    $file_name = $_FILES['berkas']['name'];
    $file_size = $_FILES['berkas']['size'];
    $file_tmp  = $_FILES['berkas']['tmp_name'];
    $file_error = $_FILES['berkas']['error'];

    $redir_target = ($_SESSION['role'] === 'admin') ? '../admin/modul.php' : '../mahasiswa/modul_user.php';

    if ($file_error === 4) {
        header("Location: $redir_target?status=error_no_file"); exit();
    }

    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['pdf', 'doc', 'docx'];
    if (!in_array($ext, $allowed_ext)) {
        header("Location: $redir_target?status=error_format"); exit();
    }

    if ($file_size > 5242880) {
        header("Location: $redir_target?status=error_size"); 
        exit();
    }

    $new_file_name = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $file_name);
    $upload_path = "../uploads/modul/" . $new_file_name;

    if (!is_dir('../uploads/modul')) { mkdir('../uploads/modul', 0777, true); }

    if (move_uploaded_file($file_tmp, $upload_path)) {
        $size_kb = round($file_size / 1024, 2);
        
        $sql = "INSERT INTO modul (judul, nama_kelas, nama_matakuliah, kategori, nama_pengupload, nama_file, ukuran, tgl_upload) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$judul, $nama_kelas, $nama_matakuliah, $kategori, $nama_pengupload, $new_file_name, $size_kb, $tgl_upload]);

        header("Location: $redir_target?status=sukses");
    } else {
        header("Location: $redir_target?status=error_upload");
    }
}