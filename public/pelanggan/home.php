<?php
require_once __DIR__ . '/../../config/database.php';
include 'header.php';
include 'sidebar.php';

$stmt = $db->query("SELECT COUNT(*) AS total FROM pelanggan");
$total_pelanggan = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
?>
<div class="main">
  <h2>Selamat Datang di PPOB App</h2>
  <p>Berikut adalah ringkasan data Anda:</p>
  <div class="card p-4 mt-3">
    <h5>Total Pelanggan: <?= $total_pelanggan; ?></h5>
  </div>
</div>
<?php include 'footer.php'; ?>
