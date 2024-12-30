<?php

include 'getAdminName.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: loginPage.html");
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari session

// Query untuk mengambil data staff berdasarkan user_id dari tabel users
$query = "SELECT s.staff_id, s.nama_staff, s.TTL, s.jenis_kelamin, s.jabatan, s.email, s.no_phone, s.staff_img
          FROM staff s
          JOIN users u ON s.staff_id = u.staff_id  -- Menghubungkan berdasarkan staff_id
          WHERE u.user_id = ?";  // Kondisi untuk user_id dari session
$params = array($user_id);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Ambil hasil query
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Admin</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/skins/_all-skins.min.css">
    <style>
        .main-header .navbar {
            background-color: #115599 !important;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-header img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .profile-header h3 {
            font-size: 24px;
            margin-top: 10px;
        }

        .profile-header p {
            font-size: 18px;
            color: #777;
        }

        .profile-details {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table.profile-table {
            width: 100%;
            margin-top: 20px;
        }

        table.profile-table th,
        table.profile-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #f2f2f2;
        }

        table.profile-table th {
            background-color: #f8f8f8;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #115599;
            border-color: #115599;
        }

        .user-panel {
            display: flex;
            align-items: center;
        }

        .user-panel .pull-left.image {
            margin-right: 15px;
        }

        .user-panel .pull-left.image img {
            border-radius: 50%;
            width: 45px;
            height: 45px;
            object-fit: cover;
        }

        .page-title {
            font-size: 40px;
            font-weight: bold;
            color: #115599;
            text-align: left;
            margin-bottom: 30px;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <a href="dashboardAdmin.php" class="logo">
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

        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel" onclick="window.location.href='../admin/Profile.php'" style="cursor: pointer;">
                    <div class="pull-left image">
                        <img src="<?php echo htmlspecialchars($data['staff_img']); ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo htmlspecialchars($data['nama_staff']); ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <ul class="sidebar-menu">
                    <li class="header">Menu</li>
                    <li><a href="dashboardAdmin.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li><a href="dataMahasiswa.php"><i class="fa fa-users"></i> <span>Data Mahasiswa</span></a></li>
                    <li><a href="dataDosen.php"><i class="fa fa-users"></i> <span>Data Dosen</span></a></li>
                    <li><a href="dataDPA.php"><i class="fa fa-file-text-o"></i> <span>Data DPA</span></a></li>
                    <li><a href="dataLaporan.php"><i class="fa fa-file-text-o"></i> <span>Data Laporan Pelanggaran</span></a></li>
                    <li><a href="dataKompensasi.php"><i class="fa fa-file-text-o"></i> <span>Data Kompensasi</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Profil Admin</h1>
            </section>

            <section class="content">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <div class="profile-header">
                            <img class="profile-user-img img-responsive img-circle" src="<?php echo htmlspecialchars($data['staff_img']); ?>" alt="User profile picture">
                            <h3 class="profile-username"><?php echo htmlspecialchars($data['nama_staff']); ?></h3>
                            <p><?php echo htmlspecialchars($data['staff_id']); ?></p>
                        </div>

                        <div class="profile-details">
                            <table class="profile-table">
                                <tr>
                                    <th>TTL</th>
                                    <td><?php echo htmlspecialchars($data['TTL']); ?></td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td><?php echo $data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                                </tr>
                                <tr>
                                    <th>Jabatan</th>
                                    <td><?php echo htmlspecialchars($data['jabatan']); ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo htmlspecialchars($data['email']); ?></td>
                                </tr>
                                <tr>
                                    <th>No HP</th>
                                    <td><?php echo htmlspecialchars($data['no_phone']); ?></td>
                                </tr>
                            </table>
                        </div>

                        <a href="EditProfile.php" class="btn btn-primary btn-block"><b>Edit Profile</b></a>
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
