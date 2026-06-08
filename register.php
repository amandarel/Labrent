<?php
require 'config/database.php';

$message = "";
$messageType = "";

if (isset($_POST['register'])) {
    $nama = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'mahasiswa'; 

    if ($password !== $confirm_password) {
        $message = "Konfirmasi password tidak cocok!";
        $messageType = "danger";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->fetch()) {
            $message = "Username sudah digunakan!";
            $messageType = "danger";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            try {
                $sql = "INSERT INTO users (nama_lengkap, username, password, role) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nama, $username, $hashed_password, $role]);
                
                $message = "Akun berhasil dibuat! Silahkan login.";
                $messageType = "success";
            } catch (PDOException $e) {
                $message = "Gagal mendaftar: " . $e->getMessage();
                $messageType = "danger";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Sistem Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { 
            background: #f4f7f6; 
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .register-container {
            width: 100%;
            padding: 20px;
        }
        .card-register { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            background: #ffffff;
            margin: auto;
            max-width: 480px; /* Lebar maksimal desktop */
        }
        .btn-primary { 
            background: #4a86c5; 
            border: none; 
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background: #3a6fa3;
        }
        .form-control {
            padding: 10px 12px;
            border-radius: 8px;
        }
        .input-group-text {
            border-radius: 8px 0 0 8px;
            background-color: #f8f9fa;
        }
        .form-control {
            border-radius: 0 8px 8px 0;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <div class="card card-register p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="assets/img/Logo-Unima.webp" alt="Logo" width="60" class="mb-3">
                <h4 class="fw-bold text-dark">Daftar Akun</h4>
                <p class="text-muted small">Lengkapi data untuk akses Sistem Lab</p>
            </div>

            <?php if($message): ?>
                <div class="alert alert-<?= $messageType ?> alert-dismissible fade show small" role="alert">
                    <i class="bi <?= $messageType == 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> me-2"></i>
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST">
                <!-- Nama Lengkap -->
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-badge text-muted"></i></span>
                        <input type="text" name="nama_lengkap" class="form-control" required placeholder="Nama lengkap sesuai KTM">
                    </div>
                </div>

                <!-- Username -->
                <div class="mb-3">
                    <label class="form-label small fw-bold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-at text-muted"></i></span>
                        <input type="text" name="username" class="form-control" required placeholder="Contoh: nim_anda">
                    </div>
                </div>

                <!-- Password Row -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control" required placeholder="Min 6 char">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold">Konfirmasi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-shield-lock text-muted"></i></span>
                            <input type="password" name="confirm_password" class="form-control" required placeholder="Ulangi">
                        </div>
                    </div>
                </div>

                <button type="submit" name="register" class="btn btn-primary w-100 shadow-sm mt-2 mb-3">
                    Daftar Sekarang <i class="bi bi-person-plus-fill ms-1"></i>
                </button>
                
                <div class="text-center">
                    <p class="small text-muted mb-0">Sudah memiliki akun?</p>
                    <a href="login.php" class="text-primary text-decoration-none fw-bold small">Masuk Ke Sistem</a>
                </div>
            </form>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted" style="font-size: 0.75rem;">&copy; <?= date('Y') ?> Laboratorium Kimia Unima. All Rights Reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>