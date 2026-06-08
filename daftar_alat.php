<?php 
session_start(); 
require 'config/database.php'; 
include 'header.php'; 

// Ambil kategori dari URL, default ke 'Semua'
$filter_kat = isset($_GET['kat']) ? $_GET['kat'] : 'Semua';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>
        
        <div class="col-md-10 p-4">
            <h4 class="mb-4 text-secondary"><i class="bi bi-box-seam"></i> Daftar Alat & Bahan Tersedia</h4>

            <!-- Navigasi Kategori (Tab Style) -->
            <ul class="nav nav-pills mb-4 shadow-sm p-2 bg-white rounded">
                <li class="nav-item">
                    <a class="nav-link <?= $filter_kat == 'Semua' ? 'active' : '' ?>" href="daftar_alat.php?kat=Semua">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $filter_kat == 'Alat' ? 'active' : '' ?>" href="daftar_alat.php?kat=Alat">
                        <i class="bi bi-tools"></i> Alat Lab
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $filter_kat == 'Bahan' ? 'active' : '' ?>" href="daftar_alat.php?kat=Bahan">
                        <i class="bi bi-droplet-potion"></i> Bahan & Kimia
                    </a>
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
                            // Query Dasar
                            $query = "SELECT a.*, p.status as s_pinjam 
                                      FROM alat a 
                                      LEFT JOIN peminjaman p ON a.nama_alat = p.nama_alat AND p.status = 'Dipinjam'";

                            // Tambahkan Filter Kategori
                            if ($filter_kat != 'Semua') {
                                $query .= " WHERE a.kategori = :kat";
                            }

                            $query .= " GROUP BY a.id ORDER BY a.nama_alat ASC";
                            
                            $stmt = $pdo->prepare($query);
                            
                            if ($filter_kat != 'Semua') {
                                $stmt->execute(['kat' => $filter_kat]);
                            } else {
                                $stmt->execute();
                            }
                            
                            $rows = $stmt->fetchAll();

                            if ($rows):
                                foreach($rows as $row):
                                    // 1. LOGIKA WARNA BADGE KONDISI (Sama seperti inventaris admin)
                                    $badge_kondisi = 'bg-success'; 
                                    if ($row['kondisi_alat'] == 'Retak') {
                                        $badge_kondisi = 'bg-warning text-dark';
                                    } elseif ($row['kondisi_alat'] == 'Rusak') {
                                        $badge_kondisi = 'bg-danger';
                                    }

                                    // 2. LOGIKA STATUS PEMINJAMAN
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
                                <!-- Menampilkan Kondisi -->
                                <td>
                                    <span class="badge <?= $badge_kondisi; ?>">
                                        <?= htmlspecialchars($row['kondisi_alat']); ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row['merek_alat']); ?></td>
                                <td><?= htmlspecialchars($row['ukuran']); ?></td>
                                <td><span class="fw-bold"><?= $row['jumlah_alat']; ?></span></td>
                                <td width="180"><?= $status; ?></td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">Data pada kategori ini belum tersedia.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>