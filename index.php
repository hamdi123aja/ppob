<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
if ($role == 'admin') {
    header("Location: admin/dashboard.php");
} elseif ($role == 'pelanggan') {
    header("Location: pelanggan/dashboard.php");
} elseif ($role == 'bank') {
    header("Location: bank/dashboard.php");
}
exit;
