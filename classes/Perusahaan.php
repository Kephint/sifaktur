<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Logger.php';

class Perusahaan {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        try {
            $query = "SELECT * FROM perusahaan ORDER BY id_perusahaan DESC";
            $result = $this->conn->query($query);
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } catch (Exception $e) {
            Logger::error("Gagal mengambil data perusahaan", $e);
            return false;
        }
    }

    




    public function getById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM perusahaan WHERE id_perusahaan = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            Logger::error("Gagal mengambil data perusahaan ID: $id", $e);
            return false;
        }
    }

    


    public function insert($nama, $alamat, $telp, $fax) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO perusahaan (nama_perusahaan, alamat, telp, fax) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama, $alamat, $telp, $fax);
            return $stmt->execute();
        } catch (Exception $e) {
            Logger::error("Gagal insert perusahaan", $e);
            return false;
        }
    }

    


    public function update($id, $nama, $alamat, $telp, $fax) {
        try {
            $stmt = $this->conn->prepare("UPDATE perusahaan SET nama_perusahaan=?, alamat=?, telp=?, fax=? WHERE id_perusahaan=?");
            $stmt->bind_param("ssssi", $nama, $alamat, $telp, $fax, $id);
            return $stmt->execute();
        } catch (Exception $e) {
            Logger::error("Gagal update perusahaan ID: $id", $e);
            return false;
        }
    }

    


    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM perusahaan WHERE id_perusahaan=?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            Logger::error("Gagal delete perusahaan ID: $id", $e);
            return false;
        }
    }

    


    public function isUsedInFaktur($id) {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM faktur WHERE id_perusahaan = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            return $result['total'] > 0;
        } catch (Exception $e) {
            Logger::error("Gagal cek relasi faktur untuk perusahaan ID: $id", $e);
            return true; 
        }
    }
}
