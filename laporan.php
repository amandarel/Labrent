<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require 'config/database.php'; 
include 'header.php'; 

// Logika Filter Tanggal
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : date('Y-m-01');
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : date('Y-m-d');
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 noprint">
                <h4 class="text-secondary">Laporan & Statistik Laboratorium</h4>
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Cetak Laporan
                </button>
            </div>

            <!-- Ringkasan Statistik (Tetap sama) -->
            <div class="row mb-4">
                <?php
                $count_alat = $pdo->query("SELECT SUM(jumlah_alat) FROM alat")->fetchColumn();
                $count_pinjam = $pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'Dipinjam'")->fetchColumn();
                $count_modul = $pdo->query("SELECT COUNT(*) FROM modul")->fetchColumn();
                ?>
                <div class="col-md-4">
                    <div class="card card-custom p-3 text-center border-start border-4 border-primary shadow-sm">
                        <small class="text-muted fw-bold">TOTAL UNIT ALAT</small>
                        <h3 class="mb-0 text-primary"><?= number_format($count_alat); ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-custom p-3 text-center border-start border-4 border-warning shadow-sm">
                        <small class="text-muted fw-bold">ALAT SEDANG DIPINJAM</small>
                        <h3 class="mb-0 text-warning"><?= $count_pinjam; ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-custom p-3 text-center border-start border-4 border-success shadow-sm">
                        <small class="text-muted fw-bold">MODUL TERSEDIA</small>
                        <h3 class="mb-0 text-success"><?= $count_modul; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Filter Laporan (Disembunyikan saat cetak) -->
            <div class="card card-custom mb-4 shadow-sm noprint">
                <div class="card-header-blue">Filter Rentang Waktu</div>
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Dari Tanggal</label>
                            <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Sampai Tanggal</label>
                            <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-filter"></i> Tampilkan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Hasil Laporan Grouping -->
            <div class="card card-custom shadow-sm">
                <div class="card-header-blue">
                    Riwayat Aktivitas Mahasiswa (<?= date('d M Y', strtotime($tgl_mulai)) ?> - <?= date('d M Y', strtotime($tgl_selesai)) ?>)
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="bg-light text-center small">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Total Item Dipinjam</th>
                                <th>Rentang Tanggal</th>
                                <th class="noprint">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query Grouping berdasarkan Nama Mahasiswa dalam rentang tanggal
                            $sql = "SELECT nama_mahasiswa, COUNT(*) as total_item, 
                                           MIN(tgl_pinjam) as tgl_awal, MAX(tgl_pinjam) as tgl_akhir
                                    FROM peminjaman 
                                    WHERE tgl_pinjam BETWEEN ? AND ? 
                                    GROUP BY nama_mahasiswa 
                                    ORDER BY tgl_akhir DESC";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$tgl_mulai, $tgl_selesai]);
                            $rows = $stmt->fetchAll();

                            if ($rows):
                                $no = 1;
                                foreach($rows as $row):
                                    // Ambil semua detail peminjaman mahasiswa ini untuk modal
                                    $stmt_det = $pdo->prepare("SELECT * FROM peminjaman WHERE nama_mahasiswa = ? AND tgl_pinjam BETWEEN ? AND ?");
                                    $stmt_det->execute([$row['nama_mahasiswa'], $tgl_mulai, $tgl_selesai]);
                                    $det_json = json_encode($stmt_det->fetchAll(PDO::FETCH_ASSOC));
                            ?>
                            <tr class="text-center align-middle small">
                                <td><?= $no++; ?></td>
                                <td class="text-start fw-bold"><?= htmlspecialchars($row['nama_mahasiswa']); ?></td>
                                <td><span class="badge bg-dark"><?= $row['total_item']; ?> Item</span></td>
                                <td>
                                    <?= date('d/m/y', strtotime($row['tgl_awal'])) ?> - <?= date('d/m/y', strtotime($row['tgl_akhir'])) ?>
                                </td>
                                <td class="noprint">
                                    <button class="btn btn-sm btn-info text-white" onclick='showDetail(<?= $det_json ?>, "<?= $row['nama_mahasiswa'] ?>")'>
                                        <i class="bi bi-list-check"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada riwayat aktivitas ditemukan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DETAIL LAPORAN -->
<div class="modal fade" id="modalDetailLaporan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rincian Riwayat: <span id="mhsNama" class="text-primary fw-bold"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-striped table-sm mb-0 text-center">
                    <thead class="table-dark">
                        <tr class="small">
                            <th>Nama Alat / Bahan</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pinjam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="isiDetail"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- CSS Khusus Cetak -->
<style>
@media print {
    .sidebar, .noprint, .btn-close { display: none !important; }
    .col-md-10 { width: 100% !important; margin: 0 !important; padding: 0 !important; flex: 0 0 100% !important; max-width: 100% !important; }
    .card { border: 1px solid #eee !important; box-shadow: none !important; }
    body { background-color: white !important; }
    .table-responsive { overflow: visible !important; }
}
</style>

<script>
function showDetail(data, nama) {
    document.getElementById('mhsNama').innerText = nama;
    const list = document.getElementById('isiDetail');
    list.innerHTML = "";

    data.forEach(item => {
        let badgeColor = item.status === 'Dipinjam' ? 'bg-warning text-dark' : 
                         (item.status === 'Dikembalikan' ? 'bg-success' : 'bg-info text-dark');
        
        const row = `
            <tr class="small align-middle">
                <td class="text-start px-3">${item.nama_alat}</td>
                <td>${item.jumlah}</td>
                <td>${item.tgl_pinjam}</td>
                <td><span class="badge ${badgeColor}">${item.status}</span></td>
            </tr>
        `;
        list.insertAdjacentHTML('beforeend', row);
    });

    new bootstrap.Modal(document.getElementById('modalDetailLaporan')).show();
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
