<?php
require_once __DIR__ . '/BaseModel.php';

class Tagihan extends BaseModel
{
    protected $table = 'tagihan';

    public function __construct($db)
    {
        parent::__construct($db, $this->table);
    }

    // ✅ Ambil semua tagihan untuk pelanggan tertentu
    public function getByPelanggan($pelanggan_id)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE pelanggan_id = :pid 
                ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pid', (int)$pelanggan_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil semua tagihan + join pelanggan (untuk admin)
    public function all()
    {
        $sql = "SELECT t.*, p.nama AS pelanggan 
                FROM {$this->table} t
                LEFT JOIN pelanggan p ON t.pelanggan_id = p.id
                ORDER BY t.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verifikasi tagihan jadi lunas
    public function verifikasi($id)
    {
        $sql = "UPDATE {$this->table} SET status='Sudah dibayar' WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
