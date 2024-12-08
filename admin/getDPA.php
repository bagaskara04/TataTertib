<?php
// Sertakan file koneksi ke database
include('../koneksi.php');

// Query untuk mengambil data DPA, NIP, dan kelas
$sql = "
    SELECT 
        dpa.dpa_id,
        dosen.nama AS nama_dpa,
        dosen.nip,
        STRING_AGG(kelas.nama_kelas, ', ') AS daftar_kelas
    FROM dpa
    JOIN dosen ON dpa.nip = dosen.nip
    LEFT JOIN kelas ON dpa.dpa_id = kelas.dpa_id
    GROUP BY dpa.dpa_id, dosen.nama, dosen.nip
";

$stmt = sqlsrv_query($conn, $sql);

// Cek apakah query berhasil
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Output data dalam format HTML tabel
if (sqlsrv_has_rows($stmt)) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo "<tr>
            <td>{$row['nama_dpa']}</td>
            <td>{$row['nip']}</td>
            <td>{$row['daftar_kelas']}</td>
            <td>
                <button class='btn btn-info btn-sm detailBtn' data-id='{$row['dpa_id']}'>Detail</button>
                <button class='btn btn-danger btn-sm deleteBtn' data-id='{$row['dpa_id']}'>Hapus</button>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>Tidak ada data DPA ditemukan.</td></tr>";
}

// Tutup koneksi
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
