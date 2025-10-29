<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pelanggan.php';

$pelangganModel = new Pelanggan($db);
$msg = '';

if (!isset($_GET['id'])) {
    header("Location: pelanggan.php");
    exit;
}

$id = intval($_GET['id']);
$pelanggan = $pelangganModel->getById($id);

if (!$pelanggan) {
    die("Pelanggan tidak ditemukan");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = $_POST['nama'];
    $alamat   = $_POST['alamat'];
    $no_meter = $_POST['no_meter'];

    if ($pelangganModel->update($id, [
        'nama'     => $nama,
        'alamat'   => $alamat,
        'no_meter' => $no_meter
    ])) {
        $msg = '<div class="alert alert-success">Data pelanggan berhasil diperbarui</div>';
        $pelanggan = $pelangganModel->getById($id); // refresh data
    } else {
        $msg = '<div class="alert alert-danger">Gagal memperbarui data</div>';
    }
}

include '../partials/header.php';
include '../partials/sidebar.php';
?>

<div class="main-content">
    <header class="app-header">
        <h4>Edit Pelanggan</h4>
    </header>

    <div class="card shadow-sm border-0 p-4">
        <?= $msg ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($pelanggan['nama']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" required><?= htmlspecialchars($pelanggan['alamat']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">No Meter</label>
                <input type="text" name="no_meter" class="form-control" value="<?= htmlspecialchars($pelanggan['no_meter']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="pelanggan.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
