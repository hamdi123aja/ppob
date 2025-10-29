<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Pelanggan.php';
require_once __DIR__ . '/../models/Tagihan.php';
require_once __DIR__ . '/../models/Pembayaran.php';

class AdminController
{
    private $userModel;
    private $pelangganModel;
    private $tagihanModel;
    private $pembayaranModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->pelangganModel = new Pelanggan();
        $this->tagihanModel = new Tagihan();
        $this->pembayaranModel = new Pembayaran();
    }

    // -------- Pelanggan ----------
    public function getAllPelanggan()
    {
        return $this->pelangganModel->all();
    }

    public function getPelangganById($id)
    {
        return $this->pelangganModel->findById($id);
    }

    public function createPelanggan($nama, $alamat, $no_meter, $username, $password = '123')
    {
        $this->userModel->create($username, $password, 'pelanggan');
        $user = $this->userModel->findByUsername($username);
        return $this->pelangganModel->create([
            'nama' => $nama,
            'alamat' => $alamat,
            'no_meter' => $no_meter,
            'user_id' => $user['id']
        ]);
    }

    public function updatePelanggan($id, $nama, $alamat, $no_meter, $username)
    {
        return $this->pelangganModel->update($id, $nama, $alamat, $no_meter, $username);
    }

    public function deletePelanggan($id)
    {
        return $this->pelangganModel->delete($id);
    }

    // -------- Tagihan ----------
    public function getAllTagihan()
    {
        return $this->tagihanModel->all();
    }

    public function verifikasiTagihan($id)
    {
        return $this->tagihanModel->verifikasi($id);
    }

    // -------- Laporan ----------
    public function getLaporanPembayaran()
    {
        return $this->pembayaranModel->allWithJoin();
    }
}
