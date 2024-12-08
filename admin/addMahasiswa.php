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

    // Memulai transaksi
    sqlsrv_begin_transaction($conn);

    try {
        // Query untuk insert data mahasiswa
        $query_mahasiswa = "INSERT INTO dbo.mahasiswa (nim, nama, ttl, jenis_kelamin, alamat, email, no_phone, prodi_id, kelas_id, phone_ortu)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params_mahasiswa = array($nim, $nama, $ttl, $jenis_kelamin, $alamat, $email, $no_phone, $prodi, $kelas, $phone_ortu);
        $stmt_mahasiswa = sqlsrv_query($conn, $query_mahasiswa, $params_mahasiswa);

        if (!$stmt_mahasiswa) {
            throw new Exception("Gagal menambahkan data mahasiswa. Error: " . print_r(sqlsrv_errors(), true));
        }

        // Query untuk insert data ke tabel users
        $query_users = "INSERT INTO dbo.users (username, password, level, nim)
                        VALUES (?, ?, ?, ?)";
        $params_users = array($nim, $nim, 3, $nim); // Username dan password diatur sesuai nim, level = 3
        $stmt_users = sqlsrv_query($conn, $query_users, $params_users);

        if (!$stmt_users) {
            throw new Exception("Gagal menambahkan data ke tabel users. Error: " . print_r(sqlsrv_errors(), true));
        }

        // Commit transaksi jika semua berhasil
        sqlsrv_commit($conn);
        echo "Mahasiswa berhasil ditambahkan.";
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        sqlsrv_rollback($conn);
        echo $e->getMessage();
    }
} else {
    echo "Metode tidak diizinkan.";
}
?>
