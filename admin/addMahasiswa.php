<?php
include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $ttl = $_POST['ttl'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $no_phone = $_POST['no_phone'];
    $prodi = $_POST['prodi_id'];  // Ganti dengan 'prodi_id'
    $kelas = $_POST['kelas_id'];  // Ganti dengan 'kelas_id'
    $phone_ortu = $_POST['phone_ortu'];

    // Query untuk insert data mahasiswa
    $query = "INSERT INTO dbo.mahasiswa (nim, nama, ttl, jenis_kelamin, alamat, email, no_phone, prodi_id, kelas_id, phone_ortu)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array($nim, $nama, $ttl, $jenis_kelamin, $alamat, $email, $no_phone, $prodi, $kelas, $phone_ortu);

    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "Mahasiswa berhasil ditambahkan.";
    } else {
        echo "Gagal menambahkan data. Error: " . print_r(sqlsrv_errors(), true);
    }
} else {
    echo "Metode tidak diizinkan.";
}

?>
