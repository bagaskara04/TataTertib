<?php

class Database {
    private static $instance = null; // Menyimpan instance tunggal
    private $conn;
    private $host = "localhost";
    private $database = "tatib";
    private $username = ""; // Isi dengan username database
    private $password = ""; // Isi dengan password database

    // Private constructor untuk mencegah instansiasi langsung
    private function __construct() {
        $connectionInfo = array(
            "Database" => $this->database,
            "UID" => $this->username,
            "PWD" => $this->password
        );

        $this->conn = sqlsrv_connect($this->host, $connectionInfo);

        if (!$this->conn) {
            die("Koneksi ke database gagal: " . print_r(sqlsrv_errors(), true));
        }
    }

    // Metode untuk mendapatkan instance tunggal
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Metode untuk mendapatkan koneksi
    public function getConnection() {
        return $this->conn;
    }

    // Menutup koneksi
    public function closeConnection() {
        if ($this->conn) {
            sqlsrv_close($this->conn);
            $this->conn = null;
            self::$instance = null;
        }
    }
}

// Membuat koneksi global yang dapat digunakan di seluruh file
$GLOBALS['conn'] = Database::getInstance()->getConnection();
?>
