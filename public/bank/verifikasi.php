<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'bank') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pembayaran.php';

$pembayaranModel = new Pembayaran($db);

$msg = '';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        // cek dulu data pembayaran
        $stmt = $db->prepare("SELECT * FROM pembayaran WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            if ($data['status'] === 'verified') {
                $msg = "Pembayaran sudah diverifikasi sebelumnya.";
            } else {
                $pembayaranModel->verifikasi($id);
                $msg = "Pembayaran berhasil diverifikasi.";
            }
        } else {
            $msg = "Data pembayaran tidak ditemukan.";
        }
    } catch (Exception $e) {
        $msg = "Terjadi kesalahan: " . $e->getMessage();
    }
}

// redirect kembali dengan pesan
header("Location: pembayaran.php?msg=" . urlencode($msg));
exit;
