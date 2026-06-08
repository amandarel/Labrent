<?php
session_start();
require 'config/database.php';

// Fitur Hapus (GET)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM alat WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: inventaris.php?pesan=hapus_sukses");
    exit();
}

// Fitur Tambah & Edit (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aksi     = $_POST['aksi'];
    $nama     = $_POST['nama'];
    $kondisi  = $_POST['kondisi'];
    $merek    = $_POST['merek'];
    $jumlah   = $_POST['jumlah'];
    $ukuran   = $_POST['ukuran'];
    $kategori = $_POST['kategori']; // Ambil sekali di sini saja

    if ($aksi == 'tambah') {
        $sql = "INSERT INTO alat (nama_alat, kondisi_alat, merek_alat, jumlah_alat, ukuran, kategori) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $kondisi, $merek, $jumlah, $ukuran, $kategori]);
    } 
    elseif ($aksi == 'edit') {
        $id = $_POST['id'];
        $sql = "UPDATE alat SET nama_alat=?, kondisi_alat=?, merek_alat=?, jumlah_alat=?, ukuran=?, kategori=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $kondisi, $merek, $jumlah, $ukuran, $kategori, $id]);
    }

    header("Location: inventaris.php?pesan=sukses");
    exit();
}