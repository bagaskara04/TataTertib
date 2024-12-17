<?php
session_start();
include '../koneksi.php'; // Koneksi database

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: loginPage.html");
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari session

// Query untuk mengambil data mahasiswa berdasarkan user_id dari tabel users
$query = "SELECT m.nim, m.nama, m.TTL, m.jenis_kelamin, m.alamat, m.email, m.no_phone, 
                 m.phone_ortu, m.jumlah_pelanggaran, m.mahasiswa_img, k.nama_kelas, p.prodi_nama
          FROM mahasiswa m
          LEFT JOIN kelas k ON m.kelas_id = k.kelas_id
          LEFT JOIN prodi p ON m.prodi_id = p.prodi_id
          JOIN users u ON m.nim = u.nim  -- Menghubungkan berdasarkan nim
          WHERE u.user_id = ?";  // Kondisi untuk user_id dari session
$params = array($user_id);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Ambil hasil query
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Format TTL jika ada
if ($data['TTL']) {
    $ttlDate = DateTime::createFromFormat('Y-m-d', $data['TTL']);
    if ($ttlDate) {
        $ttlFormatted = $ttlDate->format('Y-m-d');
    } else {
        $ttlFormatted = 'Tanggal tidak valid';
    }
} else {
    $ttlFormatted = 'Tanggal tidak tersedia';
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Profil Mahasiswa</title>
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
        <!-- Header -->
        <header class="main-header">
            <a href="dashboardMahasiswa.php" class="logo">
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

        <!-- Sidebar -->
        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo htmlspecialchars($data['mahasiswa_img']); ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo htmlspecialchars($data['nama']); ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <ul class="sidebar-menu">
                    <li class="header">Menu</li>
                    <li><a href="dashboardMahasiswa.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li><a href="daftarTatib.php"><i class="fa fa-calendar"></i> <span>Daftar Tata Tertib</span></a></li>
                    <li><a href="pelanggaranSaya.php"><i class="fa fa-user"></i> <span>Pelanggaran Saya</span></a></li>
                    <li><a href="notifikasi.php"><i class="fa fa-book"></i> <span>Notifikasi</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i> <span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Header Konten -->
            <section class="content-header">
                <h1>Profil Mahasiswa</h1>
            </section>

            <!-- Konten Profil -->
            <section class="content">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <div class="profile-header">
                            <img class="profile-user-img img-responsive img-circle" src="<?php echo htmlspecialchars($data['mahasiswa_img']); ?>" alt="User profile picture">
                            <h3 class="profile-username"><?php echo htmlspecialchars($data['nama']); ?></h3>
                            <p><?php echo htmlspecialchars($data['nim']); ?></p>
                        </div>

                        <!-- Detail Informasi -->
                        <div class="profile-details">
                            <table class="profile-table">
                                <tr>
                                    <th>TTL</th>
                                    <td><?php echo htmlspecialchars($ttlFormatted); ?></td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td><?php echo $data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td><?php echo htmlspecialchars($data['alamat']); ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo htmlspecialchars($data['email']); ?></td>
                                </tr>
                                <tr>
                                    <th>No HP</th>
                                    <td><?php echo htmlspecialchars($data['no_phone']); ?></td>
                                </tr>
                                <tr>
                                    <th>No HP Orang Tua</th>
                                    <td><?php echo htmlspecialchars($data['phone_ortu']); ?></td>
                                </tr>
                                <tr>
                                    <th>Kelas</th>
                                    <td><?php echo htmlspecialchars($data['nama_kelas']); ?></td>
                                </tr>
                                <tr>
                                    <th>Program Studi</th>
                                    <td><?php echo htmlspecialchars($data['prodi_nama']); ?></td>
                                </tr>
                            </table>
                        </div>

                        <a href="EditProfile.php" class="btn btn-primary btn-block"><b>Edit Profil</b></a>
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