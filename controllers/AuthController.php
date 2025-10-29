<?php
// controllers/AuthController.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Pelanggan.php';

class AuthController
{
    private $db;
    private $userModel;
    private $pelangganModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->pelangganModel = new Pelanggan($db);
    }

    /**
     * Ambil user berdasarkan username
     */
    public function getUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Registrasi user baru (default role = pelanggan)
     */
    public function register($username, $password, $nama, $alamat, $no_meter)
    {
        // cek apakah username sudah ada
        $user = $this->getUserByUsername($username);
        if ($user) {
            return false; // username sudah terdaftar
        }

        // simpan user baru
        $stmt = $this->db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, 'pelanggan']);

        // ambil user yang baru saja dibuat
        $user = $this->getUserByUsername($username);
        if (!$user) {
            return false;
        }

        // simpan data pelanggan dan hubungkan dengan user_id
        $this->pelangganModel->create([
            'nama'     => $nama,
            'alamat'   => $alamat,
            'no_meter' => $no_meter,
            'user_id'  => $user['id']
        ]);

        return true;
    }

    /**
     * Login user
     */
    public function login($username, $password)
    {
        $user = $this->getUserByUsername($username);

        // cek password (sementara masih plain text)
        if ($user && $password === $user['password']) {

            // simpan user ke session
            $_SESSION['user'] = $user;

            // ✅ jika role pelanggan, ambil id dari tabel pelanggan
            if ($user['role'] === 'pelanggan') {
                $stmt = $this->db->prepare("SELECT id FROM pelanggan WHERE user_id = ?");
                $stmt->execute([$user['id']]);
                $pelanggan = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($pelanggan) {
                    $_SESSION['user']['pelanggan_id'] = $pelanggan['id'];
                }
            }

            // arahkan sesuai role
            switch ($user['role']) {
                case 'admin':
                    header("Location: admin/dashboard.php");
                    break;
                case 'bank':
                    header("Location: bank/dashboard.php");
                    break;
                case 'pelanggan':
                    header("Location: pelanggan/dashboard.php");
                    break;
                default:
                    header("Location: login.php?error=role_tidak_dikenal");
                    break;
            }
            exit;
        }

        return false;
    }

    /**
     * Logout user
     */
    public function logout()
    {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
