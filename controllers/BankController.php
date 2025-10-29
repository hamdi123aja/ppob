<?php
// controllers/BankController.php
require_once __DIR__ . '/../models/Tagihan.php';
require_once __DIR__ . '/../models/Pembayaran.php';

class BankController {
    private $tagihanModel;
    private $pembayaranModel;

    public function __construct() {
        $this->tagihanModel = new Tagihan();
        $this->pembayaranModel = new Pembayaran();
    }

    // Ambil semua tagihan (untuk ditampilkan di dashboard bank)
    public function getAllTagihan() {
        return $this->tagihanModel->all();
    }

    // Proses bayar tagihan
    public function bayarTagihan($tagihan_id, $bank_id, $jumlah) {
        // simpan ke tabel pembayaran
        $this->pembayaranModel->create([
            'tagihan_id' => $tagihan_id,
            'bank_id' => $bank_id,
            'jumlah' => $jumlah
        ]);

        // update status tagihan jadi lunas
        $this->tagihanModel->verifikasi($tagihan_id);
        return true;
    }
}
