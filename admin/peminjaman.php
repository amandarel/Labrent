<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require '../config/database.php'; 
include '../includes/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <h4 class="text-secondary mb-4"><i class="bi bi-person-lines-fill"></i> Log Peminjaman Mahasiswa</h4>

            <?php if(isset($_GET['status'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    Permintaan berhasil diproses!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Pencarian Global Berdasarkan Nama -->
            <?php $search = isset($_GET['cari']) ? "%" . $_GET['cari'] . "%" : "%"; ?>

            <!-- ================= TABEL 0: MENUNGGU PERSETUJUAN ================= -->
            <div class="card card-custom shadow-sm mb-5 border-info">
                <div class="card-header bg-info text-dark d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="bi bi-patch-check"></i> Permintaan Persetujuan (Baru)</span>
                    <form class="d-flex" method="GET">
                        <input class="form-control form-control-sm me-2" type="search" name="cari" placeholder="Cari nama..." value="<?= isset($_GET['cari']) ? $_GET['cari'] : '' ?>">
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light">
                            <tr class="text-center small">
                                <th width="50">No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Jumlah Item</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_acc = "SELECT nama_mahasiswa, COUNT(*) as total 
                                        FROM peminjaman 
                                        WHERE status = 'Menunggu Persetujuan' AND nama_mahasiswa LIKE ? 
                                        GROUP BY nama_mahasiswa ORDER BY id ASC";
                            $stmt_acc = $pdo->prepare($sql_acc);
                            $stmt_acc->execute([$search]);
                            $rows_acc = $stmt_acc->fetchAll();

                            if ($rows_acc): $no=1; foreach($rows_acc as $ra):
                                $stmt_det = $pdo->prepare("SELECT * FROM peminjaman WHERE nama_mahasiswa = ? AND status = 'Menunggu Persetujuan'");
                                $stmt_det->execute([$ra['nama_mahasiswa']]);
                                $det_json = json_encode($stmt_det->fetchAll(PDO::FETCH_ASSOC));
                            ?>
                            <tr class="text-center align-middle">
                                <td><?= $no++ ?></td>
                                <td class="text-start fw-bold"><?= htmlspecialchars($ra['nama_mahasiswa']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= $ra['total'] ?> Item</span></td>
                                <td><span class="badge bg-dark text-light border">Menunggu ACC</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info text-dark fw-bold" onclick='showDetail(<?= $det_json ?>, "<?= $ra['nama_mahasiswa'] ?>", "pending")'>
                                        <i class="bi bi-eye"></i> Periksa Detail
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada permintaan persetujuan baru.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================= TABEL 1: PEMINJAMAN AKTIF (GROUPING) ================= -->
            <div class="card card-custom shadow-sm mb-5">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="bi bi-clock-history"></i> Mahasiswa Meminjam (Aktif)</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light small text-center">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Jumlah Item</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_aktif = "SELECT nama_mahasiswa, COUNT(*) as total 
                                          FROM peminjaman 
                                          WHERE status = 'Dipinjam' AND nama_mahasiswa LIKE ? 
                                          GROUP BY nama_mahasiswa ORDER BY id DESC";
                            $stmt_aktif = $pdo->prepare($sql_aktif);
                            $stmt_aktif->execute([$search]);
                            $rows_aktif = $stmt_aktif->fetchAll();

                            if ($rows_aktif): $no=1; foreach($rows_aktif as $ra):
                                $stmt_det = $pdo->prepare("SELECT * FROM peminjaman WHERE nama_mahasiswa = ? AND status = 'Dipinjam'");
                                $stmt_det->execute([$ra['nama_mahasiswa']]);
                                $det_json = json_encode($stmt_det->fetchAll(PDO::FETCH_ASSOC));
                            ?>
                            <tr class="text-center align-middle">
                                <td><?= $no++ ?></td>
                                <td class="text-start"><?= htmlspecialchars($ra['nama_mahasiswa']) ?></td>
                                <td><span class="badge bg-dark"><?= $ra['total'] ?> Item</span></td>
                                <td><span class="badge bg-warning text-dark">Dipakai</span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick='showDetail(<?= $det_json ?>, "<?= $ra['nama_mahasiswa'] ?>", "aktif")'>
                                        <i class="bi bi-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada peminjaman aktif.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================= TABEL 2: RIWAYAT SELESAI (GROUPING) ================= -->
            <div class="card card-custom shadow-sm border-0">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="bi bi-check-circle"></i> Mahasiswa Sudah Mengembalikan (Selesai)</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light small text-center">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Jumlah Item</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_done = "SELECT nama_mahasiswa, COUNT(*) as total 
                                         FROM peminjaman 
                                         WHERE status = 'Dikembalikan' AND nama_mahasiswa LIKE ? 
                                         GROUP BY nama_mahasiswa ORDER BY id DESC";
                            $stmt_done = $pdo->prepare($sql_done);
                            $stmt_done->execute([$search]);
                            $rows_done = $stmt_done->fetchAll();

                            if ($rows_done): $no=1; foreach($rows_done as $rd):
                                $stmt_det_done = $pdo->prepare("SELECT * FROM peminjaman WHERE nama_mahasiswa = ? AND status = 'Dikembalikan'");
                                $stmt_det_done->execute([$rd['nama_mahasiswa']]);
                                $det_json_done = json_encode($stmt_det_done->fetchAll(PDO::FETCH_ASSOC));
                            ?>
                            <tr class="text-center align-middle">
                                <td><?= $no++ ?></td>
                                <td class="text-start"><?= htmlspecialchars($rd['nama_mahasiswa']) ?></td>
                                <td><span class="badge bg-dark"><?= $rd['total'] ?> Item</span></td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success" onclick='showDetail(<?= $det_json_done ?>, "<?= $rd['nama_mahasiswa'] ?>", "selesai")'>
                                        <i class="bi bi-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat pengembalian.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rincian Item Mahasiswa: <span id="namaMhs" class="text-primary fw-bold"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-striped mb-0 text-center">
                    <thead class="table-dark small">
                        <tr>
                            <th>Nama Alat / Bahan</th>
                            <th>Jml</th>
                            <th>Tgl Pinjam</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="kontenDetail"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function showDetail(data, nama, tipe) {
    document.getElementById('namaMhs').innerText = nama;
    const list = document.getElementById('kontenDetail');
    list.innerHTML = "";

    data.forEach(item => {
        let tombolAksi = "";
        let kolomJumlah = item.jumlah;

        if (tipe === 'pending') {
            kolomJumlah = `<input type="number" id="qty_${item.id}" class="form-control form-control-sm mx-auto" 
                            value="${item.jumlah}" min="1" max="${item.jumlah}" style="width:70px;">`;
            tombolAksi = `<button onclick="prosesACC(${item.id})" class="btn btn-sm btn-success py-0">Setujui</button>
                          <a href="../actions/proses_pinjam.php?tolak=${item.id}" class="btn btn-sm btn-outline-danger py-0" onclick="return confirm('Tolak?')">Tolak</a>`;

        } else if (tipe === 'aktif') {
            kolomJumlah = `<input type="number" id="ret_${item.id}" class="form-control form-control-sm mx-auto" 
                            value="${item.jumlah}" min="1" max="${item.jumlah}" style="width:70px;">
                           <small class="text-muted" style="font-size:0.6rem;">Sisa: ${item.jumlah}</small>`;
            
            tombolAksi = `<button onclick="prosesKembali(${item.id})" class="btn btn-sm btn-danger py-0">
                            <i class="bi bi-arrow-return-left"></i> Kembalikan
                          </button>`;

        } else {
            tombolAksi = `<span class="badge bg-success small"><i class="bi bi-check"></i> Sudah Kembali</span>`;
        }

        const row = `
            <tr class="align-middle small">
                <td class="text-start fw-bold">${item.nama_alat}</td>
                <td>${kolomJumlah}</td>
                <td>${item.tgl_pinjam}</td>
                <td><div class="btn-group">${tombolAksi}</div></td>
            </tr>
        `;
        list.insertAdjacentHTML('beforeend', row);
    });

    new bootstrap.Modal(document.getElementById('modalDetail')).show();
}

function prosesACC(id) {
    const qty = document.getElementById('qty_' + id).value;
    if (confirm('Setujui ' + qty + ' item?')) {
        window.location.href = '../actions/proses_pinjam.php?setujui=' + id + '&acc_qty=' + qty;
    }
}

function prosesKembali(id) {
    const qty = document.getElementById('ret_' + id).value;
    if (confirm('Kembalikan ' + qty + ' item ke stok?')) {
        window.location.href = '../actions/proses_pinjam.php?kembalikan=' + id + '&ret_qty=' + qty;
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
