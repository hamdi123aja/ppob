<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pelanggan.php';

$pelangganModel = new Pelanggan($db);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $pelangganModel->delete($id);
}

header("Location: pelanggan.php");
exit;
