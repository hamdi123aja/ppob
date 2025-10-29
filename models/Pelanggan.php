<?php
// models/Pelanggan.php
require_once __DIR__ . '/BaseModel.php';

class Pelanggan extends BaseModel
{
    protected $table = 'pelanggan';

    public function __construct($db)
    {
        parent::__construct($db);
    }

    // Tambah data pelanggan
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO pelanggan (nama, alamat, no_meter, user_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['nama'],
            $data['alamat'],
            $data['no_meter'],
            $data['user_id']
        ]);
    }

    // Hitung jumlah pelanggan
    public function countAll()
    {
        return $this->db->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();
    }

    // Cari pelanggan berdasarkan user_id
    public function findByUserId($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM pelanggan WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Ambil semua data pelanggan
    public function getAll()
    {
        $sql = "SELECT p.*, u.username 
                FROM pelanggan p 
                JOIN users u ON u.id = p.user_id
                ORDER BY p.id DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil pelanggan berdasarkan ID
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM pelanggan WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update pelanggan
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE pelanggan SET nama=?, alamat=?, no_meter=? WHERE id=?");
        return $stmt->execute([$data['nama'], $data['alamat'], $data['no_meter'], $id]);
    }

    // Hapus pelanggan
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM pelanggan WHERE id=?");
        return $stmt->execute([$id]);
    }
    public function getByUserId($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM pelanggan WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
