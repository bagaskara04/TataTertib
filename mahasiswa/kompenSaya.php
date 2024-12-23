<?php
// kompenSaya.php
include 'getMahasiswaName.php';
// Mulai sesi jika diperlukan
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 3) {
    header("Location: ../loginPage.html"); // Redirect ke halaman login  
    exit();
}

// Query to get student's data
$query = "
    SELECT m.nim, m.nama, m.mahasiswa_img 
    FROM mahasiswa m
    JOIN users u ON m.nim = u.nim
    WHERE u.user_id = ?
";
$params = array($user_id);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch the student's data
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Query untuk mendapatkan riwayat kompen mahasiswa
$query_kompen = "
SELECT r.riwayat_id, r.nim, r.pengaduan_id, r.status_kompen, r.catatan_kompen, r.bukti_kompen, p.pelanggaran
FROM riwayat r
JOIN pengaduan pg ON r.pengaduan_id = pg.pengaduan_id
JOIN pelanggaran p ON pg.pelanggaran_id = p.pelanggaran_id
WHERE r.nim = ?
";
$params_kompen = array($data['nim']);
$stmt_kompen = sqlsrv_query($conn, $query_kompen, $params_kompen);

if ($stmt_kompen === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Riwayat Kompen</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../fonts/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../fonts/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
    <style>
        .main-header .navbar {
            background-color: #115599 !important;
        }

        .table th,
        .table td {
            text-align: center;
        }

        .table {
            margin-top: 20px;
        }

        .box {
            margin-top: 10px;
        }

        .activity-timestamp {
            background-color: #ffffff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .activity-timestamp ul {
            padding-left: 20px;
        }

        .user-panel {
            display: flex;
            align-items: center;
        }

        .user-panel .pull-left.image {
            margin-right: 15px;
        }

        .user-panel .pull-left.image img {
            border-radius: 50%;
            width: 45px;
            height: 45px;
            object-fit: cover;
        }

        .page-title {
            font-size: 40px;
            font-weight: bold;
            color: #115599;
            text-align: left;
            margin-bottom: 30px;
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
                        <p><?php echo htmlspecialchars($data['nama']); ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>

                <ul class="sidebar-menu">
                    <li class="header">Menu</li>
                    <li><a href="dashboardmahasiswa.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li><a href="daftarTatib.php"><i class="fa fa-calendar"></i> <span>Daftar Tata Tertib</span></a></li>
                    <li><a href="pelanggaranSaya.php"><i class="fa fa-user"></i> <span>Pelanggaran Saya</span></a></li>
                    <li><a href="notifikasi.php"><i class="fa fa-bell"></i> <span>Notifikasi</span></a></li>
                    <li><a href="buktiKompen.php"><i class="fa fa-book"></i> <span>Form Bukti Kompen</span></a></li>
                    <li class="active"><a href="kompenSaya.php"><i class="fa fa-book"></i> <span>Riwayat Kompen</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Header Konten -->
            <section class="content-header">
                <h1 class="page-title">Riwayat Kompen</h1>
            </section>

            <!-- Konten Utama -->
            <section class="content">
                <div class="box">
                    <!-- Riwayat Kompen -->
                    <div class="activity-timestamp">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pelanggaran</th>
                                    <th>Status Kompensasi</th>
                                    <th>Catatan Kompensasi</th>
                                    <th>Bukti Kompensasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Inisialisasi nomor urut
                                $no = 1;
                                while ($row = sqlsrv_fetch_array($stmt_kompen, SQLSRV_FETCH_ASSOC)) {
                                    // Mengubah huruf pertama menjadi uppercase tanpa mengubah huruf lainnya
                                    $status_kompen = ucfirst(strtolower($row['status_kompen']));

                                    // Menentukan warna berdasarkan status
                                    $statusClass = '';
                                    if ($status_kompen == 'Baru') {
                                        $statusClass = 'label label-info';  // Warna biru untuk status 'Baru'
                                    } elseif ($status_kompen == 'Proses') {
                                        $statusClass = 'label label-warning';  // Warna kuning untuk status 'Proses'
                                    } elseif ($status_kompen == 'Ditolak') {
                                        $statusClass = 'label label-danger';  // Warna merah untuk status 'Ditolak'
                                    } elseif ($status_kompen == 'Selesai') {
                                        $statusClass = 'label label-success';  // Warna hijau untuk status 'Selesai'
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($row['pelanggaran']); ?></td>
                                        <td><span class="<?php echo $statusClass; ?>"><?php echo $status_kompen; ?></span></td>
                                        <td><?php echo htmlspecialchars($row['catatan_kompen']); ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="<?php echo htmlspecialchars($row['bukti_kompen']); ?>" target="_blank">Lihat Bukti</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
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
    <script src="../dist/js/demo.js"></script>

</body>

</html>