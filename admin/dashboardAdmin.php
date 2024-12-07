<?php
include 'getAdminName.php';
// Periksa apakah user sudah login dan levelnya admin
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 1) {
    header("Location: ../loginPage.html"); // Redirect ke halaman login
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard Admin</title>
    <!-- Tell the browser to be responsive to screen width -->
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
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
      .main-header .navbar {
          background-color: #115599 !important; /* Mengganti warna navbar */
      }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

    <header class="main-header">
        <a href="dashboardAdmin.html" class="logo">
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
                <li class="header">MAIN NAVIGATION</li>
                <li class="active"><a href="dashboardAdmin.html"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                <li class="active"><a href="dataMahasiswa.html"><i class="fa fa-users"></i> <span>Data Mahasiswa</span></a></li>
                <li class="active"><a href="dataDosen.html"><i class="fa fa-users"></i> <span>Data Dosen</span></a></li>
                <li class="active"><a href="dataLaporan.html"><i class="fa fa-file-text-o"></i> <span>Data Laporan Pelanggaran</span></a></li>
                <!-- Tombol Logout -->
                <li class="active" style="position: absolute; bottom: 0; width: 100%;">
                    <a href="logout.php"><i class="fa fa-sign-out"></i> <span>Logout</span></a>
                </li>
            </ul>
        </section>
    </aside>


    <div class="content-wrapper">
        <section class="content-header">
            <h1>Dashboard<small>Control panel</small></h1>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Welcome ...</h3>
                </div>
                <div class="box-body">
                    <h1>Selamat Datang <?php echo htmlspecialchars($nama_admin);?></h1>
                </div>
                <div class="box-footer">
                    Footer
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b><a href="">Jurusan Teknologi Informasi</a></b>
        </div>
        <strong><a href="">Politeknik Negeri Malang</a></strong>
    </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
</body>
</html>