<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pembayaran.php';
require_once __DIR__ . '/../../models/Pelanggan.php';

$pembayaranModel = new Pembayaran($db);
$pelangganModel  = new Pelanggan($db);

// ✅ Ambil id pelanggan berdasarkan user_id dari session
$user_id = $_SESSION['user']['id'];
$pelanggan = $pelangganModel->getByUserId($user_id);

if (!$pelanggan) {
    die("<div class='alert alert-danger'>❌ Data pelanggan tidak ditemukan untuk akun ini.</div>");
}

$pelanggan_id = $pelanggan['id'];

// ✅ Notifikasi
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah = $_POST['jumlah'] ?? 0;
    $bulan  = $_POST['bulan'] ?? date('n');
    $tahun  = $_POST['tahun'] ?? date('Y');
    $tanggal = date('Y-m-d H:i:s'); // realtime otomatis

    if ($jumlah > 0) {
        if ($pembayaranModel->tambah($pelanggan_id, $jumlah, $bulan, $tahun, $tanggal)) {
            $msg = '<div class="alert alert-success">✅ Pembayaran berhasil ditambahkan, menunggu verifikasi bank.</div>';
        } else {
            $msg = '<div class="alert alert-danger">❌ Gagal menambahkan pembayaran.</div>';
        }
    } else {
        $msg = '<div class="alert alert-warning">⚠ Jumlah pembayaran harus lebih dari 0.</div>';
    }
}

include '../partials/header.php';
include '../partials/sidebar.php';
?>

<div class="main-content">
    <header class="app-header">
        <h3>Pembayaran Tagihan</h3>
        <div class="user-info">
            <div>
                <span><b><?= htmlspecialchars($_SESSION['user']['username']); ?></b></span><br>
                <span class="role"><?= htmlspecialchars($_SESSION['user']['role']); ?></span>
            </div>
            <a href="../logout.php" class="btn btn-sm btn-light">Logout</a>
        </div>
    </header>

    <!-- 🔹 FORM PEMBAYARAN -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <b>Form Input Pembayaran</b>
        </div>
        <div class="card-body">
            <?= $msg; ?>
            <form method="POST">
                <!-- Jumlah pembayaran -->
                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah Pembayaran</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Masukkan jumlah (Rp)" required>
                </div>

                <!-- Bulan -->
                <div class="mb-3">
                    <label for="bulan" class="form-label">Bulan Tagihan</label>
                    <select name="bulan" id="bulan" class="form-control" required>
                        <?php
                        $namaBulan = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        foreach ($namaBulan as $num => $nama): ?>
                            <option value="<?= $num; ?>" <?= $num == date('n') ? 'selected' : ''; ?>>
                                <?= $nama; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tahun -->
                <div class="mb-3">
                    <label for="tahun" class="form-label">Tahun Tagihan</label>
                    <input type="number" name="tahun" id="tahun" class="form-control" value="<?= date('Y') ?>" required>
                </div>

                <button type="submit" class="btn btn-success">💾 Simpan Pembayaran</button>
            </form>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
