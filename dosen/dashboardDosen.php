<?php  
include 'getDosenName.php';  
// Periksa apakah user sudah login dan levelnya admin  
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 2) {  
    header("Location: ../loginPage.html"); // Redirect ke halaman login  
    exit();  
}  
?>  

<!DOCTYPE html>  
<html>  
<head>  
    <meta charset="utf-8">  
    <title>Dashboard Dosen</title>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/AdminLTE.min.css">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/skins/_all-skins.min.css">  
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

        .box-body h1 {  
            font-weight: bold; /* Membuat teks di dalam box-body menjadi tebal */  
        }  

        .sidebar-menu > li > a {  
            font-weight: bold; /* Ketebalan teks menu */  
        }  
    </style>  
</head>  
<body class="hold-transition skin-blue sidebar-mini">  
<!-- Site wrapper -->  
<div class="wrapper">  

    <header class="main-header">  
        <a href="dashboardDosen.php" class="logo">  
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
                    <p><?php echo htmlspecialchars($nama_dosen); ?></p>  
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>  
                </div>  
            </div>  
            <ul class="sidebar-menu">  
                <li class="header"> Menu </li>  
                <li class="active"><a href="dashboardDosen.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>  
                <li><a href="dpaDosen.php"><i class="fa fa-users"></i> <span>Daftar DPA</span></a></li>  
                <li><a href="../laporanPelanggaran/formPelanggaran.php"><i class="fa fa-exclamation-triangle"></i> <span>Pengaduan</span></a></li> <!-- Tautan Pengaduan ditambahkan -->  
                <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>  
            </ul>  
        </section>  
    </aside>  

    <div class="content-wrapper">  
        <section class="content-header">  
          
        </section>  

        <section class="content">  
            <div class="box">  
                <div class="box-header with-border">  
                    <h3 class="box-title">Welcome ...</h3>  
                </div>  
                <div class="box-body">  
                    <h1>Selamat Datang <?php echo htmlspecialchars($nama_dosen);?></h1>  
                </div>  
                <div class="box-footer">  
                    Tatib   
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