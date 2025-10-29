<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pembayaran.php';

$pembayaranModel = new Pembayaran($db);

// pastikan id pelanggan ada di session
$pelanggan_id = $_SESSION['user']['pelanggan_id'] ?? null;
if (!$pelanggan_id) {
    die('<div class="alert alert-danger">❌ Gagal memuat data pelanggan. Silakan login ulang.</div>');
}

// Ambil data berdasarkan pelanggan login
$riwayat           = $pembayaranModel->getByPelanggan($pelanggan_id);
$totalTransaksi    = $pembayaranModel->countByPelanggan($pelanggan_id);
$verifikasiPending = $pembayaranModel->countPendingByPelanggan($pelanggan_id);
$totalVerified     = $pembayaranModel->sumByPelanggan($pelanggan_id);

include '../partials/header.php';
include '../partials/sidebar.php';
?>

<div class="main-content">
    <header class="app-header d-flex justify-content-between align-items-center mb-3">
        <h3>Dashboard Pelanggan</h3>
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
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Transaksi</h6>
                    <h3 class="fw-bold"><?= $totalTransaksi ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body text-center">
                    <h6>Menunggu Verifikasi</h6>
                    <h3 class="fw-bold"><?= $verifikasiPending ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Pembayaran Terverifikasi</h6>
                    <h3 class="fw-bold">Rp <?= number_format($totalVerified, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="mb-3">📄 Riwayat Pembayaran Anda</h5>
            <div class="table-responsive">
                <table id="riwayatTable" class="table table-bordered table-striped text-center align-middle datatable">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Jumlah</th>
                            <th width="20%">Bulan</th>
                            <th width="15%">Tahun</th>
                            <th width="25%">Tanggal Bayar</th>
                            <th width="15%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($riwayat)): ?>
                            <?php foreach ($riwayat as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1; ?></td>
                                    <td>Rp <?= number_format($row['jumlah'], 0, ',', '.'); ?></td>
                                    <?php
                                    $namaBulan = [
                                        1 => 'Januari',
                                        2 => 'Februari',
                                        3 => 'Maret',
                                        4 => 'April',
                                        5 => 'Mei',
                                        6 => 'Juni',
                                        7 => 'Juli',
                                        8 => 'Agustus',
                                        9 => 'September',
                                        10 => 'Oktober',
                                        11 => 'November',
                                        12 => 'Desember'
                                    ];
                                    $bulanText = $row['bulan'] ?? '-';
                                    if (is_numeric($bulanText) && isset($namaBulan[(int)$bulanText])) {
                                        $bulanText = $namaBulan[(int)$bulanText];
                                    }
                                    ?>
                                    <td><?= htmlspecialchars($bulanText); ?></td>

                                    <td><?= htmlspecialchars($row['tahun'] ?? '-'); ?></td>
                                    <td><?= htmlspecialchars($row['tanggal_bayar'] ?? '-'); ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'verified'): ?>
                                            <span class="badge bg-success">Verified</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">Belum ada data pembayaran</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>

<!-- DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#riwayatTable').DataTable({
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: true,
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                },
                zeroRecords: "Tidak ada data ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
            }
        });
    });
</script>