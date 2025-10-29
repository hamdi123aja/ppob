<?php
session_start();
if (empty($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pembayaran.php';

include_once __DIR__ . '/../partials/header.php';
include_once __DIR__ . '/../partials/sidebar.php';

// ambil data laporan dari DB
$pembayaran = new Pembayaran($db);
$laporan = $pembayaran->getAll();
?>

<div class="main-content">
    <header class="app-header d-flex justify-content-between align-items-center mb-3">
        <h3>Laporan Pembayaran</h3>
        <div class="user-info">
            <div>
                <span><b><?= htmlspecialchars($_SESSION['user']['username']); ?></b></span><br>
                <span class="role"><?= htmlspecialchars($_SESSION['user']['role']); ?></span>
            </div>
            <a href="../logout.php" class="btn btn-sm btn-light">Logout</a>
        </div>
    </header>

    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Data Pembayaran</h5>
            <div class="mb-3 text-end">
                <a href="eksport_excel.php" class="btn btn-success btn-sm">🟢 Export Excel</a>
                <a href="eksport_word.php" class="btn btn-primary btn-sm">🔵 Export Word</a>
                <a href="eksport_pdf.php" class="btn btn-danger btn-sm">🔴 Export PDF</a>

            </div>

        </div>

        <div class="table-responsive">
            <table id="laporanTable" class="table table-bordered table-striped text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>No. Meter</th>
                        <th>Jumlah</th>
                        <th>Tanggal Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($laporan): ?>
                        <?php $no = 1;
                        foreach ($laporan as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['no_meter']) ?></td>
                                <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($row['tanggal_bayar']) ?></td>
                                <td>
                                    <?php if ($row['status'] === 'verified'): ?>
                                        <span class="badge bg-success">Verified</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data pembayaran</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
        $('#laporanTable').DataTable({
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