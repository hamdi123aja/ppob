<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user'] ?? null;

// 🔹 Deteksi lokasi file untuk menentukan path favicon
$currentPath = $_SERVER['PHP_SELF'];

if (
    strpos($currentPath, '/admin/') !== false ||
    strpos($currentPath, '/bank/') !== false ||
    strpos($currentPath, '/pelanggan/') !== false ||
    strpos($currentPath, '/partials/') !== false
) {
    // Halaman di dalam subfolder (admin/bank/pelanggan/partials)
    $faviconPath = '../../assets/favicon.png';
} elseif (
    strpos($currentPath, '/public/') !== false ||
    strpos($currentPath, '/login.php') !== false ||
    strpos($currentPath, '/register.php') !== false
) {
    // Halaman langsung di public (login, register, dll)
    $faviconPath = '../assets/favicon.png';
} else {
    // Default fallback
    $faviconPath = 'assets/favicon.png';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPOB APP</title>

    <!-- ✅ Favicon -->
    <link rel="icon" type="image/png" href="<?= $faviconPath; ?>">
    <link rel="shortcut icon" type="image/png" href="<?= $faviconPath; ?>">
    <link rel="apple-touch-icon" href="<?= $faviconPath; ?>">

    <!-- ✅ Bootstrap & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- ✅ DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- ✅ Custom Style -->
    <style>
        body {
            background: #f7f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Sidebar */
        .sidebar {
            height: 100vh;
            background: linear-gradient(180deg, #0d47a1, #1976d2);
            color: #fff;
            padding-top: 20px;
            position: fixed;
            left: 0;
            top: 0;
            width: 230px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li a {
            display: block;
            padding: 12px 16px;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            margin: 3px 8px;
            transition: 0.3s;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: #42a5f5;
            font-weight: bold;
        }

        /* Main content */
        .main-content {
            margin-left: 230px;
            padding: 20px;
        }

        /* Header */
        header.app-header {
            background: #1976d2;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-info span {
            font-size: 14px;
            line-height: 1.3;
        }

        .user-info .role {
            background: #42a5f5;
            color: #fff;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Footer */
        footer {
            background: #0d47a1;
            color: #fff;
            text-align: center;
            padding: 12px;
            position: fixed;
            bottom: 0;
            left: 230px;
            right: 0;
        }
    </style>
</head>

<body>
