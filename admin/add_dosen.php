<?php
// Include database connection file
include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect input data from form
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $ttl = $_POST['ttl']; // Assuming format 'YYYY-MM-DD'
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    $no_phone = $_POST['no_phone'];
    $alamat = $_POST['alamat'];

    // Start transaction
    // Start transaction
sqlsrv_begin_transaction($conn);

try {
    // Insert data into dosen table
    $query_dosen = "INSERT INTO dbo.dosen (nip, nama, TTL, jenis_kelamin, jabatan, email, no_phone, alamat)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $params_dosen = array($nip, $nama, $ttl, $jenis_kelamin, $jabatan, $email, $no_phone, $alamat);
    $stmt_dosen = sqlsrv_query($conn, $query_dosen, $params_dosen);

    if (!$stmt_dosen) {
        throw new Exception("Gagal menambahkan data dosen. Error: " . print_r(sqlsrv_errors(), true));
    }

    // Insert data into users table
    $query_users = "INSERT INTO dbo.users (username, password, level, nip)
                    VALUES (?, ?, ?, ?)";
    $params_users = array($nip, $nip, 2, $nip); // Username and password are set as nip, level = 2
    $stmt_users = sqlsrv_query($conn, $query_users, $params_users);

    if (!$stmt_users) {
        throw new Exception("Gagal menambahkan data ke tabel users. Error: " . print_r(sqlsrv_errors(), true));
    }

    // Commit transaction if both queries succeed
    sqlsrv_commit($conn);
    echo "Dosen berhasil ditambahkan.";
} catch (Exception $e) {
    // Rollback transaction if an error occurs
    sqlsrv_rollback($conn);
    echo "Terjadi kesalahan: " . $e->getMessage();
}

} else {
    echo "Metode tidak diizinkan.";
}
?>
