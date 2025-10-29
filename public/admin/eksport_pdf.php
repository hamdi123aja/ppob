<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // pastikan dompdf sudah diinstall

use Dompdf\Dompdf;
use Dompdf\Options;

// Setup dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Ambil data pembayaran + nama pelanggan
$sql = "
    SELECT 
        p.id, 
        pl.nama AS nama_pelanggan, 
        pl.no_meter, 
        p.jumlah, 
        p.bulan, 
        p.tahun, 
        p.tanggal_bayar, 
        p.status
    FROM pembayaran p
    JOIN pelanggan pl ON p.pelanggan_id = pl.id
    ORDER BY p.id DESC
";
$stmt = $db->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Siapkan isi HTML laporan
$html = '
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: center; }
        th { background-color: #1976d2; color: #fff; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Data Pembayaran PPOB</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>No. Meter</th>
                <th>Jumlah (Rp)</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Tanggal Bayar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';

if (count($data) > 0) {
    $no = 1;
    foreach ($data as $row) {
        $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . htmlspecialchars($row['nama_pelanggan']) . '</td>
            <td>' . htmlspecialchars($row['no_meter']) . '</td>
            <td>Rp ' . number_format($row['jumlah'], 0, ',', '.') . '</td>
            <td>' . htmlspecialchars($row['bulan']) . '</td>
            <td>' . htmlspecialchars($row['tahun']) . '</td>
            <td>' . htmlspecialchars($row['tanggal_bayar']) . '</td>
            <td>' . ($row['status'] == 'verified' ? 'Terverifikasi' : 'Pending') . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="8">Tidak ada data pembayaran</td></tr>';
}

$html .= '
        </tbody>
    </table>
    <br><br>
    <p style="text-align:right;">Dicetak pada: ' . date('d-m-Y H:i:s') . '</p>
</body>
</html>
';

// Load HTML ke Dompdf
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');

// Render dan output ke browser
$dompdf->render();
$dompdf->stream('laporan_pembayaran.pdf', ['Attachment' => false]);
exit;
?>
