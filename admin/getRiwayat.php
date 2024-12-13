<?php
include('../koneksi.php');

// Query untuk mengambil data dari tabel riwayat
$query = "SELECT riwayat_id, nim, pengaduan_id, status_kompen, catatan_kompen, bukti_kompen FROM riwayat";
$stmt = sqlsrv_query($conn, $query);

// Cek jika query berhasil
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Mengambil data dan menampilkannya dalam format HTML
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<tr>
            <td>{$row['riwayat_id']}</td>
            <td>{$row['nim']}</td>
            <td>{$row['pengaduan_id']}</td>
            <td>{$row['status_kompen']}</td>
            <td>{$row['catatan_kompen']}</td>
            <td><a href='{$row['bukti_kompen']}' target='_blank'>Lihat Bukti</a></td>
            <td>
                <button class='btn btn-danger deleteRiwayat' data-id='{$row['riwayat_id']}'>Hapus</button>
            </td>
          </tr>";
}

// Menutup koneksi
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>