<?php
class BaseModel
{
    protected $db;
    protected $table;

    public function __construct($db, $table = null)
    {
        $this->db = $db;
        if ($table) {
            $this->table = $table;
        }
    }

    // Ambil semua data dari tabel
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cari berdasarkan id
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Hapus berdasarkan id
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([(int)$id]);
    }
}
