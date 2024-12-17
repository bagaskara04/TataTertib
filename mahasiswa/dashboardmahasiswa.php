<?php
// daftarTatib.php
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
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard Mahasiswa</title>
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
            margin-top: 20px;
        }

        .card-box {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 150px;
            /* Tinggi minimum */
            max-width: 90%;
            /* Membatasi lebar kotak */
            margin: 0 auto 20px;
            /* Tengahkan kotak secara horizontal dan tambahkan margin bawah */
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: background-color 0.3s;
            position: relative;
        }


        .card-box:hover {
            background-color: #e2e6ea;
        }

        .card-box .title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .notification-count {
            background-color: #ff5733;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 12px;
        }

        .activity-timestamp {
            background-color: #ffffff;
            /* Pastikan latar putih terlihat */
            padding: 20px;
            /* Beri ruang di dalamnya */
            margin-top: 20px;
            /* Tambahkan jarak dari elemen atas */
            border-radius: 10px;
            /* Tambahkan sedikit radius untuk estetika */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            /* Sedikit bayangan untuk pemisahan visual */
        }

        .activity-timestamp ul {
            padding-left: 20px;
            /* Beri ruang dari tepi ul */
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
                    <li class="active"><a href="dashboardmahasiswa.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li><a href="daftarTatib.php"><i class="fa fa-calendar"></i> <span>Daftar Tata Tertib</span></a></li>
                    <li><a href="pelanggaranSaya.php"><i class="fa fa-user"></i> <span>Pelanggaran Saya</span></a></li>
                    <li><a href="notifikasi.php"><i class="fa fa-book"></i> <span>Notifikasi</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Header Konten -->
            <section class="content-header">
                <h1>Dashboard</h1>
            </section>

            <!-- Konten Utama -->
            <section class="content">
                <div class="box">

                    <div class="row">
                        <!-- Kotak 1: Total Pelanggaran -->
                        <div class="col-md-6">
                            <div class="card-box d-flex align-items-center justify-content-center">
                                <div onclick="window.location.href='pelanggaranSaya.php'">
                                    <div class="title">Total Pelanggaran</div>
                                    <div>Jumlah pelanggaran yang Anda lakukan: <strong>3</strong></div>
                                </div>
                            </div>
                        </div>

                        <!-- Kotak 2: Notifikasi -->
                        <div class="col-md-6">
                            <div class="card-box d-flex align-items-center justify-content-center">
                                <div onclick="window.location.href='notifikasi.php'">
                                    <div class="title">Notifikasi</div>
                                    <div>Notifikasi Baru</div>
                                    <span class="notification-count">2</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Aktivitas Terbaru -->
                    <div class="activity-timestamp">
                        <p><strong>Aktivitas Terbaru:</strong></p>
                        <ul>
                            <li>Bukti Kompen diupload pada: <strong>5 Desember</strong></li>
                            <li>Pelanggaran dilakukan pada: <strong>3 Desember</strong></li>
                        </ul>
                    </div>
                </div>
        </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Jurusan Teknologi Informasi</b>
        </div>
        <strong>Politeknik Negeri Malang</strong>
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