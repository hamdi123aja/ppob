<?php
// config/database.php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'ppob';


try {
    $db = new PDO("mysql:host=localhost;dbname=ppob", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    date_default_timezone_set('Asia/Jakarta');
    $db->exec("SET time_zone = '+07:00'");
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
