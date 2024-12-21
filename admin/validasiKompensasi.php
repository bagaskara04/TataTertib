<?php
include('../koneksi.php');

// Mengecek apakah riwayat_id dan status_kompen diterima
if (isset($_POST['riwayat_id']) && isset($_POST['status_kompen'])) {
    $riwayat_id = $_POST['riwayat_id'];
    $status_kompen = $_POST['status_kompen'];

    // Query untuk memperbarui status kompensasi
    $query = "UPDATE riwayat SET status_kompen = ? WHERE riwayat_id = ?";
    $params = array($status_kompen, $riwayat_id);
    $stmt = sqlsrv_query($conn, $query, $params);

    // Cek jika query berhasil
    if ($stmt === false) {
        echo 'error';
    } else {
        echo 'success';
    }

    // Menutup koneksi
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
} else {
    echo 'error';
}
?>
