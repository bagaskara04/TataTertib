<?php
include('../koneksi.php');

// Ambil data dari form
$nim = $_POST['nim'];
$nama = $_POST['nama'];
$ttl = $_POST['ttl'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$alamat = $_POST['alamat'];
$email = $_POST['email'];
$no_phone = $_POST['no_phone'];
$prodi_id = $_POST['prodi_id'];
$kelas_id = $_POST['kelas_id'];
$phone_ortu = $_POST['phone_ortu'];

// Query untuk memperbarui data mahasiswa
$query = "UPDATE dbo.mahasiswa SET 
    nama = ?, ttl = ?, jenis_kelamin = ?, alamat = ?, email = ?, no_phone = ?, prodi_id = ?, kelas_id = ?, phone_ortu = ?
    WHERE nim = ?";

$params = array($nama, $ttl, $jenis_kelamin, $alamat, $email, $no_phone, $prodi_id, $kelas_id, $phone_ortu, $nim);

$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt) {
    echo "Data mahasiswa berhasil diperbarui.";
} else {
    echo "Terjadi kesalahan saat memperbarui data.";
}

sqlsrv_close($conn);
?>
