<?php
session_start();
require '../config/database.php';

//1. FITUR SETUJUI
if (isset($_GET['setujui']) && isset($_GET['acc_qty'])) {
    $id = $_GET['setujui'];
    $acc_qty = (int)$_GET['acc_qty'];

    try {
        $pdo->beginTransaction();

        // 1. Ambil data asli pengajuan untuk tahu jumlah awal yang diminta
        $stmt = $pdo->prepare("SELECT nama_alat, jumlah FROM peminjaman WHERE id = ?");
        $stmt->execute([$id]);
        $p = $stmt->fetch();

        if ($p) {
            $requested_qty = (int)$p['jumlah'];
            $nama_alat = $p['nama_alat'];

            // 2. Logika Pengembalian Selisih Stok
            if ($acc_qty < $requested_qty) {
                $selisih = $requested_qty - $acc_qty;
                
                $upd_stok = $pdo->prepare("UPDATE alat SET jumlah_alat = jumlah_alat + ? WHERE nama_alat = ?");
                $upd_stok->execute([$selisih, $nama_alat]);
            }

            // 3. Update tabel peminjaman 
            $sql_update = "UPDATE peminjaman SET status = 'Dipinjam', jumlah = ? WHERE id = ?";
            $pdo->prepare($sql_update)->execute([$acc_qty, $id]);

            $pdo->commit();
            header("Location: ../admin/peminjaman.php?status=acc_sukses");
        } else {
            $pdo->rollBack();
            header("Location: ../admin/peminjaman.php?status=notfound");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
    exit();
}

// 2. FITUR TOLAK
if (isset($_GET['tolak'])) {
    $id = $_GET['tolak'];
    
    try {
        $pdo->beginTransaction();
        
        $stmt_get = $pdo->prepare("SELECT nama_alat, jumlah FROM peminjaman WHERE id = ?");
        $stmt_get->execute([$id]);
        $p = $stmt_get->fetch();
        
        if ($p) {
            $sql_stok = "UPDATE alat SET jumlah_alat = jumlah_alat + ? WHERE nama_alat = ?";
            $pdo->prepare($sql_stok)->execute([$p['jumlah'], $p['nama_alat']]);

            $pdo->prepare("DELETE FROM peminjaman WHERE id = ?")->execute([$id]);
            
            $pdo->commit();
            header("Location: ../admin/peminjaman.php?status=ditolak");
        } else {
            $pdo->rollBack();
            header("Location: ../admin/peminjaman.php?status=gagal");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
    exit();
}

//3. FITUR KEMBALIKAN ALAT
if (isset($_GET['kembalikan']) && isset($_GET['ret_qty'])) {
    $id = $_GET['kembalikan'];
    $ret_qty = (int)$_GET['ret_qty'];
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT * FROM peminjaman WHERE id = ?");
        $stmt->execute([$id]);
        $p = $stmt->fetch();

        if ($p) {
            $current_borrowed = (int)$p['jumlah'];
            $nama_alat = $p['nama_alat'];

            if ($ret_qty >= $current_borrowed) {
                $pdo->prepare("UPDATE peminjaman SET status = 'Dikembalikan', jumlah = ? WHERE id = ?")
                    ->execute([$current_borrowed, $id]);
            } else {

                $sisa = $current_borrowed - $ret_qty;

                $pdo->prepare("UPDATE peminjaman SET jumlah = ? WHERE id = ?")
                    ->execute([$sisa, $id]);

                $sql_histori = "INSERT INTO peminjaman (nama_mahasiswa, nama_alat, jumlah, tgl_pinjam, status) 
                                VALUES (?, ?, ?, ?, 'Dikembalikan')";
                $pdo->prepare($sql_histori)->execute([$p['nama_mahasiswa'], $nama_alat, $ret_qty, $p['tgl_pinjam']]);
            }

            $upd_stok = $pdo->prepare("UPDATE alat SET jumlah_alat = jumlah_alat + ? WHERE nama_alat = ?");
            $upd_stok->execute([$ret_qty, $nama_alat]);

            $pdo->commit();
            header("Location: ../admin/peminjaman.php?status=returned");
        } else {
            header("Location: ../admin/peminjaman.php?status=notfound");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_mhs = $_POST['nama_mhs'];
    $alat     = $_POST['alat'];
    $jumlah   = $_POST['jumlah'];
    $tgl      = $_POST['tgl'];
    $status   = "Dipinjam";

    try {
        $pdo->beginTransaction();

        $stmt_stok = $pdo->prepare("SELECT jumlah_alat FROM alat WHERE nama_alat = ?");
        $stmt_stok->execute([$alat]);
        $data_alat = $stmt_stok->fetch();

        if (!$data_alat) {
            header("Location: ../admin/peminjaman.php?status=alat_tidak_ada");
            exit();
        }

        if ($data_alat['jumlah_alat'] < $jumlah) {
            header("Location: ../admin/peminjaman.php?status=stok_kurang");
            exit();
        }

        $sql_pinjam = "INSERT INTO peminjaman (nama_mahasiswa, nama_alat, jumlah, tgl_pinjam, status) VALUES (?, ?, ?, ?, ?)";
        $pdo->prepare($sql_pinjam)->execute([$nama_mhs, $alat, $jumlah, $tgl, $status]);

        $sql_kurangi_stok = "UPDATE alat SET jumlah_alat = jumlah_alat - ? WHERE nama_alat = ?";
        $pdo->prepare($sql_kurangi_stok)->execute([$jumlah, $alat]);

        $pdo->commit();
        header("Location: ../admin/peminjaman.php?status=success");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>