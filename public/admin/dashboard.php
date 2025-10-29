<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pelanggan.php';
require_once __DIR__ . '/../../models/Pembayaran.php';
require_once __DIR__ . '/../../models/Tagihan.php';

$pelangganModel  = new Pelanggan($db);
$pembayaranModel = new Pembayaran($db);
$tagihanModel    = new Tagihan($db);

// Ambil data statistik
$totalPelanggan = $pelangganModel->countAll();

// 🔹 hitung tagihan yang belum dibayar atau masih pending
$tagihanBelumBayar = $db->query("
    SELECT COUNT(*) 
    FROM pembayaran 
    WHERE status = 'pending'
")->fetchColumn();

// 🔹 total transaksi dan pendapatan
$totalPembayaran = $pembayaranModel->countAll();
$totalPendapatan = $pembayaranModel->sumAll();

// Transaksi terbaru
$latest = $pembayaranModel->getLatest(10); // ambil 10 dulu, nanti di-limit DataTables

include '../partials/header.php';
include '../partials/sidebar.php';
?>

<!-- Tambahkan CSS DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="main-content">
    <header class="app-header d-flex justify-content-between align-items-center mb-3">
        <h3>Dashboard Admin</h3>
        <div class="user-info">
            <div>
                <span><b><?= htmlspecialchars($_SESSION['user']['username']); ?></b></span><br>
                <span class="role"><?= htmlspecialchars($_SESSION['user']['role']); ?></span>
            </div>
            <a href="../logout.php" class="btn btn-sm btn-light">Logout</a>
        </div>
    </header>

    <!-- 4 Pilar Statistik -->
    <!-- 4 Pilar Statistik -->
    <div class="row text-center mb-4">
    <!-- Box Pelanggan -->
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 h-100" style="background-color:#0d6efd; color:white;">
            <div class="card-body">
                <i class="bi bi-people-fill fs-1 mb-2"></i>
                <h6 class="fw-normal">Pelanggan</h6>
                <h3 class="fw-bold mb-0"><?= $totalPelanggan; ?></h3>
            </div>
        </div>
    </div>

    <!-- Box Tagihan Belum Dibayar -->
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 h-100" style="background-color:#ffc107; color:#000;">
            <div class="card-body">
                <i class="bi bi-receipt fs-1 mb-2"></i>
                <h6 class="fw-normal">Tagihan Belum Dibayar</h6>
                <h3 class="fw-bold mb-0"><?= $tagihanBelumBayar; ?></h3>
            </div>
        </div>
    </div>

    <!-- Box Total Pembayaran -->
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 h-100" style="background-color:#198754; color:white;">
            <div class="card-body">
                <i class="bi bi-wallet2 fs-1 mb-2"></i>
                <h6 class="fw-normal">Total Pembayaran</h6>
                <h3 class="fw-bold mb-0"><?= $totalPembayaran; ?></h3>
            </div>
        </div>
    </div>

    <!-- Box Total Pendapatan -->
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 h-100" style="background-color:#dc3545; color:white;">
            <div class="card-body">
                <i class="bi bi-cash-coin fs-1 mb-2"></i>
                <h6 class="fw-normal">Total Pendapatan</h6>
                <h3 class="fw-bold mb-0">Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?></h3>
            </div>
        </div>
    </div>
</div>



    <!-- Transaksi Terbaru -->
    <div class="card p-4 shadow-sm">
        <h5 class="mb-3">📑 Transaksi Terbaru</h5>
        <div class="table-responsive">
            <table id="transaksiTable" class="table table-bordered table-striped text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Jumlah</th>
                        <th>Tanggal Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($latest): ?>
                        <?php foreach ($latest as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                                <td><?= $row['tanggal_bayar'] ?></td>
                                <td>
                                    <?php if ($row['status'] === 'verified'): ?>
                                        <span class="badge bg-success">Terverifikasi</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Belum ada transaksi</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tambahkan JS DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#transaksiTable').DataTable({
            "pageLength": 5, // default tampilkan 5 data
            "lengthMenu": [5, 10, 25, 50], // pilihan jumlah data
            "ordering": true, // aktifkan sorting
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "paginate": {
                    "previous": "Sebelumnya",
                    "next": "Berikutnya"
                },
                "zeroRecords": "Tidak ada data ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data tersedia",
            }
        });
    });
</script>
