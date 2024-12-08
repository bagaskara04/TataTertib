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
    FORMAT(p.tanggal_pengaduan, 'yyyy-MM-dd HH:mm:ss') AS tanggal_pengaduan,
    p.status_pengaduan,
    p.catatan
FROM pengaduan p
JOIN dosen d ON p.nip = d.nip
JOIN mahasiswa m ON p.nim = m.nim
JOIN pelanggaran pelang ON p.pelanggaran_id = pelang.pelanggaran_id
JOIN sanksi_pelanggaran sp ON pelang.sanksi_id = sp.sanksi_id
ORDER BY p.tanggal_pengaduan DESC;";

// Execute the query
$sqlsrv_query = sqlsrv_query($conn, $query);

// Check if the query was successful
if ($sqlsrv_query === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Prepare the data for output
$output = '';
while ($row = sqlsrv_fetch_array($sqlsrv_query, SQLSRV_FETCH_ASSOC)) {
    $output .= '
        <tr>
            <td>' . htmlspecialchars($row['pengaduan_id']) . '</td>
            <td>' . htmlspecialchars($row['dosen_nama']) . '</td>
            <td>' . htmlspecialchars($row['mahasiswa_nama']) . '</td>
            <td>' . htmlspecialchars($row['nim']) . '</td>
            <td>' . htmlspecialchars($row['pelanggaran']) . '</td>
            <td>' . htmlspecialchars($row['tingkat']) . '</td>
            <td>' . htmlspecialchars($row['sanksi']) . '</td>
            <td>' . htmlspecialchars($row['tanggal_pengaduan']) . '</td>
            <td>' . htmlspecialchars($row['status_pengaduan']) . '</td>
            <td>' . htmlspecialchars($row['catatan']) . '</td>
            <td>
                <button class="btn btn-info btn-sm detailBtn" data-id="' . htmlspecialchars($row['pengaduan_id']) . '">Detail</button>
                <button class="btn btn-warning btn-sm editBtn" data-id="' . htmlspecialchars($row['pengaduan_id']) . '" data-status="' . htmlspecialchars($row['status_pengaduan']) . '">Edit</button>
            </td>
        </tr>
    ';
}

// Return the table rows
echo $output;

// Close the database connection
sqlsrv_close($conn);
?>
