<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$auth = new AuthController($db);
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nama     = trim($_POST['nama']);
    $alamat   = trim($_POST['alamat']);
    $no_meter = trim($_POST['no_meter']);

    if ($auth->register($username, $password, $nama, $alamat, $no_meter)) {
        $msg = '<div class="alert alert-success text-center">Registrasi berhasil! Silakan <a href="login.php">login</a>.</div>';
    } else {
        $msg = '<div class="alert alert-danger text-center">Registrasi gagal, username sudah digunakan!</div>';
    }
}

// ✅ Path favicon (karena register.php berada di folder public)
$faviconPath = '../assets/favicon.png';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pelanggan - PPOB</title>

    <!-- ✅ Favicon -->
    <link rel="icon" type="image/png" href="<?= $faviconPath; ?>">
    <link rel="shortcut icon" type="image/png" href="<?= $faviconPath; ?>">

    <!-- ✅ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0d47a1, #1976d2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-box {
            margin-top: 5%;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
            padding: 30px;
        }

        .register-header {
            background: #1976d2;
            color: #fff;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-register {
            background: #0d47a1;
            color: #fff;
            font-weight: bold;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-register:hover {
            background: #1565c0;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            text-decoration: none;
            color: #0d47a1;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="register-box">
                    <div class="register-header">
                        ⚡ PPOB APP - Registrasi Pelanggan
                    </div>
                    <div class="p-3">
                        <?= $msg ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">👤 Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">🔑 Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">📛 Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">🏠 Alamat</label>
                                <textarea name="alamat" class="form-control" placeholder="Masukkan alamat" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">💡 No. Meter</label>
                                <input type="text" name="no_meter" class="form-control" placeholder="Masukkan nomor meter" required>
                            </div>
                            <button type="submit" class="btn btn-register w-100">Daftar</button>
                        </form>
                        <div class="login-link">
                            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
