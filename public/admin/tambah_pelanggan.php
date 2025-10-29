<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Pelanggan.php';

$userModel = new User($db);
$pelangganModel = new Pelanggan($db);

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = $_POST['nama'];
    $alamat   = $_POST['alamat'];
    $no_meter = $_POST['no_meter'];
    $username = $_POST['username'];
    $password = "123"; // default password

    // buat user baru
    $userModel->create([
        'username' => $username,
        'password' => $password,
        'role'     => 'pelanggan'
    ]);

    // ambil user
    $user = $userModel->findByUsername($username);

    // buat pelanggan
    if ($user) {
        $pelangganModel->create([
            'nama'     => $nama,
            'alamat'   => $alamat,
            'no_meter' => $no_meter,
            'user_id'  => $user['id']
        ]);
        $msg = '<div class="alert alert-success">Pelanggan berhasil ditambahkan</div>';
    } else {
        $msg = '<div class="alert alert-danger">Gagal membuat pelanggan</div>';
    }
}

include '../partials/header.php';
include '../partials/sidebar.php';
?>

<div class="main-content">
    <header class="app-header">
        <h4>Tambah Pelanggan</h4>
    </header>

    <div class="card shadow-sm border-0 p-4">
        <?= $msg ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">No Meter</label>
                <input type="text" name="no_meter" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="pelanggan.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
