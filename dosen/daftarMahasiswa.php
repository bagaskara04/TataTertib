<?php
include 'getDosenName.php';

// Periksa apakah user sudah login dan levelnya admin
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 2 || !isset($_SESSION['nip'])) {
    header("Location: ../loginPage.html"); // Redirect ke halaman login jika sesi tidak valid
    exit();
}

// Ambil data mahasiswa berdasarkan kelas yang diampu oleh dosen
$nama_dosen = htmlspecialchars($nama_dosen);
$nip = $_SESSION['nip']; // Asumsikan nip dosen disimpan di session

$query = "
SELECT m.nim, m.nama, p.tingkat
FROM mahasiswa m
JOIN kelas k ON m.kelas_id = k.kelas_id
JOIN pengaduan pg ON m.nim = pg.nim
JOIN pelanggaran p ON pg.pelanggaran_id = p.pelanggaran_id
WHERE k.nip = ?;
";

$params = [$nip]; // Parameter untuk NIP dosen yang disimpan di session

// Menjalankan query
$stmt = sqlsrv_query($conn, $query, $params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Query to get student's data
$query1 = "
    SELECT d.nip, d.nama, d.dosen_img 
    FROM dosen d 
    JOIN users u ON d.nip = u.nip
    WHERE u.user_id = ?
";
$params1 = array($user_id);
$stmt1 = sqlsrv_query($conn, $query1, $params1);

if ($stmt1 === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch the student's data
$data1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar Mahasiswa</title>
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
                <div class="user-panel" onclick="window.location.href='Profile.php'" style="cursor: pointer;">
                    <div class="pull-left image">
                        <img src="<?php echo htmlspecialchars($data1['dosen_img']); ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo htmlspecialchars($nama_dosen); ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <ul class="sidebar-menu">
                    <li class="header"> Menu </li>
                    <li><a href="dashboardDosen.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li><a href="dpaDosen.php"><i class="fa fa-users"></i> <span>Daftar DPA</span></a></li>
                    <li class="active"><a href="daftarMahasiswa.php"><i class="fa fa-file-text-o"></i> <span>Daftar Mahasiswa</span></a></li>
                    <li><a href="formPelanggaran.php"><i class="fa fa-file-text-o"></i> <span> Laporan Pelanggaran</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Catatan Pelanggaran</h1>
            </section>

            <section class="content">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Mahasiswa yang Anda Ampu</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Tingkat Pelanggaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nim']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                        <td><?php echo htmlspecialchars($row['tingkat']); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer">
                        JTI Polinema
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