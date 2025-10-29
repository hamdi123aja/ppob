<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'bank') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pelanggan.php';
require_once __DIR__ . '/../../models/Pembayaran.php';

$pembayaranModel = new Pembayaran($db);
$pelangganModel  = new Pelanggan($db);

// ambil semua data pembayaran
$data = $pembayaranModel->getAll();

// ambil daftar pelanggan untuk pilihan di form
$listPelanggan = $pelangganModel->getAll();

// pesan notifikasi
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pelanggan_id = $_POST['pelanggan_id'] ?? null;
    $jumlah       = $_POST['jumlah'] ?? 0;
    $bulan        = $_POST['bulan'] ?? date('m');
    $tahun        = $_POST['tahun'] ?? date('Y');
    $tanggal = date('Y-m-d H:i:s'); // realtime dengan jam


    if ($pembayaranModel->tambah($pelanggan_id, $jumlah, $bulan, $tahun, $tanggal)) {
        $msg = '<div class="alert alert-success">✅ Pembayaran berhasil ditambahkan!</div>';
    } else {
        $msg = '<div class="alert alert-danger">❌ Gagal menambahkan pembayaran.</div>';
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
                <span><b><?= htmlspecialchars($user['username']); ?></b></span><br>
                <span class="role"><?= htmlspecialchars($user['role']); ?></span>
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
                    <!-- Pilih pelanggan -->
                    <div class="mb-3">
                        <label for="pelanggan_id" class="form-label">Pilih Pelanggan</label>
                        <select name="pelanggan_id" id="pelanggan_id" class="form-control" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php foreach ($listPelanggan as $p): ?>
                                <option value="<?= $p['id']; ?>">
                                    <?= htmlspecialchars($p['nama']); ?> (Meter: <?= $p['no_meter']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

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
                        <select name="tahun" id="tahun" class="form-control" required>
                            <?php
                            $tahunSekarang = date('Y');
                            for ($i = $tahunSekarang - 2; $i <= $tahunSekarang + 2; $i++): ?>
                                <option value="<?= $i; ?>" <?= $i == $tahunSekarang ? 'selected' : ''; ?>>
                                    <?= $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Tanggal bayar -->
                    <div class="mb-3">
                        <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-success">💾 Simpan Pembayaran</button>
                </form>
            </div>
        </div>

 

<?php include '../partials/footer.php'; ?>
