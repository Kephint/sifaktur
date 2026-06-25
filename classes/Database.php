<?php




require_once __DIR__ . '/Logger.php';

class Database {
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'faktur';

    private static $instance = null;
    private $conn;

    
    private function __construct() {
        
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            $this->conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            Logger::error("Koneksi database gagal", $e);
            die("<div style='padding:20px;background:#f8d7da;color:#842029;border-radius:8px;margin:20px;font-family:sans-serif;'>
                <strong>Terjadi Kesalahan Sistem!</strong><br>
                Gagal terhubung ke database. Silakan cek file logs/error.log untuk detail debugging.
            </div>");
        }
    }

    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    
    public function getConnection() {
        return $this->conn;
    }
}
