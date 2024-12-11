<?php
// notifikasi.php

session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notifikasi - Dashboard Mahasiswa</title>
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
                <li><a href="pelanggaranSaya.php"><i class="fa fa-user"></i> <span>Pelanggaran Saya</span></a></li>
                <li class="active"><a href="notifikasi.php"><i class="fa fa-bell"></i> <span>Notifikasi</span></a></li>
                <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Notifikasi</h1>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Notifikasi Terbaru</h3>
                </div>
                <div class="box-body" style="background-color: #ffffff;">
                    <!-- Daftar Notifikasi -->
                    <div class="list-group">
                        <!-- Notifikasi 1 -->
                        <a href="#" class="list-group-item">
                            <h4 class="list-group-item-heading">Peringatan Pelanggaran</h4>
                            <p class="list-group-item-text">Anda telah melakukan pelanggaran yang perlu segera diselesaikan.</p>
                            <small>2 jam yang lalu</small>
                        </a>
                    
                        <!-- Notifikasi 3 -->
                        <a href="#" class="list-group-item">
                            <h4 class="list-group-item-heading">Bukti Kompen Diterima</h4>
                            <p class="list-group-item-text">Bukti kompen yang Anda upload telah diterima dan sedang diproses.</p>
                            <small>3 hari yang lalu</small>
                        </a>
                    </div>
                    <!-- End Daftar Notifikasi -->
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
