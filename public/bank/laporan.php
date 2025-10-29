<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'bank') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pembayaran.php';

$pembayaranModel = new Pembayaran($db);
$data = $pembayaranModel->getAll();

// Nama file laporan
$filename = "laporan_pembayaran_" . date('Y-m-d_H-i-s') . ".xls";

// Header untuk download sebagai Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Cetak header tabel
echo "<table border='1'>";
echo "<tr>
        <th>No</th>
        <th>Nama Pelanggan</th>
        <th>No Meter</th>
        <th>Jumlah</th>
        <th>Tanggal Bayar</th>
        <th>Status</th>
      </tr>";

// Isi data
$no = 1;
foreach ($data as $row) {
    echo "<tr>
            <td>{$no}</td>
            <td>{$row['nama']}</td>
            <td>{$row['no_meter']}</td>
            <td>" . number_format($row['jumlah'], 0, ',', '.') . "</td>
            <td>{$row['tanggal_bayar']}</td>
            <td>{$row['status']}</td>
          </tr>";
    $no++;
}

echo "</table>";
exit;
