<?php
// Koneksi database
include('../koneksi.php');

// Query untuk mengambil data dosen
$query = "SELECT * FROM dosen";
$result = sqlsrv_query($conn, $query);

if ($result) {
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['nip'] . "</td>";
        echo "<td>" . $row['nama'] . "</td>";
        echo "<td>" . $row['TTL'] . "</td>";
        echo "<td>" . $row['jenis_kelamin'] . "</td>";
        echo "<td>" . $row['jabatan'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['no_phone'] . "</td>";
        echo "<td>" . $row['alamat'] . "</td>";
        echo "<td><button class='btn btn-warning btn-sm editDosen' data-nip='" . $row['nip'] . "'>Edit</button> <button class='btn btn-danger btn-sm deleteDosen' data-nip='" . $row['nip'] . "'>Delete</button></td>";
        echo "</tr>";
    }
} else {
    echo "Error: " . print_r(sqlsrv_errors(), true);
}
?>
