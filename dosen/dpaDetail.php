<?php
// Koneksi database
require_once '../koneksi.php';

// Get dpa_id from URL
$dpa_id = $_GET['dpa_id'];

// Query to fetch students under this DPA who have violations
$query = "
    SELECT 
        mahasiswa.nama AS nama_mahasiswa, 
        pelanggaran.deskripsi AS pelanggaran
    FROM mahasiswa
    JOIN pelanggaran ON mahasiswa.mahasiswa_id = pelanggaran.mahasiswa_id
    WHERE mahasiswa.dpa_id = ?
";  

// Prepare and execute the query
$stmt = sqlsrv_query($conn, $query, array($dpa_id));

// Check if the query executed successfully
if (!$stmt) {
    die("Query failed: " . print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail DPA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Mahasiswa dengan Pelanggaran di DPA</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>Pelanggaran</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_mahasiswa']) ?></td>
                        <td><?= htmlspecialchars($row['pelanggaran']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>