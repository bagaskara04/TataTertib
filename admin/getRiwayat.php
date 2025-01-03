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
    // Membuat URL yang dapat diakses untuk bukti kompensasi
    $bukti_path = "../mahasiswa/uploads/" . basename($row['bukti_kompen']);

    // Pastikan file ada di server sebelum memberikan link
    if (file_exists($bukti_path)) {
        $bukti_url = "http://localhost/TataTertib/" . basename($row['bukti_kompen']);
    } else {
        $bukti_url = "#"; // Jika file tidak ditemukan
    }

    // Tentukan kelas warna berdasarkan status_kompen
    $statusClass = '';
    switch ($row['status_kompen']) {
        case 'baru':
            $statusClass = 'btn-primary';
            break;
        case 'proses':
            $statusClass = 'btn-warning';
            break;
        case 'ditolak':
            $statusClass = 'btn-danger';
            break;
        case 'selesai':
            $statusClass = 'btn-success';
            break;
    }

    echo "<tr>
            <td>{$row['riwayat_id']}</td>
            <td>{$row['nim']}</td>
            <td>{$row['pengaduan_id']}</td>
            <td><span class='btn $statusClass'>" . ucfirst(strtolower($row['status_kompen'])) . "</span></td>
            <td>{$row['catatan_kompen']}</td>
            <td><a href='{$bukti_path}' target='_blank'>Lihat Bukti</a></td>
            <td>
                <button class='btn btn-primary validRiwayat' data-id='{$row['riwayat_id']}'>Validasi</button>
            </td>
          </tr>";
}

// Menutup koneksi
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
