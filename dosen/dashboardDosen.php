<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 2) {
    header("Location: loginPage.html");
    exit();
}

include 'koneksi.php';  // Include your connection file

// Ambil data kelas dan DPA dari database
$query = "SELECT kelas.nama_kelas, dosen.nama AS nama_dpa 
          FROM kelas 
          JOIN dosen ON kelas.dpa_id = dosen.dosen_id";

// Execute query using sqlsrv_query
$result = sqlsrv_query($conn, $query);

// Check if query executed successfully
if (!$result) {
    echo "Error in query execution.<br />";
    die(print_r(sqlsrv_errors(), true));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar DPA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="styleDosen.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <a href="dashboardDosen.php" class="logo">
            <span class="logo-mini"><b>D</b>DS</span>
            <span class="logo-lg">Dashboard <b>Dosen</b></span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="https://via.placeholder.com/160" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>Dosen</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                <li><a href="dashboardDosen.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                <li class="active"><a href="daftarDPA.php"><i class="fa fa-users"></i> <span>Daftar DPA</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Daftar DPA</h1>
            <small>Informasi kelas dan DPA</small>
        </section>
        <section class="content">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Daftar Kelas dan DPA</h3>
                </div>
                <div class="box-body">
                    <div class="dpa-container">
                        <ul>
                            <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)): ?>
                                <li>
                                    <span class="class-name"><?= htmlspecialchars($row['nama_kelas']) ?></span>
                                    <span class="dpa-name">DPA: <?= htmlspecialchars($row['nama_dpa']) ?></span>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
                <div class="box-footer">
                    <small>*Informasi ini diperbarui secara berkala.</small>
                </div>
            </div>
        </section>
    </div>

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