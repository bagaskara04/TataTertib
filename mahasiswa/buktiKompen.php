<?php
include 'getMahasiswaName.php';

// Start session if needed
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 3) {
    header("Location: ../loginPage.html");
    exit();
}

// Fetch student data
$query = "
    SELECT m.nim, m.nama, m.mahasiswa_img
    FROM mahasiswa m
    JOIN users u ON m.nim = u.nim
    WHERE u.user_id = ?
";
$params = array($user_id);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$nim = $data['nim'];
$nama_mahasiswa = $data['nama'];
$mahasiswa_img = $data['mahasiswa_img'];

// Fetch complaints (pengaduan) for the student
$query_pengaduan = "
    SELECT p.pengaduan_id, pl.pelanggaran
    FROM pengaduan p
    JOIN pelanggaran pl ON p.pelanggaran_id = pl.pelanggaran_id
    WHERE p.nim = ?
";
$params_pengaduan = array($nim);
$stmt_pengaduan = sqlsrv_query($conn, $query_pengaduan, $params_pengaduan);

if ($stmt_pengaduan === false) {
    die(print_r(sqlsrv_errors(), true));
}

$pengaduan_options = [];
while ($row = sqlsrv_fetch_array($stmt_pengaduan, SQLSRV_FETCH_ASSOC)) {
    $pengaduan_options[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pengaduan_id = $_POST['pengaduan_id'];
    $status_kompen = $_POST['status_kompen'];
    $catatan_kompen = $_POST['catatan_kompen'];

    // Process file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["bukti_kompen"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate file upload
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["bukti_kompen"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }

    // Limit file types
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $uploadOk = 0;
    }

    // If file is valid
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["bukti_kompen"]["tmp_name"], $target_file)) {
            // Insert data into riwayat
            $query_riwayat = "
                INSERT INTO riwayat (nim, pengaduan_id, status_kompen, catatan_kompen, bukti_kompen)
                VALUES (?, ?, ?, ?, ?)
            ";
            $params_riwayat = array($nim, $pengaduan_id, $status_kompen, $catatan_kompen, $target_file);
            $stmt_riwayat = sqlsrv_query($conn, $query_riwayat, $params_riwayat);

            if ($stmt_riwayat === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            echo "<script>alert('Bukti kompensasi berhasil diunggah!');</script>";
        } else {
            echo "<script>alert('Maaf, terjadi kesalahan saat mengunggah file.');</script>";
        }
    } else {
        echo "<script>alert('File tidak valid atau sudah ada. Harap periksa kembali.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Form Bukti Kompensasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fonts/font-awesome.min.css">
    <link rel="stylesheet" href="../fonts/ionicons.min.css">
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
    <style>
        .box {
            margin-top: 40px;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }

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

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 8px;
            box-shadow: none;
            transition: border-color 0.3s ease-in-out;
        }

        .form-control:focus {
            border-color: #115599;
            box-shadow: 0 0 5px rgba(17, 85, 153, 0.6);
        }

        button[type="submit"] {
            background-color: #115599;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0d4789;
        }

        .user-panel img {
            border-radius: 50%;
            max-width: 50px;
        }

        /* .sidebar-menu li a {
            font-size: 16px;
        } */

        /* .sidebar-menu .header {
            font-size: 18px;
            font-weight: bold;
            color: #115599;
        } */

        .sidebar-menu li a:hover {
            background-color: #ddd;
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
                <div class="user-panel" onclick="window.location.href='Profile.php'" style="cursor: pointer;">
                    <div class="pull-left image">
                        <img src="<?php echo htmlspecialchars($mahasiswa_img); ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo htmlspecialchars($nama_mahasiswa); ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>

                <ul class="sidebar-menu">
                    <li class="header">Menu</li>
                    <li><a href="dashboardMahasiswa.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li><a href="daftarTatib.php"><i class="fa fa-calendar"></i> <span>Daftar Tata Tertib</span></a></li>
                    <li><a href="pelanggaranSaya.php"><i class="fa fa-user"></i> <span>Pelanggaran Saya</span></a></li>
                    <li><a href="notifikasi.php"><i class="fa fa-bell"></i> <span>Notifikasi</span></a></li>
                    <li class="active"><a href="buktiKompen.php"><i class="fa fa-book"></i> <span>Form Bukti Kompen</span></a></li>
                    <li><a href="kompenSaya.php"><i class="fa fa-book"></i> <span>Riwayat Kompen</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content-header">
                <h1 class="page-title">Form Bukti Kompensasi</h1>
            </section>

            <section class="content">
                <div class="box">
                    <div class="card">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nim">NIM</label>
                                <input type="text" class="form-control" id="nim" name="nim" value="<?php echo htmlspecialchars($nim); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="pengaduan_id">ID Pengaduan</label>
                                <select class="form-control" id="pengaduan_id" name="pengaduan_id" required>
                                    <?php foreach ($pengaduan_options as $pengaduan) { ?>
                                        <option value="<?php echo htmlspecialchars($pengaduan['pengaduan_id']); ?>">
                                            <?php echo htmlspecialchars($pengaduan['pelanggaran']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status_kompen">Status Kompensasi</label>
                                <select class="form-control" id="status_kompen" name="status_kompen" required>
                                    <option value="baru" selected>Baru</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="catatan_kompen">Catatan Kompensasi</label>
                                <textarea class="form-control" id="catatan_kompen" name="catatan_kompen" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="bukti_kompen">Upload Bukti</label>
                                <input type="file" class="form-control" id="bukti_kompen" name="bukti_kompen" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </form>
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

    <!-- Scripts -->
    <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="../plugins/fastclick/fastclick.js"></script>
    <script src="../dist/js/app.min.js"></script>
    <script src="../dist/js/demo.js"></script>
</body>

</html>