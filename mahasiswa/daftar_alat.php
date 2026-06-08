<?php 
session_start();
require '../config/database.php'; 
include '../includes/header.php'; 

// 1. PENGATURAN PAGINATION
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// 2. LOGIKA FILTER KATEGORI
$filter_kat = isset($_GET['kat']) ? $_GET['kat'] : 'Semua';

// 3. HITUNG TOTAL DATA
$count_query = "SELECT COUNT(*) FROM alat";
if ($filter_kat != 'Semua') {
    $count_query .= " WHERE kategori = :kat";
}
$stmt_total = $pdo->prepare($count_query);
if ($filter_kat != 'Semua') {
    $stmt_total->execute(['kat' => $filter_kat]);
} else {
    $stmt_total->execute();
}
$total_items = $stmt_total->fetchColumn();
$total_pages = ceil($total_items / $limit);

// 4. QUERY UTAMA DENGAN LIMIT & OFFSET
$query = "SELECT a.*, p.status as s_pinjam 
          FROM alat a 
          LEFT JOIN peminjaman p ON a.nama_alat = p.nama_alat AND p.status = 'Dipinjam'";

if ($filter_kat != 'Semua') {
    $query .= " WHERE a.kategori = :kat";
}

$query .= " GROUP BY a.id ORDER BY a.nama_alat ASC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
if ($filter_kat != 'Semua') {
    $stmt->bindValue(':kat', $filter_kat, PDO::PARAM_STR);
}
$stmt->execute();
$rows = $stmt->fetchAll();
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="col-md-10 p-4">
            <h4 class="mb-4 text-secondary"><i class="bi bi-box-seam"></i> Daftar Alat & Bahan Tersedia</h4>

            <!-- Navigasi Kategori -->
            <ul class="nav nav-pills mb-4 shadow-sm p-2 bg-white rounded">
                <li class="nav-item">
                    <a class="nav-link <?= $filter_kat == 'Semua' ? 'active' : '' ?>" href="daftar_alat.php?kat=Semua&page=1">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $filter_kat == 'Alat' ? 'active' : '' ?>" href="daftar_alat.php?kat=Alat&page=1">Alat Lab</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $filter_kat == 'Bahan' ? 'active' : '' ?>" href="daftar_alat.php?kat=Bahan&page=1">Bahan & Kimia</a>
                </li>
            </ul>

            <div class="card card-custom border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="bg-light">
                            <tr class="text-center small">
                                <th>Nama Alat / Bahan</th>
                                <th>Kondisi</th>
                                <th>Merek</th>
                                <th>Ukuran</th>
                                <th>Stok</th>
                                <th>Status Peminjaman</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($rows):
                                foreach($rows as $row):
                                    $badge_kondisi = 'bg-success'; 
                                    if ($row['kondisi_alat'] == 'Retak') {
                                        $badge_kondisi = 'bg-warning text-dark';
                                    } elseif ($row['kondisi_alat'] == 'Rusak') {
                                        $badge_kondisi = 'bg-danger';
                                    }

                                    if ($row['jumlah_alat'] <= 0) {
                                        $status = '<span class="badge bg-danger w-100">Habis / Tidak Tersedia</span>';
                                    } elseif ($row['s_pinjam']) {
                                        $status = '<span class="badge bg-warning text-dark w-100">Sedang Dipakai</span>';
                                    } else {
                                        $status = '<span class="badge bg-success w-100">Tersedia</span>';
                                    }
                            ?>
                            <tr class="align-middle text-center small">
                                <td class="text-start fw-bold"><?= htmlspecialchars($row['nama_alat']); ?></td>
                                <td><span class="badge <?= $badge_kondisi; ?>"><?= htmlspecialchars($row['kondisi_alat'] ?: '-'); ?></span></td>
                                <td><?= htmlspecialchars($row['merek_alat'] ?: '-'); ?></td>
                                <td><?= htmlspecialchars($row['ukuran'] ?: '-'); ?></td>
                                <td><span class="fw-bold"><?= $row['jumlah_alat']; ?></span></td>
                                <td width="180"><?= $status; ?></td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="6" class="text-center text-muted py-5">Data tidak ditemukan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 5. UI PAGINATION -->
            <?php if ($total_pages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?kat=<?= $filter_kat ?>&page=<?= $page - 1 ?>">Previous</a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="?kat=<?= $filter_kat ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?kat=<?= $filter_kat ?>&page=<?= $page + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
            <p class="text-center text-muted small">Menampilkan total <?= $total_items ?> item.</p>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>