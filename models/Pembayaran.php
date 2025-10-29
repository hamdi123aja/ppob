<?php
require_once 'BaseModel.php';

class Pembayaran extends BaseModel
{
    protected $table = 'pembayaran';

    public function __construct($db)
    {
        parent::__construct($db, 'pembayaran');
        $this->db = $db; // simpan koneksi db
    }

    /** 
     * Ambil semua pembayaran (untuk admin/bank) 
     */
    public function getAll()
    {
        $sql = "SELECT pb.*, pl.nama, pl.no_meter 
                FROM pembayaran pb
                JOIN pelanggan pl ON pl.id = pb.pelanggan_id
                ORDER BY pb.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // alias
    public function all()
    {
        return $this->getAll();
    }

    /** 
     * Ambil pembayaran berdasarkan pelanggan 
     */
    public function getByPelanggan($pelanggan_id)
    {
        $query = "SELECT id, jumlah, bulan, tahun, tanggal_bayar, status 
                  FROM " . $this->table . " 
                  WHERE pelanggan_id = :pelanggan_id 
                  ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":pelanggan_id", (int)$pelanggan_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** 
     * Hitung total transaksi pelanggan 
     */
    public function countByPelanggan($pelanggan_id)
    {
        $sql = "SELECT COUNT(*) FROM " . $this->table . " WHERE pelanggan_id = :pid";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":pid", (int)$pelanggan_id, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /** 
     * Hitung transaksi pending pelanggan 
     */
    public function countPendingByPelanggan($pelanggan_id)
    {
        $sql = "SELECT COUNT(*) FROM " . $this->table . " 
                WHERE pelanggan_id = :pid AND status='pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":pid", (int)$pelanggan_id, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /** 
     * Hitung total pembayaran terverifikasi pelanggan 
     */
    public function sumByPelanggan($pelanggan_id)
    {
        $sql = "SELECT COALESCE(SUM(jumlah),0) 
                FROM " . $this->table . " 
                WHERE pelanggan_id = :pid AND status='verified'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":pid", (int)$pelanggan_id, PDO::PARAM_INT);
        $stmt->execute();
        return (float)$stmt->fetchColumn();
    }

    /** 
     * Tambah pembayaran baru 
     */
    public function tambah($pelanggan_id, $jumlah, $bulan, $tahun, $tanggal)
    {
        try {
            if (!$tanggal) {
                $tanggal = date('Y-m-d H:i:s');
            }

            $sql = "INSERT INTO pembayaran 
                    (pelanggan_id, jumlah, bulan, tahun, tanggal_bayar, status)
                    VALUES (?, ?, ?, ?, ?, 'pending')";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                (int)$pelanggan_id,
                $jumlah,
                $bulan,
                $tahun,
                $tanggal
            ]);
        } catch (Exception $e) {
            error_log("Error tambah pembayaran: " . $e->getMessage());
            return false;
        }
    }

    /** 
     * Verifikasi pembayaran 
     */
    public function verifikasi($id)
    {
        $sql = "UPDATE pembayaran SET status = 'verified' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int)$id]);

        $pembayaran = $this->getById($id);

        if ($pembayaran) {
            $sql2 = "UPDATE tagihan 
                     SET status = 'Sudah dibayar', tanggal_bayar = ?
                     WHERE pelanggan_id = ? AND bulan = ? AND tahun = ?";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute([
                $pembayaran['tanggal_bayar'],
                $pembayaran['pelanggan_id'],
                $pembayaran['bulan'],
                $pembayaran['tahun']
            ]);
        }

        return true;
    }

    /** 
     * Ambil pembayaran by id 
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM pembayaran WHERE id = ?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** 
     * Hitung semua pembayaran (global) 
     */
    public function countAll()
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM pembayaran")->fetchColumn();
    }

    /** 
     * Jumlah total pembayaran yang sudah verified (global) 
     */
    public function sumAll()
    {
        $val = $this->db->query("SELECT COALESCE(SUM(jumlah),0) FROM pembayaran WHERE status='verified'")->fetchColumn();
        return (float)$val;
    }

    /** 
     * Ambil transaksi terbaru 
     */
    public function getLatest($limit = 5)
    {
        $sql = "SELECT p.id, p.jumlah, p.tanggal_bayar, p.status, pel.nama
                FROM pembayaran p
                JOIN pelanggan pel ON pel.id = p.pelanggan_id
                ORDER BY p.id DESC
                LIMIT :lim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** 
     * Summary bulanan 
     */
    public function getMonthlySummary()
    {
        $sql = "SELECT 
                    DATE_FORMAT(tanggal_bayar, '%M %Y') AS bulan,
                    SUM(jumlah) AS total,
                    YEAR(tanggal_bayar) AS y,
                    MONTH(tanggal_bayar) AS m
                FROM pembayaran
                WHERE status = 'verified'
                GROUP BY y, m
                ORDER BY y ASC, m ASC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$r) {
            $r['total'] = (float)$r['total'];
        }
        return $rows;
    }
}
