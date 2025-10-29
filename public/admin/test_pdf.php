<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;

ob_start();
$html = "<h1>Hello PDF</h1><p>Jika ini muncul, dompdf bekerja!</p>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("test.pdf", ["Attachment" => false]);
ob_end_clean();
exit;
