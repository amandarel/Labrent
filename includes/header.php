<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --primary-blue: #4a86c5; --light-border: #dee2e6; }
        body { background-color: #fff; font-family: sans-serif; }
        .header-top { border-bottom: 2px solid #f0f0f0; padding: 10px 20px; }
        .logo-text { color: var(--primary-blue); font-weight: bold; font-size: 1.2rem; }
        .profile-dropdown .dropdown-toggle::after { display: none; }
        .profile-dropdown .btn-profile { 
            background: none; border: none; display: flex; align-items: center; gap: 10px; color: #555;
            padding: 5px 12px; border-radius: 50px; transition: 0.3s;
        }
        .profile-dropdown .btn-profile:hover { background-color: #f1f5f9; color: var(--primary-blue); }
        .dropdown-menu { border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 8px; font-size: 0.9rem; }
        
        .sidebar { border-right: 1px solid var(--light-border); min-height: 100vh; padding: 0; }
        .nav-link { color: #333; padding: 12px 20px; border-bottom: 1px solid #f1f1f1; display: flex; align-items: center; }
        .nav-link i { margin-right: 10px; color: var(--primary-blue); }
        .nav-link.active { background-color: var(--primary-blue); color: white !important; }
        .nav-link.active i { color: white; }
        .card-custom { border: 1px solid var(--light-border); border-radius: 0; }
        .card-header-blue { background: white; border-bottom: 1px solid var(--light-border); padding: 10px 15px; color: #003366; font-weight: bold; }
        
    @media (max-width: 768px) {
        .sticky-col {
            position: sticky;
            right: 0;
            background-color: white !important;
            z-index: 1;
            box-shadow: -2px 0 5px rgba(0,0,0,0.1);
            }
        .table-dark .sticky-col {
            background-color: #212529 !important;
            }
        }
    </style>
</head>
<body>

<div class="header-top d-flex justify-content-between align-items-center shadow-sm">
    <div class="d-flex align-items-center">
        <button class="btn btn-white border-0 d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
            <i class="bi bi-list fs-2 text-primary"></i>
        </button>
        
        <img src="<?= BASE_URL ?>/assets/img/Logo-Unima.webp" width="40" class="me-2" alt="Logo">
        <span class="logo-text d-none d-md-block">Sistem Informasi Lab</span>
    </div>

    <!-- Dropdown Profil -->
    <div class="dropdown profile-dropdown">
        <button class="btn-profile dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <div class="text-end d-none d-sm-block">
                <span class="d-block fw-bold" style="font-size: 0.85rem;"><?= htmlspecialchars($_SESSION['nama']); ?></span>
                <small class="text-muted" style="font-size: 0.75rem;"><?= ucfirst($_SESSION['role']); ?></small>
            </div>
            <i class="bi bi-person-circle fs-3 ms-2"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
            <li><a class="dropdown-item" href="<?= BASE_URL ?>profil.php">Pengaturan Profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>logout.php">Logout</a></li>
        </ul>
    </div>
</div>