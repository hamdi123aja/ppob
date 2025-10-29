<?php
session_start();
if(empty($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

include_once __DIR__ . '/partials/header.php';
include_once __DIR__ . '/partials/sidebar.php';

$user = $_SESSION['user'];
?>

<div class="main-content">
  <header class="app-header">
    <h3>Profil Saya</h3>
    <div>👤 <?= htmlspecialchars($user['username']) ?></div>
  </header>

  <div class="card p-4 shadow-sm">
    <h5 class="mb-3">Detail Akun</h5>
    <table class="table table-bordered">
      <tr>
        <th>Nama Lengkap</th>
        <td><?= htmlspecialchars($user['nama'] ?? '-') ?></td>
      </tr>
      <tr>
        <th>Username</th>
        <td><?= htmlspecialchars($user['username']) ?></td>
      </tr>
      <tr>
        <th>Role</th>
        <td><?= ucfirst($user['role']) ?></td>
      </tr>
      <?php if($user['role'] == 'pelanggan'): ?>
      <tr>
        <th>Alamat</th>
        <td><?= htmlspecialchars($user['alamat'] ?? '-') ?></td>
      </tr>
      <tr>
        <th>No. Meter</th>
        <td><?= htmlspecialchars($user['no_meter'] ?? '-') ?></td>
      </tr>
      <?php endif; ?>
    </table>
  </div>
</div>

<?php include_once __DIR__ . '/partials/footer.php'; ?>
