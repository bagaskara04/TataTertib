<?php
include('../koneksi.php'); 

// Query untuk mengambil semua data mahasiswa
$query = "SELECT m.nim, m.nama, m.TTL, m.jenis_kelamin, m.alamat, m.email, m.no_phone, 
           p.prodi_nama, k.nama_kelas, m.phone_ortu, m.jumlah_pelanggaran
    FROM dbo.mahasiswa m
    JOIN dbo.kelas k ON m.kelas_id = k.kelas_id
	JOIN dbo.prodi p ON p.prodi_id = m.prodi_id";  // Pastikan nama tabel dan kolom sesuai dengan yang ada di database
$result = sqlsrv_query($conn, $query);

// Mengecek dan menampilkan data mahasiswa
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    echo "<tr>
            <td>{$row['nim']}</td>
            <td>{$row['nama']}</td>
            <td>{$row['TTL']}</td>
            <td>{$row['jenis_kelamin']}</td>
            <td>{$row['alamat']}</td>
            <td>{$row['email']}</td>
            <td>{$row['no_phone']}</td>
            <td>{$row['prodi_nama']}</td>
            <td>{$row['nama_kelas']}</td>
            <td>{$row['phone_ortu']}</td>
            <td>{$row['jumlah_pelanggaran']}</td>
            <td>
            <button class='btn btn-warning editStudent' data-id='{$row['nim']}'>Edit</button>
            <button class='btn btn-danger deleteStudent' data-id='{$row['nim']}'>Delete</button>
            </td>
          </tr>";
}
?>
