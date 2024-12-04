<?php
include('../koneksi.php'); // Menghubungkan dengan database

// Query untuk mengambil data prodi
$query = "SELECT prodi_id, prodi_nama FROM prodi";
$stmt = sqlsrv_query($conn, $query);

// Cek apakah query berhasil
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Jika ada data, tampilkan sebagai option

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<option value='" . $row['prodi_id'] . "'>" . $row['prodi_nama'] . "</option>";
}

// Menutup koneksi
sqlsrv_free_stmt($stmt);
?>
