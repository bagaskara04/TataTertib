<?php
include('../koneksi.php');

// Query mengambil data mahasiswa
$query = "SELECT m.nim, m.nama, m.ttl, m.jenis_kelamin, m.alamat, m.email, m.no_phone, 
           p.prodi_nama, k.nama_kelas, m.phone_ortu, 
           (SELECT TOP 1 tingkat FROM dbo.pelanggaran 
            WHERE pelanggaran_id = (SELECT TOP 1 pelanggaran_id FROM dbo.pengaduan WHERE nim = m.nim AND status_pengaduan = 'valid')) AS tingkat
    FROM dbo.mahasiswa m
    JOIN dbo.kelas k ON m.kelas_id = k.kelas_id
    JOIN dbo.prodi p ON p.prodi_id = m.prodi_id";
$result = sqlsrv_query($conn, $query);

// Mengecek dan menampilkan data mahasiswa
if ($result) {
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['nim'] . "</td>";
        echo "<td>" . $row['nama'] . "</td>";
        echo "<td>" . $row['ttl'] . "</td>";
        echo "<td>" . $row['jenis_kelamin'] . "</td>";
        echo "<td>" . $row['alamat'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['no_phone'] . "</td>";
        echo "<td>" . $row['prodi_nama'] . "</td>";
        echo "<td>" . $row['nama_kelas'] . "</td>";
        echo "<td>" . $row['phone_ortu'] . "</td>";
        echo "<td>" . $row['tingkat'] . "</td>";
        echo "<td><button class='btn btn-warning btn-sm editStudent' data-id='" . $row['nim'] . "'>Edit</button> <button class='btn btn-danger btn-sm deleteStudent' data-id='" . $row['nim'] . "'>Delete</button></td>";
        echo "</tr>";
    }
} else {
    echo "Error: " . print_r(sqlsrv_errors(), true);
}
?>
