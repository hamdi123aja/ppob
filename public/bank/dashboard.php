<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'bank') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pembayaran.php';

$pembayaranModel = new Pembayaran($db);

// Data statistik
$totalTransaksi     = $pembayaranModel->countAll();
$verifikasiMenunggu = $db->query("SELECT COUNT(*) FROM pembayaran WHERE status='pending'")->fetchColumn();
$totalVerified      = $pembayaranModel->sumAll();

// Riwayat pembayaran terbaru
$riwayat = $pembayaranModel->getAll();

include '../partials/header.php';
include '../partials/sidebar.php';
?>  

<div class="main-content">
    <header class="app-header d-flex justify-content-between align-items-center mb-3">
        <h3>Dashboard Bank</h3>
        <div class="user-info">
            <div>
                <span><b><?= htmlspecialchars($_SESSION['user']['username']); ?></b></span><br>
                <span class="role"><?= htmlspecialchars($_SESSION['user']['role']); ?></span>
            </div>
            <a href="../logout.php" class="btn btn-sm btn-light">Logout</a>
        </div>
    </header>

    <!-- Statistik -->
    <div class="row mb-4">
        <!-- Total Transaksi -->
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-list-check display-6 mb-2"></i>
                    <h6>Total Transaksi</h6>
                    <h3 class="fw-bold"><?= $totalTransaksi ?></h3>
                </div>
            </div>
        </div>

        <!-- Verifikasi Menunggu -->
        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history display-6 mb-2"></i>
                    <h6>Verifikasi Menunggu</h6>
                    <h3 class="fw-bold"><?= $verifikasiMenunggu ?></h3>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-cash-stack display-6 mb-2"></i>
                    <h6>Total Pendapatan (Terverifikasi)</h6>
                    <h3 class="fw-bold">Rp <?= number_format($totalVerified, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTable Pembayaran -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">📑 Daftar Pembayaran Terbaru</h5>
                <a href="export_pdf.php" class="btn btn-primary btn-sm">📄 Generate Laporan</a>
            </div>
            <div class="table-responsive">
                <table id="transaksiTable" class="table table-bordered table-striped text-center align-middle datatable">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Pelanggan</th>
                            <th>Jumlah</th>
                            <th>Tanggal Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($riwayat): ?>
                            <?php foreach ($riwayat as $i => $row): ?>
                                <tr>
                                    <td><?= $i+1 ?></td>
                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                                    <td><?= $row['tanggal_bayar'] ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'verified'): ?>
                                            <span class="badge bg-success">Verified</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] !== 'verified'): ?>
                                            <a href="verifikasi.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Verifikasi</a>
                                        <?php else: ?>
                                            <span class="text-muted">✔</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">Belum ada pembayaran</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50],
        "ordering": true,
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
