<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pelanggan.php';

$pelangganModel = new Pelanggan($db);
$dataPelanggan = $pelangganModel->getAll();

include '../partials/header.php';
include '../partials/sidebar.php';
?>

<div class="main-content">
    <header class="app-header">
        <h4>Kelola Pelanggan</h4>
        <div class="user-info">
            <div>
                <span><b><?= htmlspecialchars($_SESSION['user']['username']); ?></b></span><br>
                <span class="role"><?= htmlspecialchars($_SESSION['user']['role']); ?></span>
            </div>
            <a href="../logout.php" class="btn btn-sm btn-light">Logout</a>
        </div>

    </header>

    <div class="card shadow-sm border-0 mt-3">

        <div class="card-body">


            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <a href="tambah_pelanggan.php" class="btn btn-sm btn-primary">+ Tambah Pelanggan</a>

                    <hr>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No Meter</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataPelanggan as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($p['nama']) ?></td>
                            <td><?= htmlspecialchars($p['alamat']) ?></td>
                            <td><?= htmlspecialchars($p['no_meter']) ?></td>
                            <td><?= htmlspecialchars($p['username']) ?></td>
                            <td>
                                <a href="edit_pelanggan.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="hapus_pelanggan.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </class=>
        </div>
    </div>

    <?php include '../partials/footer.php'; ?>