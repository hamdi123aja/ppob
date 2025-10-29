<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/database.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ambil data pembayaran
$stmt = $db->query("SELECT p.id, pl.nama, pl.no_meter, p.jumlah, p.tanggal_bayar, p.status 
                    FROM pembayaran p 
                    JOIN pelanggan pl ON p.pelanggan_id = pl.id
                    ORDER BY p.tanggal_bayar DESC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// header
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Nama Pelanggan');
$sheet->setCellValue('C1', 'No. Meter');
$sheet->setCellValue('D1', 'Jumlah');
$sheet->setCellValue('E1', 'Tanggal Bayar');
$sheet->setCellValue('F1', 'Status');

$rowNum = 2;
$no = 1;
foreach ($data as $row) {
    $sheet->setCellValue('A' . $rowNum, $no);
    $sheet->setCellValue('B' . $rowNum, $row['nama']);
    $sheet->setCellValue('C' . $rowNum, $row['no_meter']);
    $sheet->setCellValue('D' . $rowNum, $row['jumlah']);
    $sheet->setCellValue('E' . $rowNum, $row['tanggal_bayar']);
    $sheet->setCellValue('F' . $rowNum, $row['status']);
    $rowNum++;
    $no++;
}

// download file Excel
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="laporan_pembayaran.xlsx"');
$writer->save('php://output');
exit;
