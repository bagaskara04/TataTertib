<?php

include('../koneksi.php');


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
    p.catatan,
    p.bukti_pelanggaran
FROM pengaduan p
JOIN dosen d ON p.nip = d.nip
JOIN mahasiswa m ON p.nim = m.nim
JOIN pelanggaran pelang ON p.pelanggaran_id = pelang.pelanggaran_id
JOIN sanksi_pelanggaran sp ON pelang.sanksi_id = sp.sanksi_id
ORDER BY p.tanggal_pengaduan DESC;";


$sqlsrv_query = sqlsrv_query($conn, $query);


if ($sqlsrv_query === false) {
    die(print_r(sqlsrv_errors(), true));
}


$output = '';
$baseUrl = 'http://localhost/TataTertib/laporanPelanggaran/';
while ($row = sqlsrv_fetch_array($sqlsrv_query, SQLSRV_FETCH_ASSOC)) {
    $buktiLink = $row['bukti_pelanggaran'] 
        ? '<a href="' . $baseUrl . htmlspecialchars($row['bukti_pelanggaran']) . '" target="_blank" btn-primary>Lihat Bukti</a>' 
        : 'Tidak Tersedia';

    $output .= '
        <tr>
            <td>' . htmlspecialchars($row['pengaduan_id']) . '</td>
            <td>' . htmlspecialchars($row['dosen_nama']) . '</td>
            <td>' . htmlspecialchars($row['mahasiswa_nama']) . '</td>
            <td>' . htmlspecialchars($row['nim']) . '</td>
            <td class="truncate">' . htmlspecialchars($row['pelanggaran']) . '</td>
            <td>' . htmlspecialchars($row['tingkat']) . '</td>
            <td class="truncate">' . htmlspecialchars($row['sanksi']) . '</td>
            <td>' . htmlspecialchars($row['tanggal_pengaduan']) . '</td>
            <td>' . htmlspecialchars($row['status_pengaduan']) . '</td>
            <td>' . htmlspecialchars($row['catatan']) . '</td>
            <td>' . $buktiLink . '</td>
            <td>
                <button class="btn btn-info btn-sm detailBtn" data-id="' . htmlspecialchars($row['pengaduan_id']) . '">Detail</button>
                <button class="btn btn-success btn-sm editBtn" data-id="' . htmlspecialchars($row['pengaduan_id']) . '" data-status="' . htmlspecialchars($row['status_pengaduan']) . '">Validasi</button>
            </td>
        </tr>
    ';
}


echo $output;


sqlsrv_close($conn);
?>
