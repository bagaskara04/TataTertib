<?php
session_start();
include 'koneksi.php'; // Hubungkan dengan database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Enkripsi password dengan MD5 sesuai dengan database Anda
    $hashed_password = md5($password);

    // Query untuk memeriksa username dan password
    $sql = "SELECT * FROM [user] WHERE username = '$username' AND password = '$hashed_password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Simpan data user ke session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['level'] = $user['level'];

        // Redirect berdasarkan level user
        switch ($user['level']) {
            case 1: // Admin
                header("Location: adminDashboard.php");
                break;
            case 2: // Dosen
                header("Location: dosenDashboard.php");
                break;
            case 3: // Mahasiswa
                header("Location: mahasiswaDashboard.php");
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
