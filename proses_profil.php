<?php
session_start();
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_SESSION['user_id'];
    $nama_lengkap = $_POST['nama_lengkap']; // Sesuaikan name form
    $user_name = $_POST['username'];
    $pw_lama = $_POST['pw_lama'];
    $pw_baru = $_POST['pw_baru'];

    // 1. Ambil data user
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $u = $stmt->fetch();

    // 2. Verifikasi Password Lama
    if (!password_verify($pw_lama, $u['password'])) {
        header("Location: profil.php?status=error_pw");
        exit();
    }

    // 3. Cek Username Duplikat
    $cek = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $cek->execute([$user_name, $id]);
    if ($cek->fetch()) {
        header("Location: profil.php?status=error_user");
        exit();
    }

    // 4. Update Data (Gunakan kolom nama_lengkap)
    if (!empty($pw_baru)) {
        $sql = "UPDATE users SET nama_lengkap = ?, username = ?, password = ? WHERE id = ?";
        $pdo->prepare($sql)->execute([$nama_lengkap, $user_name, password_hash($pw_baru, PASSWORD_DEFAULT), $id]);
    } else {
        $sql = "UPDATE users SET nama_lengkap = ?, username = ? WHERE id = ?";
        $pdo->prepare($sql)->execute([$nama_lengkap, $user_name, $id]);
    }

    // Update Nama di Session agar di Header ikut berubah
    $_SESSION['nama'] = $nama_lengkap;
    
    header("Location: profil.php?status=sukses");
    exit();
}