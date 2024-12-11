<?php
include 'getDosenName.php';
// Periksa apakah user sudah login dan levelnya admin

// Koneksi database
require_once '../koneksi.php';  // Pastikan file koneksi ke database sudah benar

// Query untuk mengambil data kelas dan DPA terkait
$query = "
    SELECT 
        dpa.dpa_id,
        dosen.nama AS nama_dpa,
        STRING_AGG(kelas.nama_kelas, ', ') AS daftar_kelas
    FROM dpa
    JOIN dosen ON dpa.nip = dosen.nip
    LEFT JOIN kelas ON dpa.dpa_id = kelas.dpa_id
    GROUP BY dpa.dpa_id, dosen.nama
    ORDER BY daftar_kelas 
";  

// Eksekusi query
$stmt = sqlsrv_query($conn, $query);

// Cek apakah query berhasil dijalankan
if (!$stmt) {
    die("Query gagal dijalankan: " . print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar DPA</title>
    <!-- Link ke Bootstrap dan AdminLTE -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/skins/_all-skins.min.css">
    <style>
        .main-header .navbar {
            background-color: #115599 !important;
        }

        .page-title {
            font-size: 40px !important;
            font-weight: bold;
            color: #115599;
            text-align: left;
            margin-bottom: 30px;
        }

        .sidebar-menu > li > a {
            font-weight: bold; /* Ketebalan teks menu */
        }
        
        .content-wrapper {
            padding: 30px;
        }

        .box {
            margin-bottom: 30px;
        }

        .table {
            margin-top: 20px;
        }

        .dpa-container ul {
            list-style-type: none;
            padding-left: 0;
        }

        .dpa-container li {
            margin-bottom: 15px;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .dpa-container .class-name {
            font-weight: bold;
            font-size: 18px;
        }

        .dpa-container .dpa-name {
            color: #777;
            font-style: italic;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <!-- Header -->
    <header class="main-header">
        <a href="dashboardDosen.html" class="logo">
            <span class="logo-mini"><b>D</b>DS</span>
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

    <!-- Sidebar -->
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
            <li class="header">Menu </li>
            <li><a href="dashboardDosen.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                <li class="active"><a href="dpaDosen.php"><i class="fa fa-users"></i> <span>Daftar DPA</span></a></li>
                <li><a href="../laporanPelanggaran/formPelanggaran.php"><i class="fa fa-file-text-o"></i> <span> Laporan Pelanggaran</span></a></li>
                <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
            </ul>
        </section>
    </aside>    

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <!-- Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h1 class="page-title">Daftar Kelas dan DPA</h1>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Table with DPA data -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Kelas</th>
                                        <th>Nama DPA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                                        <tr>
                                        <td><?= htmlspecialchars($row['daftar_kelas'] ?? 'Tidak ada kelas') ?></td>

                                            <td><?= htmlspecialchars($row['nama_dpa']) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <p>Pastikan data yang ditampilkan sudah benar.</p>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b><a href="#">Jurusan Teknologi Informasi</a></b>
        </div>
        <strong><a href="#">Politeknik Negeri Malang</a></strong>
    </footer>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/js/app.min.js"></script>
</body>
</html>