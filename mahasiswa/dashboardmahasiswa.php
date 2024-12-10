<?php
// daftarTatib.php

// Mulai sesi jika diperlukan
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Daftar Tata Tertib - Dashboard Mahasiswa</title>
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

        .table th, .table td {
            text-align: center;
        }

        .table {
            margin-top: 20px;
        }

        .box {
            margin-top: 20px;
        }

        .card-box {
            display: inline-block;
            width: 30%;
            margin: 15px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            cursor: pointer;
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
            top: -10px;
            right: -10px;
            font-size: 12px;
        }

        .activity-timestamp {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Header -->
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

    <!-- Sidebar -->
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
                <li><a href="dashboardMahasiswa.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                <li class="active"><a href="daftarTatib.php"><i class="fa fa-calendar"></i> <span>Daftar Tata Tertib</span></a></li>
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
            <h1>Daftar Tata Tertib</h1>
        </section>

        <!-- Konten Utama -->
        <section class="content">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Tata Tertib Mahasiswa</h3>
                </div>
                <div class="box-body" style="background-color: #ffffff;"> <!-- Latar belakang putih di sini -->
                    <!-- 3 Kotak yang bisa diklik -->
                    <div class="card-box" onclick="window.location.href='pelanggaranSaya.php'">
                        <div class="title">Total Pelanggaran</div>
                        <div>Jumlah pelanggaran yang Anda lakukan: <strong>3</strong></div>
                    </div>

                    <div class="card-box" onclick="window.location.href='notifikasi.php'">
                        <div class="title">Notifikasi</div>
                        <div>
                            Notifikasi Baru
                        </div>
                        <span class="notification-count">2</span> <!-- Notification count at the top-right corner -->
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
