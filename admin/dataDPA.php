<?php
include 'getAdminName.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Data DPA - Dashboard Admin</title>
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
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../dist/img/profile3.png" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                <p><?php echo htmlspecialchars($nama_admin);?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                <li><a href="dashboardAdmin.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                <li><a href="dataMahasiswa.php"><i class="fa fa-users"></i> <span>Data Mahasiswa</span></a></li>
                <li><a href="dataDosen.php"><i class="fa fa-users"></i> <span>Data Dosen</span></a></li>
                <li class="active"><a href="dataDPA.php"><i class="fa fa-file-text-o"></i> <span>Data DPA</span></a></li>
                <li><a href="dataLaporan.php"><i class="fa fa-file-text-o"></i> <span>Data Laporan Pelanggaran</span></a></li>
                <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Data DPA</h1>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Daftar Data DPA</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-10">
                            <input type="text" id="searchDPA" class="form-control" placeholder="Search">
                        </div>
                    </div>
                    <table id="dpaTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama DPA</th>
                                <th>NIP</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data DPA Akan Ditampilkan Di Sini -->
                        </tbody>
                    </table>
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

<!-- jQuery -->
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="../plugins/fastclick/fastclick.js"></script>
<script src="../dist/js/app.min.js"></script>
<script src="../dist/js/demo.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        function fetchDPA() {
            $.ajax({
                url: 'getDPA.php',
                method: 'GET',
                success: function (data) {
                    $('#dpaTable tbody').html(data);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat mengambil data DPA.");
                }
            });
        }

        $('#searchDPA').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#dpaTable tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        fetchDPA();
    });
</script>
</body>
</html>
