<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/database.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

$stmt = $db->query("SELECT p.id, pl.nama, pl.no_meter, p.jumlah, p.tanggal_bayar, p.status 
                    FROM pembayaran p 
                    JOIN pelanggan pl ON p.pelanggan_id = pl.id
                    ORDER BY p.tanggal_bayar DESC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$phpWord = new PhpWord();
$section = $phpWord->addSection();

// judul
$section->addText('Laporan Pembayaran', ['bold' => true, 'size' => 16], ['alignment' => 'center']);
$section->addTextBreak(1);

// tabel
$table = $section->addTable([
    'borderSize' => 6,
    'borderColor' => '000000',
    'cellMargin' => 80,
]);

$table->addRow();
$table->addCell(500)->addText('No', ['bold' => true]);
$table->addCell(2500)->addText('Nama Pelanggan', ['bold' => true]);
$table->addCell(1500)->addText('No. Meter', ['bold' => true]);
$table->addCell(1500)->addText('Jumlah', ['bold' => true]);
$table->addCell(2500)->addText('Tanggal Bayar', ['bold' => true]);
$table->addCell(1500)->addText('Status', ['bold' => true]);

$no = 1;
foreach ($data as $row) {
    $table->addRow();
    $table->addCell(500)->addText($no++);
    $table->addCell(2500)->addText($row['nama']);
    $table->addCell(1500)->addText($row['no_meter']);
    $table->addCell(1500)->addText('Rp ' . number_format($row['jumlah'], 0, ',', '.'));
    $table->addCell(2500)->addText($row['tanggal_bayar']);
    $table->addCell(1500)->addText($row['status']);
}

// download file Word
header("Content-Description: File Transfer");
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="laporan_pembayaran.docx"');
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save("php://output");
exit;
