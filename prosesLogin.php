<?php
session_start();
include 'koneksi.php'; // Menghubungkan dengan file koneksi yang berisi kode di atas

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $username = $_POST['username'];  
    $password = $_POST['password'];  // Tidak melakukan enkripsi password

    // Query untuk memeriksa username dan password
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";  // Sesuaikan dengan nama tabel yang benar
    
    // Menyiapkan query
    $params = array($username, $password);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($stmt)) {
        // Mengambil data user
        $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        // Menyimpan data user ke dalam session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['level'] = $user['level'];

        // Redirect berdasarkan level user
        switch ($user['level']) {
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
