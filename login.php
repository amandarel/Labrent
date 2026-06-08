<?php
session_start();
require 'config/database.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama'] = $user['nama_lengkap'];

        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard_admin.php");
        } else {
            header("Location: mahasiswa/dashboard_user.php");
        }
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { 
            background: #f4f7f6; 
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container {
            width: 100%;
            padding: 15px;
        }
        .card-login { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            background: #ffffff;
            margin: auto;
            max-width: 400px;
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
            padding: 12px;
            border-radius: 8px;
        }
        .login-logo {
            transition: transform 0.3s ease;
        }
        .login-logo:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="card card-login p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="assets/img/Logo-Unima.webp" alt="Logo" width="70" class="login-logo mb-3">
                <h4 class="fw-bold text-dark">Sistem Lab</h4>
                <p class="text-muted small">Silahkan masuk ke akun Anda</p>
            </div>

            <?php if(isset($error)): ?>
                <div class='alert alert-danger d-flex align-items-center py-2' role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div style="font-size: 0.85rem;"><?= $error ?></div>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" name="username" class="form-control border-start-0" placeholder="Masukkan username" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100 shadow-sm mb-3">
                    Masuk Sekarang <i class="bi bi-arrow-right-short ms-1"></i>
                </button>

                <div class="text-center mt-3">
                    <p class="small text-muted mb-0">Belum punya akun?</p>
                    <a href="register.php" class="text-primary text-decoration-none fw-bold small">Daftar Akun Baru</a>
                </div>
            </form>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-muted" style="font-size: 0.75rem;">&copy; <?= date('Y') ?> Laboratorium Kimia Unima. All Rights Reserved.</p>
        </div>
    </div>

</body>
</html>