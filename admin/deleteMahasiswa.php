<?php
include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $nim = $_POST['id'];
    $query = "DELETE FROM dbo.mahasiswa WHERE nim = ?";
    $params = array($nim);

    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "Mahasiswa dengan NIM $nim berhasil dihapus.";
    } else {
        echo "Gagal menghapus data. Error: " . print_r(sqlsrv_errors(), true);
    }
} else {
    echo "Tidak ada ID yang diterima atau metode salah.";
}
?>
