<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Lab</title>
    <!-- Link CSS tetap sama -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --primary-blue: #4a86c5; --light-border: #dee2e6; }
        body { background-color: #fff; font-family: sans-serif; }
        .header-top { border-bottom: 2px solid #f0f0f0; padding: 10px 20px; }
        .logo-text { color: var(--primary-blue); font-weight: bold; font-size: 1.2rem; }
        
        /* Dropdown Styling */
        .profile-dropdown .dropdown-toggle::after { display: none; }
        .profile-dropdown .btn-profile { 
            background: none; border: none; display: flex; align-items: center; gap: 10px; color: #555;
            padding: 5px 12px; border-radius: 50px; transition: 0.3s;
        }
        .profile-dropdown .btn-profile:hover { background-color: #f1f5f9; color: var(--primary-blue); }
        .dropdown-menu { border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 8px; font-size: 0.9rem; }
        
        /* Sidebar & Nav Styles tetap sama */
        .sidebar { border-right: 1px solid var(--light-border); min-height: 100vh; padding: 0; }
        .nav-link { color: #333; padding: 12px 20px; border-bottom: 1px solid #f1f1f1; display: flex; align-items: center; }
        .nav-link i { margin-right: 10px; color: var(--primary-blue); }
        .nav-link.active { background-color: var(--primary-blue); color: white !important; }
        .nav-link.active i { color: white; }
        .card-custom { border: 1px solid var(--light-border); border-radius: 0; }
        .card-header-blue { background: white; border-bottom: 1px solid var(--light-border); padding: 10px 15px; color: #003366; font-weight: bold; }
    </style>
</head>
<body>

<div class="header-top d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <img src="Logo-Unima.png" width="40" class="me-2" alt="Logo">
        <span class="logo-text d-none d-md-block">Sistem Informasi Lab</span>
    </div>

    <!-- Dropdown Profil Aman -->
    <div class="dropdown profile-dropdown">
        <button class="btn-profile dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="text-end d-none d-sm-block">
                <!-- 3. Proteksi XSS: Menggunakan htmlspecialchars -->
                <span class="d-block fw-bold" style="font-size: 0.85rem; line-height: 1.2;">
                    <?= htmlspecialchars($_SESSION['nama'] ?? 'User'); ?>
                </span>
                <small class="text-muted" style="font-size: 0.75rem;">
                    <?= htmlspecialchars(ucfirst($_SESSION['role'] ?? 'Guest')); ?>
                </small>
            </div>
            <i class="bi bi-person-circle fs-3"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end p-2">
            <li><h6 class="dropdown-header d-sm-none"><?= htmlspecialchars($_SESSION['nama'] ?? 'User'); ?></h6></li>
            <li><a class="dropdown-item rounded" href="profil.php"><i class="bi bi-person-gear"></i> Pengaturan Profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <!-- Logout sebaiknya diarahkan ke file proses logout -->
            <li><a class="dropdown-item rounded text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
        </ul>
    </div>
</div>