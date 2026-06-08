<?php
session_start();
require 'config/database.php';

// --- FITUR SETUJUI (ACC) ---
if (isset($_GET['setujui'])) {
    $id = $_GET['setujui'];
    $sql = "UPDATE peminjaman SET status = 'Dipinjam' WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    header("Location: peminjaman.php?status=acc_sukses");
    exit();
}

// --- FITUR TOLAK (Jika ditolak, stok dikembalikan) ---
if (isset($_GET['tolak'])) {
    $id = $_GET['tolak'];
    
    try {
        $pdo->beginTransaction();
        
        // 1. Ambil data jumlah dan nama alat
        $stmt_get = $pdo->prepare("SELECT nama_alat, jumlah FROM peminjaman WHERE id = ?");
        $stmt_get->execute([$id]);
        $p = $stmt_get->fetch();
        
        if ($p) {
            // 2. Kembalikan stok alat
            $sql_stok = "UPDATE alat SET jumlah_alat = jumlah_alat + ? WHERE nama_alat = ?";
            $pdo->prepare($sql_stok)->execute([$p['jumlah'], $p['nama_alat']]);
            
            // 3. Hapus pengajuan
            $pdo->prepare("DELETE FROM peminjaman WHERE id = ?")->execute([$id]);
            
            $pdo->commit();
            header("Location: peminjaman.php?status=ditolak");
        } else {
            $pdo->rollBack();
            header("Location: peminjaman.php?status=gagal");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
    exit();
}

// --- FITUR KEMBALIKAN ALAT (GET) ---
if (isset($_GET['kembalikan'])) {
    $id = $_GET['kembalikan'];

    try {
        $pdo->beginTransaction(); // Mulai transaksi

        // 1. Ambil data peminjaman untuk tahu Nama Alat dan Jumlahnya
        $stmt_check = $pdo->prepare("SELECT nama_alat, jumlah FROM peminjaman WHERE id = ?");
        $stmt_check->execute([$id]);
        $data_pinjam = $stmt_check->fetch();

        if ($data_pinjam) {
            $nama_alat = $data_pinjam['nama_alat'];
            $jumlah_pinjam = $data_pinjam['jumlah'];

            // 2. Update status peminjaman menjadi Dikembalikan
            $sql_update_pinjam = "UPDATE peminjaman SET status = 'Dikembalikan' WHERE id = ?";
            $pdo->prepare($sql_update_pinjam)->execute([$id]);

            // 3. Tambahkan kembali stok di tabel alat
            $sql_update_stok = "UPDATE alat SET jumlah_alat = jumlah_alat + ? WHERE nama_alat = ?";
            $pdo->prepare($sql_update_stok)->execute([$jumlah_pinjam, $nama_alat]);

            $pdo->commit(); // Simpan permanen perubahan
            header("Location: peminjaman.php?status=returned");
        } else {
            header("Location: peminjaman.php?status=notfound");
        }
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack(); // Batalkan semua jika ada error
        die("Error: " . $e->getMessage());
    }
}

// --- FITUR TAMBAH PEMINJAMAN (POST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_mhs = $_POST['nama_mhs'];
    $alat     = $_POST['alat'];
    $jumlah   = $_POST['jumlah'];
    $tgl      = $_POST['tgl'];
    $status   = "Dipinjam";

    try {
        $pdo->beginTransaction(); // Mulai transaksi

        // 1. Cek stok alat saat ini mencukupi atau tidak
        $stmt_stok = $pdo->prepare("SELECT jumlah_alat FROM alat WHERE nama_alat = ?");
        $stmt_stok->execute([$alat]);
        $data_alat = $stmt_stok->fetch();

        if (!$data_alat) {
            header("Location: peminjaman.php?status=alat_tidak_ada");
            exit();
        }

        if ($data_alat['jumlah_alat'] < $jumlah) {
            // Jika stok kurang dari jumlah yang mau dipinjam
            header("Location: peminjaman.php?status=stok_kurang");
            exit();
        }

        // 2. Simpan data ke tabel peminjaman
        $sql_pinjam = "INSERT INTO peminjaman (nama_mahasiswa, nama_alat, jumlah, tgl_pinjam, status) VALUES (?, ?, ?, ?, ?)";
        $pdo->prepare($sql_pinjam)->execute([$nama_mhs, $alat, $jumlah, $tgl, $status]);

        // 3. Kurangi stok di tabel alat
        $sql_kurangi_stok = "UPDATE alat SET jumlah_alat = jumlah_alat - ? WHERE nama_alat = ?";
        $pdo->prepare($sql_kurangi_stok)->execute([$jumlah, $alat]);

        $pdo->commit(); // Simpan permanen perubahan
        header("Location: peminjaman.php?status=success");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack(); // Batalkan semua jika ada error
        die("Error: " . $e->getMessage());
    }
}
?>