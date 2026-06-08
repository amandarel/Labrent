<?php 
session_start();
require 'config/database.php'; 
include 'includes/header.php'; 

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<div class="container-fluid"><div class="row">
    <?php include 'includes/sidebar.php'; ?>
    <div class="col-md-10 p-5">
        <div class="card mx-auto shadow-sm" style="max-width: 500px; border-radius: 10px;">
            <div class="card-body p-4">
                <h5 class="mb-4 text-center fw-bold text-secondary">Pengaturan Profil</h5>
                
                <?php if(isset($_GET['status'])): ?>
                    <div class="alert alert-<?= ($_GET['status'] == 'sukses') ? 'success' : 'danger' ?> small">
                        <?php
                            if($_GET['status'] == 'sukses') echo 'Profil berhasil diperbarui!';
                            if($_GET['status'] == 'error_pw') echo 'Password lama salah!';
                            if($_GET['status'] == 'error_user') echo 'Username sudah digunakan!';
                        ?>
                    </div>
                <?php endif; ?>

                <form action="actions/proses_profil.php" method="POST">
                    <div class="mb-3">
                        <label class="small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <hr>
                    
                    <!-- Password Lama -->
                    <div class="mb-3">
                        <label class="small fw-bold">Password Saat Ini (Wajib)</label>
                        <div class="input-group">
                            <input type="password" name="pw_lama" id="pw_lama" class="form-control" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('pw_lama', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Password Baru -->
                    <div class="mb-3">
                        <label class="small fw-bold">Password Baru (Opsional)</label>
                        <div class="input-group">
                            <input type="password" name="pw_baru" id="pw_baru" class="form-control" placeholder="Kosongkan jika tidak ganti">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('pw_baru', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold mt-2">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div></div>

<script>
// Fungsi untuk sembunyi/tampilkan password
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = "password";
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>