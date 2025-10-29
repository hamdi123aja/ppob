<?php
// controllers/PelangganController.php
require_once __DIR__ . '/../models/Pelanggan.php';
require_once __DIR__ . '/../models/Tagihan.php';

class PelangganController {
    private $pelangganModel;
    private $tagihanModel;

    public function __construct() {
        $this->pelangganModel = new Pelanggan();
        $this->tagihanModel = new Tagihan();
    }

    // Ambil profil pelanggan berdasarkan user_id
    public function getProfile($user_id) {
        return $this->pelangganModel->findByUserId($user_id);
    }

    // Ambil daftar tagihan pelanggan
    public function getTagihan($pelanggan_id) {
        return $this->tagihanModel->findByPelangganId($pelanggan_id);
    }
}
