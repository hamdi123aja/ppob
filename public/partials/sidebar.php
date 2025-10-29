<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="sidebar">
    <h4 class="text-center mb-4">⚡ PPOB APP</h4>
    <ul class="nav flex-column">

        <!-- Dashboard -->
        <li>
            <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
                🏠 Dashboard
            </a>
        </li>

        <!-- Menu untuk Admin -->
        <?php if (!empty($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
            <li>
                <a href="pelanggan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'pelanggan.php' ? 'active' : '' ?>">
                    👥 Kelola Pelanggan
                </a>
            </li>
            <li>
                <a href="tagihan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'tagihan.php' ? 'active' : '' ?>">
                    📑 Verifikasi Tagihan
                </a>
            </li>
            <li>
                <a href="laporan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'laporan.php' ? 'active' : '' ?>">
                    📊 Laporan
                </a>
            </li>

        <!-- Menu untuk Bank -->
        <?php elseif (!empty($_SESSION['user']) && $_SESSION['user']['role'] == 'bank'): ?>
            <li>
                <a href="pembayaran.php" class="<?= basename($_SERVER['PHP_SELF']) == 'pembayaran.php' ? 'active' : '' ?>">
                    💰 Pembayaran
                </a>
            </li>
            

        <!-- Menu untuk Pelanggan -->
        <?php elseif (!empty($_SESSION['user']) && $_SESSION['user']['role'] == 'pelanggan'): ?>
            <li>
                <a href="pembayaran.php" class="<?= basename($_SERVER['PHP_SELF']) == 'pembayaran.php' ? 'active' : '' ?>">
                    💳 Pembayaran
                </a>
            </li>
           
        <?php endif; ?>

        <!-- Logout -->
        <li>
            <a href="../logout.php">🚪 Logout</a>
        </li>
    </ul>
</div>
