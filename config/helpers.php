<?php
/**
 * Fungsi untuk mendapatkan path favicon secara otomatis
 */
function getFaviconPath() {
    $path = $_SERVER['PHP_SELF'];

    if (strpos($path, '/admin/') !== false ||
        strpos($path, '/bank/') !== false ||
        strpos($path, '/pelanggan/') !== false) {
        return '../../assets/favicon.png';
    } elseif (strpos($path, '/public/') !== false) {
        return '../assets/favicon.png';
    } else {
        return 'assets/favicon.png';
    }
}
