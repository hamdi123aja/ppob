<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pembayaran.php';

$pembayaranModel = new Pembayaran($db);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $pembayaranModel->verifikasi($id);
}

header("Location: tagihan.php");
exit;
?>
