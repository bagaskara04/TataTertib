<?php
session_start();

include '../koneksi.php';

// Periksa apakah user sudah login dan levelnya DPA
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 3) {
    header("Location: ../loginPage.html"); // Redirect ke halaman login
    exit();
}

// Ambil ID DPA dari session
$dpa_id = $_SESSION['user_id'];

// Query untuk mengambil daftar kelas yang dipegang oleh DPA
$query_kelas = "SELECT k.id AS kelas_id, k.nama_kelas 
                FROM kelas k 
                WHERE k.dpa_id = ?";
$stmt_kelas = $conn->prepare($query_kelas);
$stmt_kelas->bind_param("i", $dpa_id);
$stmt_kelas->execute();
$result_kelas = $stmt_kelas->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard DPA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Dashboard DPA</h1>
    <p>Berikut adalah daftar kelas yang Anda pegang beserta nama-nama mahasiswa di dalamnya:</p>

    <?php while ($kelas = $result_kelas->fetch_assoc()): ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Kelas: <?php echo htmlspecialchars($kelas['nama_kelas']); ?></h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query untuk mengambil daftar mahasiswa di kelas
                        $query_mahasiswa = "SELECT m.nama_mahasiswa, m.nim 
                                            FROM mahasiswa m 
                                            WHERE m.kelas_id = ?";
                        $stmt_mahasiswa = $conn->prepare($query_mahasiswa);
                        $stmt_mahasiswa->bind_param("i", $kelas['kelas_id']);
                        $stmt_mahasiswa->execute();
                        $result_mahasiswa = $stmt_mahasiswa->get_result();

                        while ($mahasiswa = $result_mahasiswa->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($mahasiswa['nama_mahasiswa']); ?></td>
                                <td><?php echo htmlspecialchars($mahasiswa['nim']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>