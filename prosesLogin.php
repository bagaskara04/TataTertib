<?php
session_start();
include 'koneksi.php';

class Login {
    private $conn;

    public function __construct() {
        global $conn; // koneksi global
        $this->conn = $conn;
    }

    // autentikasi
    public function authenticate($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $params = array($username, $password);

        // Menjalankan query
        $stmt = sqlsrv_query($this->conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_has_rows($stmt)) {
            $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

            // Menyimpan data user di session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];

            return $user['level']; // Mengembalikan level user
        } else {
            return false; // Login gagal
        }
    }
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // instansiasi Login
    $login = new Login();
    $level = $login->authenticate($username, $password);

    if ($level !== false) {
        switch ($level) {
            case 1: // Admin
                header("Location: admin/dashboardAdmin.php");
                break;
            case 2: // Dosen
                header("Location: dosen/dashboardDosen.php");
                break;
            case 3: // Mahasiswa
                header("Location: mahasiswa/dashboardMahasiswa.php");
                break;
            default:
                echo "<script>alert('Level user tidak valid!'); window.location.href = 'loginPage.html';</script>";
                break;
        }
    } else {
        // Jika username atau password salah
        echo "<script>alert('Username atau password salah!'); window.location.href = 'loginPage.html';</script>";
    }
}
?>
