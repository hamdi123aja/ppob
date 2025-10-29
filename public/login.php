<?php
// public/login.php
session_start();

// load koneksi db dan controller
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$auth = new AuthController($db);
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // gunakan method login() yang redirect bila sukses
    $ok = $auth->login($username, $password);
    if ($ok === false) {
        $msg = '<div class="alert alert-danger">Username atau Password salah.</div>';
    }
}

// ✅ Path favicon otomatis
$faviconPath = '../assets/favicon.png';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Login PPOB</title>
  <link rel="icon" type="image/png" href="<?= $faviconPath; ?>">
  <link rel="shortcut icon" type="image/png" href="<?= $faviconPath; ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #457b9d, #1d3557);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      width: 380px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, .15);
      padding: 24px;
      background: #fff;
    }
    .btn-custom {
      background: #e63946;
      color: #fff;
      border: none;
    }
    .btn-custom:hover {
      background: #d62828;
    }
  </style>
</head>
<body>
  <div class="card">
    <h3 class="text-center mb-3">⚡ PPOB Login</h3>
    <?= $msg ?>
    <form method="post" novalidate>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" value="123" class="form-control" required>
      </div>
      <button class="btn btn-custom w-100">Login</button>
    </form>
    <p class="mt-3 text-center">
      <a href="register.php" style="text-decoration:none">Registrasi</a>
    </p>
  </div>
</body>
</html>
