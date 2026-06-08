<?php
session_start();
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $aksi = $_POST['aksi'] ?? '';

    // TAMBAH
    if ($aksi == 'tambah') {
        $nama     = $_POST['nama'] ?? '';
        $kondisi  = $_POST['kondisi'] ?? '';
        $merek    = $_POST['merek'] ?? '';
        $jumlah   = $_POST['jumlah'] ?? 0;
        $ukuran   = $_POST['ukuran'] ?? '';
        $kategori = $_POST['kategori'] ?? '';

        $sql = "INSERT INTO alat (nama_alat, kondisi_alat, merek_alat, jumlah_alat, ukuran, kategori)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $kondisi, $merek, $jumlah, $ukuran, $kategori]);

        header("Location: ../admin/inventaris.php?pesan=tambah_sukses");
        exit();
    }

    // EDIT
    if ($aksi == 'edit') {
        $id       = $_POST['id'] ?? 0;
        $nama     = $_POST['nama'] ?? '';
        $kondisi  = $_POST['kondisi'] ?? '';
        $merek    = $_POST['merek'] ?? '';
        $jumlah   = $_POST['jumlah'] ?? 0;
        $ukuran   = $_POST['ukuran'] ?? '';
        $kategori = $_POST['kategori'] ?? '';

        $sql = "UPDATE alat 
                SET nama_alat=?, kondisi_alat=?, merek_alat=?, jumlah_alat=?, ukuran=?, kategori=? 
                WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $kondisi, $merek, $jumlah, $ukuran, $kategori, $id]);

        header("Location: ../admin/inventaris.php?pesan=edit_sukses");
        exit();
    }

    // HAPUS
    if ($aksi == 'hapus') {
        $id = $_POST['id'] ?? 0;
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM alat WHERE id = ?");
            $stmt->execute([$id]);
        }

        header("Location: ../admin/inventaris.php?pesan=hapus_sukses");
        exit();
    }
}