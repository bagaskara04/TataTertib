<?php
session_start();

include '../koneksi.php'; // Pastikan koneksi sudah diinisialisasi di sini

if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 2) {
    header("Location: ../loginPage.html");
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan nama admin
$sql = "SELECT dosen.nama 
        FROM dosen 
        INNER JOIN users ON dosen.nip = users.nip 
        WHERE users.user_id = ?";
$params = array($user_id);

// Jalankan query menggunakan koneksi yang sudah ada
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Default nama admin jika data tidak ditemukan
$nama_dosen = "Dosen";

// Ambil hasil query jika ada data
if (sqlsrv_has_rows($stmt)) {
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $nama_dosen = $row['nama'];
}
?>
