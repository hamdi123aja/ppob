<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pembayaran.php';

$pembayaranModel = new Pembayaran($db);
$tagihan = $pembayaranModel->getAll();

include_once __DIR__ . '/../partials/header.php';
include_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="main-content">
    <header class="app-header d-flex justify-content-between align-items-center mb-3">
        <h4>Verifikasi Tagihan</h4>
        <div class="user-info">
            <div>
                <span><b><?= htmlspecialchars($_SESSION['user']['username']); ?></b></span><br>
                <span class="role"><?= htmlspecialchars($_SESSION['user']['role']); ?></span>
            </div>
            <a href="../logout.php" class="btn btn-sm btn-light">Logout</a>
        </div>
    </header>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="mb-3">Daftar Tagihan</h5>
            <div class="table-responsive">
                <table id="tagihanTable" class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Pelanggan</th>
                            <th>No Meter</th>
                            <th>Jumlah</th>
                            <th>Tanggal Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tagihan)): ?>
                            <?php foreach ($tagihan as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1; ?></td>
                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td><?= htmlspecialchars($row['no_meter']); ?></td>
                                    <td>Rp <?= number_format($row['jumlah'], 0, ',', '.'); ?></td>
                                    <td><?= $row['tanggal_bayar']; ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'verified'): ?>
                                            <span class="badge bg-success">Terverifikasi</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] !== 'verified'): ?>
                                            <a href="verifikasi.php?id=<?= $row['id']; ?>"
                                                class="btn btn-sm btn-success">Verifikasi</a>
                                        <?php else: ?>
                                            <span class="text-muted">✔</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Belum ada tagihan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tagihanTable').DataTable({
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

<?php include_once __DIR__ . '/../partials/footer.php'; ?>
