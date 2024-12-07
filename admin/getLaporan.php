<?php
// Include database connection
include('../koneksi.php');

// Query to fetch the list of violation reports
$query = "SELECT 
    p.pengaduan_id,
    d.nama AS dosen_nama,
    m.nama AS mahasiswa_nama,
    m.nim,
    pelang.pelanggaran,
    pelang.tingkat,
    sp.nama_sanksi AS sanksi,
    p.bukti_pelanggaran,
    p.tanggal_pengaduan,
    p.status_pengaduan,
    p.catatan
FROM pengaduan p
JOIN dosen d ON p.nip = d.nip
JOIN mahasiswa m ON p.nim = m.nim
JOIN pelanggaran pelang ON p.pelanggaran_id = pelang.pelanggaran_id
JOIN sanksi_pelanggaran sp ON pelang.sanksi_id = sp.sanksi_id
ORDER BY p.tanggal_pengaduan DESC;"; // Replace with your actual table name

// Execute the query
$sqlsrv_query = sqlsrv_query($conn, $query);

// Check if any rows are returned
if ($sqlsrv_query === false) {
    die(print_r(sqlsrv_errors(), true));
}

$laporan = array();
while ($row = sqlsrv_fetch_array($sqlsrv_query, SQLSRV_FETCH_ASSOC)) {
    $laporan[] = $row;
}

// Return the data as JSON
echo json_encode($laporan);

// Close the database connection
sqlsrv_close($conn);
?>
