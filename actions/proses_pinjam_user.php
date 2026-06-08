<?php
session_start();
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_alat'])) {
    $ids_alat = $_POST['id_alat'];
    $jumlahs  = $_POST['jumlah'];
    $tgl      = $_POST['tgl'];
    $nama_mhs = $_SESSION['nama'];

    try {
        $pdo->beginTransaction();

        foreach ($ids_alat as $index => $id_alat) {
            $jml_minta = $jumlahs[$index];

            // 1. Cek stok terbaru
            $stmt = $pdo->prepare("SELECT nama_alat, jumlah_alat FROM alat WHERE id = ? FOR UPDATE");
            $stmt->execute([$id_alat]);
            $alat = $stmt->fetch();

            if (!$alat || $alat['jumlah_alat'] < $jml_minta) {
                throw new Exception("Stok " . ($alat['nama_alat'] ?? 'item') . " tidak mencukupi!");
            }
            
            // 2. Tentukan Status Awal
            $status = "Menunggu Persetujuan"; 

            // 3. Simpan data ke tabel peminjaman
            $stmt_ins = $pdo->prepare("INSERT INTO peminjaman (nama_mahasiswa, nama_alat, jumlah, tgl_pinjam, status) VALUES (?, ?, ?, ?, ?)");
            $stmt_ins->execute([$nama_mhs, $alat['nama_alat'], $jml_minta, $tgl, $status]);

            // 4. Potong Stok
            $stmt_upd = $pdo->prepare("UPDATE alat SET jumlah_alat = jumlah_alat - ? WHERE id = ?");
            $stmt_upd->execute([$jml_minta, $id_alat]);
        }

        $pdo->commit();
        echo "<script>alert('Berhasil! Pengajuan pinjaman sedang menunggu persetujuan admin.'); window.location='../mahasiswa/pinjam.php';</script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('Gagal: " . $e->getMessage() . "'); window.location='../mahasiswa/pinjam.php';</script>";
    }
} else {
    header("Location: ../mahasiswa/pinjam.php");
    exit();
}