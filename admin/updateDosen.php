<?php
include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $ttl = $_POST['ttl'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    $no_phone = $_POST['no_phone'];
    $alamat = $_POST['alamat'];

    $query = "UPDATE dosen SET nama = ?, TTL = ?, jenis_kelamin = ?, jabatan = ?, email = ?, no_phone = ?, alamat = ? WHERE nip = ?";
    $params = array($nama, $ttl, $jenis_kelamin, $jabatan, $email, $no_phone, $alamat, $nip);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "Data dosen berhasil diperbarui.";
    } else {
        echo "Error: " . print_r(sqlsrv_errors(), true);
    }
} else {
    echo "Metode HTTP tidak valid.";
}
?>
