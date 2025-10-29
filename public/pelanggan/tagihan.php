<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];

// sementara contoh data (nanti ambil dari database)
$tagihan = [
    ["bulan" => "Januari", "tahun" => "2025", "jumlah" => 200000, "status" => "Belum dibayar", "tanggal" => "-"],
    ["bulan" => "Februari", "tahun" => "2025", "jumlah" => 200000, "status" => "Sudah dibayar", "tanggal" => "2025-02-15"],
];
?>
<?php include_once("../partials/header.php"); ?>

<div class="sidebar">
    <h4 class="mb-4">⚡ PPOB APP</h4>
    <ul>
        <li><a href="dashboard.php">🏠 Dashboard</a></li>
        <li><a href="tagihan.php" class="active">📄 Tagihan Saya</a></li>
        <li><a href="../logout.php">🚪 Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <header class="app-header">
        <h3>Tagihan Saya</h3>
        <div class="user-info">
            <div>
                <span><b><?= htmlspecialchars($user['nama'] ?? $user['username']); ?></b></span><br>
                <span class="role"><?= htmlspecialchars($user['role']); ?></span>
            </div>
            <a href="../logout.php" class="btn btn-sm btn-light">Logout</a>
        </div>
    </header>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title">Daftar Tagihan</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tagihan as $t): ?>
                        <tr>
                            <td><?= $t['bulan']; ?></td>
                            <td><?= $t['tahun']; ?></td>
                            <td>Rp <?= number_format($t['jumlah'], 0, ',', '.'); ?></td>
                            <td>
                                <?php if ($t['status'] === "Sudah dibayar"): ?>
                                    <span class="badge bg-success"><?= $t['status']; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><?= $t['status']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= $t['tanggal']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<footer>
    &copy; 2025 PPOB App | All Rights Reserved
</footer>
