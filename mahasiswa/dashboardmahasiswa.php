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
        .logo-lg {
            font-weight: bold;
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
                <li class="active"><a href="dashboardMahasiswa.php"><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="daftarTatib.php"><i class="fa fa-book"></i> <span>Daftar Tata Tertib</span></a></li>
                <li><a href="pelanggaranSaya.php"><i class="fa fa-exclamation-triangle"></i> <span>Pelanggaran Saya</span></a></li>
                <li><a href="buktiKompen.php"><i class="fa fa-upload"></i> <span>Upload Bukti Kompen</span></a></li>
                <li><a href="ajukanBanding.php"><i class="fa fa-gavel"></i> <span>Ajukan Banding</span></a></li>
                <li><a href="notifikasi.php"><i class="fa fa-bell"></i> <span>Notifikasi</span></a></li>
                <li><a href="logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
            </ul>
        </section>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Dashboard</h1>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Selamat Datang, Alexander Pierce</h3>
                </div>
                <div class="box-body">
                    <p>Anda terdaftar di jurusan <b>Teknik Informatika</b>, angkatan <b>2023</b>.</p>
                    <p>Berikut adalah beberapa menu yang dapat Anda akses:</p>
                        <ul>
                            <li><b>Dashboard</b>: Halaman utama untuk melihat informasi umum dan status terkini.</li>
                            <li><b>Daftar Tata Tertib</b>: Melihat dan mematuhi peraturan yang berlaku.</li>
                            <li><b>Pelanggaran Saya</b>: Menyediakan informasi tentang pelanggaran yang Anda lakukan.</li>
                            <li><b>Upload Bukti Kompen</b>: Mengunggah bukti kompensasi pelanggaran.</li>
                            <li><b>Ajukan Banding</b>: Mengajukan permohonan banding terhadap keputusan pelanggaran.</li>
                            <li><b>Notifikasi</b>: Melihat pemberitahuan terbaru terkait aktivitas Anda.</li>
                        </ul>
                </div>
                <div class="box-footer">
                    <p>Semangat belajar!</p>
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
