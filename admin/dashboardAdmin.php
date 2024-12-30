<?php
include 'getAdminName.php';

if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 1) {
    header("Location: ../loginPage.html");
    exit();
}

// Query untuk menghitung jumlah laporan
$sql_laporan = "SELECT COUNT(*) AS jumlah_laporan FROM pengaduan";
$stmt_laporan = sqlsrv_query($conn, $sql_laporan);
$jumlah_laporan = 0;

if ($stmt_laporan) {
    $row = sqlsrv_fetch_array($stmt_laporan, SQLSRV_FETCH_ASSOC);
    $jumlah_laporan = $row['jumlah_laporan'];
}

// Query untuk menghitung jumlah kompensasi
$sql_kompensasi = "SELECT COUNT(*) AS jumlah_kompensasi FROM riwayat";
$stmt_kompensasi = sqlsrv_query($conn, $sql_kompensasi);
$jumlah_kompensasi = 0;

if ($stmt_kompensasi) {
    $row = sqlsrv_fetch_array($stmt_kompensasi, SQLSRV_FETCH_ASSOC);
    $jumlah_kompensasi = $row['jumlah_kompensasi'];
}

// Query to get student's data
$query = "
    SELECT s.staff_id, s.nama_staff, s.staff_img 
    FROM staff s
    JOIN users u ON s.staff_id = u.staff_id
    WHERE u.user_id = ?
";
$params = array($user_id);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch the student's data
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard Admin</title>
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

        .page-title {
            font-size: 40px;
            font-weight: bold;
            color: #115599;
            text-align: left;
            margin-bottom: 30px;
        }

        .user-panel {
            display: flex;
            align-items: center;
        }

        .user-panel .pull-left.image {
            margin-right: 15px;
            /* Space between image and name */
        }

        .user-panel .pull-left.image img {
            border-radius: 50%;
            /* Makes the image circular */
            width: 45px;
            /* Adjust the size of the profile image */
            height: 45px;
            /* Adjust the size of the profile image */
            object-fit: cover;
            /* Ensures the image fits well inside the circle */
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <a href="dashboardAdmin.php" class="logo">
                <span class="logo-mini"><b>S</b>TB</span>
                <span class="logo-lg">SI<b>TATIB</b></span>
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
                <div class="user-panel" onclick="window.location.href='Profile.php'" style="cursor: pointer;">
                    <div class="pull-left image">
                        <img src="<?php echo htmlspecialchars($data['staff_img']); ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo htmlspecialchars($nama_admin); ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <ul class="sidebar-menu">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="active"><a href="dashboardAdmin.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li><a href="dataMahasiswa.php"><i class="fa fa-users"></i> <span>Data Mahasiswa</span></a></li>
                    <li><a href="dataDosen.php"><i class="fa fa-users"></i> <span>Data Dosen</span></a></li>
                    <li><a href="dataDPA.php"><i class="fa fa-file-text-o"></i> <span>Data DPA</span></a></li>
                    <li><a href="dataLaporan.php"><i class="fa fa-file-text-o"></i> <span>Data Laporan Pelanggaran</span></a></li>
                    <li><a href="dataKompensasi.php"><i class="fa fa-file-text-o"></i> <span>Data Kompensasi</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Dashboard Admin</h1>
            </section>

            <section class="content">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Welcome ...</h3>
                    </div>
                    <div class="box-body">
                        <h1>Selamat Datang <?php echo htmlspecialchars($nama_admin); ?></h1>
                    </div>
                    <div class="box-footer">
                        JTI Polinema
                    </div>
                </div>
                <div class="row">
                    <!-- Card Box for Data Laporan -->
                    <div class="col-md-6">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Data Laporan Pelanggaran</h3>
                            </div>
                            <div class="box-body">
                                <p>Jumlah laporan pelanggaran: <strong><?php echo $jumlah_laporan; ?></strong></p>
                                <a href="dataLaporan.php" class="btn btn-primary">Lihat Detail</a>
                            </div>
                        </div>
                    </div>

                    <!-- Card Box for Data Kompensasi -->
                    <div class="col-md-6">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title">Data Kompensasi</h3>
                            </div>
                            <div class="box-body">
                                <p>Jumlah data kompensasi: <strong><?php echo $jumlah_kompensasi; ?></strong></p>
                                <a href="dataKompensasi.php" class="btn btn-success">Lihat Detail</a>
                            </div>
                        </div>
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

    <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="../plugins/fastclick/fastclick.js"></script>
    <script src="../dist/js/app.min.js"></script>
    <script src="../dist/js/demo.js"></script>
</body>

</html>