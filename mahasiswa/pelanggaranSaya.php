<?php

include 'getMahasiswaName.php';

// Periksa apakah pengguna sudah login dan memiliki level mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 3 || !isset($_SESSION['nim'])) {
    header("Location: ../loginPage.html"); // Redirect ke halaman login jika sesi tidak valid
    exit();
}
$nim = $_SESSION['nim'];

$query = "
    SELECT p.pelanggaran, p.tingkat, sp.nama_sanksi, pg.bukti_pelanggaran, pg.tanggal_pengaduan, pg.status_pengaduan, pg.catatan
    FROM pengaduan pg
    JOIN pelanggaran p ON pg.pelanggaran_id = p.pelanggaran_id
    JOIN sanksi_pelanggaran sp ON p.sanksi_id = sp.sanksi_id
    WHERE pg.nim = ?
";

$params = [$nim]; // Parameter untuk NIM Mahasiswa yang disimpan di session
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die("Error executing query: " . print_r(sqlsrv_errors(), true));
}

// Query to get student's data
$query1 = "
    SELECT m.nim, m.nama, m.mahasiswa_img 
    FROM mahasiswa m
    JOIN users u ON m.nim = u.nim
    WHERE u.user_id = ?
";
$params = array($user_id);
$stmt1 = sqlsrv_query($conn, $query1, $params);

if ($stmt1 === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch the student's data
$data = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pelanggaran Saya</title>
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

        .page-title {
            font-size: 40px;
            font-weight: bold;
            color: #115599;
            text-align: left;
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <!-- Header -->
        <header class="main-header">
            <a href="dashboardMahasiswa.php" class="logo">
                <span class="logo-mini"><b>STB</b></span>
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

        <!-- Sidebar -->
        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel" onclick="window.location.href='Profile.php'" style="cursor: pointer;">
                    <div class="pull-left image">
                        <img src="<?php echo htmlspecialchars($data['mahasiswa_img']); ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo htmlspecialchars($nama_mahasiswa); ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>

                <ul class="sidebar-menu">
                    <li class="header">Menu</li>
                    <li><a href="dashboardmahasiswa.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li><a href="daftarTatib.php"><i class="fa fa-calendar"></i> <span>Daftar Tata Tertib</span></a></li>
                    <li class="active"><a href="pelanggaranSaya.php"><i class="fa fa-user"></i> <span>Pelanggaran Saya</span></a></li>
                    <li><a href="notifikasi.php"><i class="fa fa fa-bell"></i> <span>Notifikasi</span></a></li>
                    <li><a href="buktiKompen.php"><i class="fa fa-book"></i> <span>Form Bukti Kompen</span></a></li>
                    <li><a href="kompenSaya.php"><i class="fa fa-book"></i> <span>Riwayat Kompen</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content-header">
                <h1 class="page-title">Pelanggaran Saya</h1>
            </section>

            <section class="content">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Daftar Pelanggaran</h3>
                    </div>

                    <div class="box-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pelanggaran</th>
                                    <th>Tingkat</th>
                                    <th>Sanksi</th>
                                    <th>Bukti Pelanggaran</th>
                                    <th>Tanggal Pengaduan</th>
                                    <th>Status Pengaduan</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>" . htmlspecialchars($row['pelanggaran']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['tingkat']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['nama_sanksi']) . "</td>";
                                    echo "<td><a class='btn btn-primary btn-sm' href='../laporanPelanggaran/" . htmlspecialchars($row['bukti_pelanggaran']) . "' target='_blank'>Lihat Bukti</a></td>";
                                    echo "<td>" . htmlspecialchars($row['tanggal_pengaduan']->format('Y-m-d')) . "</td>";
                                    // Warna tombol berdasarkan status pengaduan
                                    $status = htmlspecialchars($row['status_pengaduan']);
                                    $statusCapitalized = ucwords(strtolower($status));
                                    $statusColor = "";

                                    switch (strtolower($status)) {
                                        case 'valid':
                                            $statusColor = 'btn-success';
                                            break;
                                        case 'tidak valid':
                                            $statusColor = 'btn-danger';
                                            break;
                                        case 'baru':
                                            $statusColor = 'btn-primary';
                                            break;
                                        case 'proses':
                                            $statusColor = 'btn-warning';
                                            break;
                                        default:
                                            $statusColor = 'btn-default';
                                    }

                                    echo "<td><button class='btn $statusColor btn-sm'>" . $statusCapitalized . "</button></td>";
                                    echo "<td>" . htmlspecialchars($row['catatan']) . "</td>";
                                    echo "</tr>";
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
                <b><a href="https://jti.polinema.ac.id/" target="_blank">Jurusan Teknologi Informasi</a></b>
            </div>
            <strong><a href="https://polinema.ac.id" target="_blank">Politeknik Negeri Malang</a></strong>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="../plugins/fastclick/fastclick.js"></script>
    <script src="../dist/js/app.min.js"></script>
</body>

</html>