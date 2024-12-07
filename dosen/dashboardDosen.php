<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 2) {
    header("Location: loginPage.html");
    exit();
}

include 'koneksi.php';  // Koneksi ke database

// Ambil data dosen berdasarkan session
$dosen_id = $_SESSION['user_id'];  // Asumsi session sudah memiliki user_id

// Query untuk mengambil daftar mahasiswa yang ditanggung dosen ini
$query = "SELECT mahasiswa.nama, mahasiswa.nim FROM mahasiswa
          JOIN dpa ON mahasiswa.id = dpa.mahasiswa_id
          WHERE dpa.dosen_id = '$dosen_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="styleDosen.css">
</head>
<body>
<div class="wrapper">
    <header class="main-header">
        <a href="dashboardDosen.php" class="logo">
            <span class="logo-mini"><b>D</b>DS</span>
            <span class="logo-lg">Dashboard <b>Dosen</b></span>
        </a>
    </header>
    
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Daftar Mahasiswa Pembimbing</h1>
        </section>
        <section class="content">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Mahasiswa yang Ditanggung</h3>
                </div>
                <div class="box-body">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $row['nim']; ?></td>
                                        <td><?php echo $row['nama']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Tidak ada mahasiswa yang ditanggung oleh Anda.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>