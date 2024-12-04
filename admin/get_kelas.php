<?php
include('../koneksi.php');

if (isset($_GET['prodi_id'])) {
    $prodi_id = $_GET['prodi_id'];
    $query = "SELECT kelas_id, nama_kelas FROM kelas WHERE prodi_id = ?";
    $stmt = sqlsrv_query($conn, $query, array($prodi_id));

    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo '<option value="' . $row['kelas_id'] . '">' . $row['nama_kelas'] . '</option>';
        }
    } else {
        echo '<option value="">Gagal memuat data kelas</option>';
    }
}
?>
