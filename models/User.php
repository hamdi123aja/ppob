<?php
// models/User.php
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel {
    protected $table = 'users';

    public function __construct($db) {
        parent::__construct($db);
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (username, password, role, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([
            $data['username'],
            $data['password'],
            $data['role']
        ]);
    }
}
