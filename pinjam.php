<?php 
session_start();
if($_SESSION['role'] != 'mahasiswa') header("Location: login.php");
require 'config/database.php';
include 'header.php';

$search = isset($_GET['cari']) ? "%".$_GET['cari']."%" : "%";
$filter_kat = isset($_GET['kat']) ? $_GET['kat'] : 'Semua';

$query_sql = "SELECT * FROM alat WHERE nama_alat LIKE ? AND jumlah_alat > 0";
$params = [$search];

if ($filter_kat != 'Semua') {
    $query_sql .= " AND kategori = ?";
    $params[] = $filter_kat;
}

$query_sql .= " ORDER BY nama_alat ASC LIMIT 50";
$stmt_katalog = $pdo->prepare($query_sql);
$stmt_katalog->execute($params);
$katalog = $stmt_katalog->fetchAll();
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>
        
        <div class="col-md-10 p-4">
            <h4 class="text-secondary mb-4"><i class="bi bi-cart4"></i> Peminjaman Alat & Bahan</h4>

            <div class="row">
                <!-- KATALOG -->
                <div class="col-md-7">
                    <div class="card card-custom mb-4 shadow-sm">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="small fw-bold mb-2">Kategori:</label>
                                <div class="btn-group w-100">
                                    <a href="pinjam.php?kat=Semua" class="btn btn-sm <?= $filter_kat == 'Semua' ? 'btn-primary' : 'btn-outline-primary' ?>">Semua</a>
                                    <a href="pinjam.php?kat=Alat" class="btn btn-sm <?= $filter_kat == 'Alat' ? 'btn-primary' : 'btn-outline-primary' ?>">Alat</a>
                                    <a href="pinjam.php?kat=Bahan" class="btn btn-sm <?= $filter_kat == 'Bahan' ? 'btn-primary' : 'btn-outline-primary' ?>">Bahan</a>
                                </div>
                            </div>

                            <form method="GET" class="d-flex mb-3">
                                <input type="hidden" name="kat" value="<?= $filter_kat ?>">
                                <input type="text" name="cari" class="form-control me-2" placeholder="Cari alat..." value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>">
                                <button type="submit" class="btn btn-dark"><i class="bi bi-search"></i></button>
                            </form>

                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light sticky-top text-center small">
                                        <tr><th>Nama Item</th><th>Stok</th><th>Aksi</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php if($katalog): foreach($katalog as $a): ?>
                                        <tr>
                                            <td>
                                                <span class="fw-bold"><?= htmlspecialchars($a['nama_alat']) ?></span><br>
                                                <small class="text-muted"><?= htmlspecialchars($a['merek_alat']) ?> | <?= htmlspecialchars($a['ukuran']) ?></small>
                                            </td>
                                            <td class="text-center"><span class="badge bg-info text-dark"><?= $a['jumlah_alat'] ?></span></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-success" onclick="tambahKeKeranjang(<?= $a['id'] ?>, '<?= htmlspecialchars($a['nama_alat']) ?>', <?= $a['jumlah_alat'] ?>)">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; else: ?>
                                        <tr><td colspan="3" class="text-center py-4">Item tidak ditemukan.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KERANJANG (Daftar Pilihan) -->
                <div class="col-md-5">
                    <div class="card card-custom border-primary shadow-sm mb-4">
                        <div class="card-header bg-primary text-white fw-bold">
                            <i class="bi bi-basket3"></i> Daftar Pilihan
                        </div>
                        <div class="card-body">
                            <!-- PASTIKAN ADA id="formPinjam" -->
                            <form action="proses_pinjam_user.php" method="POST" id="formPinjam">
                                <div class="mb-3">
                                    <label class="small fw-bold">Tanggal Pinjam:</label>
                                    <input type="date" name="tgl" class="form-control" value="<?= date('Y-m-d') ?>" required>
                                </div>

                                <div id="keranjang-kosong" class="text-center py-4 text-muted">
                                    <i class="bi bi-cart-x fs-2"></i><br>Belum ada item dipilih
                                </div>

                                <div id="area-keranjang" class="d-none">
                                    <table class="table table-sm small">
                                        <thead><tr><th>Item</th><th width="80">Jml</th><th></th></tr></thead>
                                        <tbody id="list-item-pinjam"></tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary w-100 fw-bold mt-2">KIRIM PENGAJUAN</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIWAYAT PINJAMAN -->
            <div class="card card-custom shadow-sm">
                <div class="card-header-blue fw-bold"><i class="bi bi-clock-history"></i> Riwayat Pinjaman Saya</div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 text-center small">
                        <thead class="bg-light">
                            <tr><th>Nama Alat/Bahan</th><th>Jumlah</th><th>Tanggal Pinjam</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt_hist = $pdo->prepare("SELECT * FROM peminjaman WHERE nama_mahasiswa = ? ORDER BY id DESC LIMIT 10");
                            $stmt_hist->execute([$_SESSION['nama']]);
                            $histori = $stmt_hist->fetchAll();
                            
                            if ($histori): foreach ($histori as $h):
                            ?>
                            <tr class="align-middle">
                                <td class="text-start fw-bold"><?= htmlspecialchars($h['nama_alat']) ?></td>
                                <td><?= $h['jumlah'] ?></td>
                                <td><?= date('d/m/Y', strtotime($h['tgl_pinjam'])) ?></td>
                                <td>
                                    <?php 
                                    $st = $h['status'];
                                    if ($st == 'Menunggu Persetujuan') {
                                        echo '<span class="badge bg-info text-dark"><i class="bi bi-clock"></i> Menunggu Persetujuan</span>';
                                    } elseif ($st == 'Dipinjam') {
                                        echo '<span class="badge bg-warning text-dark"><i class="bi bi-box-seam"></i> Dipinjam</span>';
                                    } elseif ($st == 'Dikembalikan') {
                                        echo '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Dikembalikan</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary">'.htmlspecialchars($st).'</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="4" class="py-3 text-muted">Belum ada riwayat peminjaman.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let keranjang = JSON.parse(localStorage.getItem('keranjang_pinjam')) || {};
document.addEventListener('DOMContentLoaded', () => { renderKeranjang(); });

function tambahKeKeranjang(id, nama, stokMax) {
    if (keranjang[id]) { alert("Item sudah ada di daftar!"); return; }
    keranjang[id] = { id: id, nama: nama, stok: stokMax, jumlah: 1 };
    simpanKeranjang();
    renderKeranjang();
}

function hapusItem(id) {
    delete keranjang[id];
    simpanKeranjang();
    renderKeranjang();
}

function updateJumlah(id, val) {
    if(keranjang[id]) { keranjang[id].jumlah = val; simpanKeranjang(); }
}

function simpanKeranjang() { localStorage.setItem('keranjang_pinjam', JSON.stringify(keranjang)); }

function renderKeranjang() {
    const list = document.getElementById('list-item-pinjam');
    const kosong = document.getElementById('keranjang-kosong');
    const area = document.getElementById('area-keranjang');
    list.innerHTML = "";
    let keys = Object.keys(keranjang);

    if (keys.length === 0) {
        kosong.classList.remove('d-none');
        area.classList.add('d-none');
    } else {
        kosong.classList.add('d-none');
        area.classList.remove('d-none');
        keys.forEach(id => {
            const item = keranjang[id];
            list.insertAdjacentHTML('beforeend', `
                <tr class="align-middle">
                    <td><small class="fw-bold">${item.nama}</small><input type="hidden" name="id_alat[]" value="${item.id}"></td>
                    <td><input type="number" name="jumlah[]" class="form-control form-control-sm" value="${item.jumlah}" min="1" max="${item.stok}" onchange="updateJumlah(${id}, this.value)" required></td>
                    <td class="text-end"><button type="button" class="btn btn-link text-danger p-0" onclick="hapusItem(${id})"><i class="bi bi-x-circle-fill"></i></button></td>
                </tr>
            `);
        });
    }
}

document.getElementById('formPinjam').addEventListener('submit', () => {
    localStorage.removeItem('keranjang_pinjam');
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>