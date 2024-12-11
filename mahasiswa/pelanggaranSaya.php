<?php
// pelanggaranSaya.php

session_start();

// Koneksi database
require_once '../koneksi.php'; // Pastikan file koneksi database benar

// Ambil data pelanggaran mahasiswa berdasarkan ID pengguna (asumsikan session berisi ID pengguna)
$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;

// Query untuk mengambil data pelanggaran mahasiswa
$query = "
    SELECT riwayat_id, nim, pengaduan_id, status_kompen, catatan_kompen, bukti_kompen
    FROM riwayat 
    WHERE nim = ?
";

$params = [$userId];
$stmt = sqlsrv_query($conn, $query, $params);

if (!$stmt) {
    die("Query gagal dijalankan: " . print_r(sqlsrv_errors(), true));
}

// Proses upload bukti kompen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idPelanggaran']) && isset($_FILES['bukti'])) {
    $idPelanggaran = $_POST['idPelanggaran'];
    $bukti = $_FILES['bukti'];

    // Direktori tujuan untuk menyimpan file
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($bukti['name']);
    $targetFilePath = $uploadDir . $fileName;

    // Validasi file dan pindahkan ke direktori tujuan
    if (move_uploaded_file($bukti['tmp_name'], $targetFilePath)) {
        // Simpan informasi file ke database
        $updateQuery = "UPDATE riwayat SET bukti_kompen = ? WHERE id = ? AND user_id = ?";
        $updateParams = [$fileName, $idPelanggaran, $userId];

        $updateStmt = sqlsrv_query($conn, $updateQuery, $updateParams);
        if ($updateStmt) {
            echo "<script>alert('Bukti kompen berhasil diupload.'); window.location.href='pelanggaranSaya.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan bukti kompen ke database.');</script>";
        }
    } else {
        echo "<script>alert('Gagal mengupload file.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pelanggaran Saya - Dashboard Mahasiswa</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fonts/font-awesome.min.css">
    <link rel="stylesheet" href="../fonts/ionicons.min.css">
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
    <style>
        .main-header .navbar {
            background-color: #115599 !important;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <a href="dashboardMahasiswa.php" class="logo">
            <span class="logo-mini"><b>MHS</b></span>
            <span class="logo-lg">SITATIB</span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>Alexander Pierce</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="header">Menu</li>
                <li><a href="dashboardMahasiswa.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                <li><a href="daftarTatib.php"><i class="fa fa-calendar"></i> <span>Daftar Tata Tertib</span></a></li>
                <li class="active"><a href="dashboardMahasiswa.php"><i class="fa fa-user"></i> <span>Pelanggaran Saya</span></a></li>
                <li><a href="notifikasi.php"><i class="fa fa-book"></i> <span>Notifikasi</span></a></li>
                <li><a href="logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Pelanggaran Saya</h1>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Daftar Pelanggaran yang Anda Lakukan</h3>
                </div>
                <div class="box-body" style="background-color: #ffffff;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Deskripsi Pelanggaran</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Ajukan Banding</th>
                                <th>Upload Bukti Kompen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $status = "Kosong";
                                if ($row['status_band'] === 'proses') {
                                    $status = "Banding Sedang Diproses";
                                } elseif ($row['status_band'] === 'ditolak') {
                                    $status = "Banding Ditolak";
                                } elseif ($row['status_band'] === 'disetujui') {
                                    $status = "Banding Disetujui";
                                } elseif ($row['status_kompen'] === 'selesai') {
                                    $status = "Selesai";
                                }

                                echo "
                                    <tr>
                                        <td>{$no}</td>
                                        <td>{$row['deskripsi']}</td>
                                        <td>{$row['tanggal']}</td>
                                        <td>{$status}</td>
                                        <td>
                                            <form action='ajukanBanding.php' method='GET' style='display:inline;'>
                                                <input type='hidden' name='idPelanggaran' value='{$row['id']}'>
                                                <button type='submit' class='btn btn-primary btn-sm'>Ajukan Banding</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action='' method='POST' enctype='multipart/form-data' style='display:inline;'>
                                                <input type='hidden' name='idPelanggaran' value='{$row['id']}'>
                                                <input type='file' name='bukti' required>
                                                <button type='submit' class='btn btn-success btn-sm'>Upload</button>
                                            </form>
                                        </td>
                                    </tr>
                                ";
                                $no++;
                            }

                            if ($no === 1) {
                                echo "<tr><td colspan='6'>Tidak ada data pelanggaran.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Jurusan Teknologi Informasi</b>
        </div>
        <strong>Politeknik Negeri Malang</strong>
    </footer>

</div>

<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="../plugins/fastclick/fastclick.js"></script>
<script src="../dist/js/app.min.js"></script>
<script src="../dist/js/demo.js"></script>
</body>
</html>