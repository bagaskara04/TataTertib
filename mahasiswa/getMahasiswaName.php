<?php
session_start();

include '../koneksi.php'; // Pastikan koneksi sudah diinisialisasi di sini

if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 3) {
    header("Location: ../loginPage.html");
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan nama mahasiswa
$sql = "SELECT mahasiswa.nama 
        FROM mahasiswa 
        INNER JOIN users ON mahasiswa.nim = users.nim 
        WHERE users.user_id = ?";
$params = array($user_id);

// Jalankan query menggunakan koneksi yang sudah ada
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Default nama admin jika data tidak ditemukan
$nama_mahasiswa = "Mahasiswa";

// Ambil hasil query jika ada data
if (sqlsrv_has_rows($stmt)) {
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $nama_mahasiswa = $row['nama'];
}
?>
