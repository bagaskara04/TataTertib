<?php
// daftarTatib.php
include 'getMahasiswaName.php';
// Koneksi database
require_once '../koneksi.php'; // Pastikan file koneksi database sudah benar

// Query untuk mengambil data pelanggaran
$query = "
    SELECT tingkat, pelanggaran 
    FROM pelanggaran 
    ORDER BY tingkat ASC
";

// Eksekusi query
$stmt = sqlsrv_query($conn, $query);

// Cek apakah query berhasil dijalankan
if (!$stmt) {
    die("Query gagal dijalankan: " . print_r(sqlsrv_errors(), true));
}
// Query to get student's data
$query = "
    SELECT m.nim, m.nama, m.mahasiswa_img 
    FROM mahasiswa m
    JOIN users u ON m.nim = u.nim
    WHERE u.user_id = ?
";
$params = array($user_id);
$stmt1 = sqlsrv_query($conn, $query, $params);

if ($stmt1 === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch the student's data
$data = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);

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

        .content-wrapper {
            padding: 20px;
        }

        .panel {
            margin-bottom: 15px;
        }

        .panel-heading {
            background-color: #f7f7f7;
            font-weight: bold;
            cursor: pointer;
        }

        .panel-body {
            background-color: #ffffff;
            padding: 15px;
            border: 1px solid #ddd;
            display: none;
            /* Initially hidden */
        }

        .panel .icon {
            float: right;
            transition: transform 0.3s;
        }

        .panel.open .icon {
            transform: rotate(90deg);
            /* Rotate the icon when open */
        }

        .level-1 {
            border-left: 5px solid #ff5733;
        }

        .level-2 {
            border-left: 5px solid #ffcc00;
        }

        .level-3 {
            border-left: 5px solid #ffeb3b;
        }

        .level-4 {
            border-left: 5px solid #fff3cd;
        }

        .level-5 {
            border-left: 5px solid #d1e7dd;
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
                <div class="user-panel" onclick="window.location.href='Profile.php'" style="cursor: pointer;">
                    <div class="pull-left image">
                        <img src="<?php echo htmlspecialchars($data['mahasiswa_img']); ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo htmlspecialchars($nama_mahasiswa); ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <ul class="sidebar-menu">
                    <li class="header">Menu</li>
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
                    <div class="box-body" style="background-color: #ffffff;">
                        <!-- Klasifikasi Tingkat Pelanggaran -->
                        <h3>Klasifikasi Tingkat Pelanggaran</h3>

                        <?php
                        // Menampilkan data dari database berdasarkan tingkat
                        $current_tingkat = null;

                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            // Jika tingkat berubah, buat header baru
                            if ($current_tingkat !== $row['tingkat']) {
                                // Tutup panel sebelumnya jika ada
                                if ($current_tingkat !== null) {
                                    echo "</div></div>";
                                }

                                // Perbarui tingkat saat ini dan tambahkan panel baru
                                $current_tingkat = $row['tingkat'];
                                echo "
                            <div class='panel level-$current_tingkat' onclick='togglePanel(this)'>
                                <div class='panel-heading'>
                                    Tingkat $current_tingkat
                                    <span class='icon'>&#62;</span>
                                </div>
                                <div class='panel-body' style='display: none;'>
                            ";
                            }

                            // Tambahkan data tata tertib dalam panel ini
                            echo "<p>{$row['pelanggaran']}</p>";
                        }

                        // Tutup panel terakhir
                        if ($current_tingkat !== null) {
                            echo "</div></div>";
                        }
                        ?>
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

    <!-- Custom JavaScript for Toggle Effect -->
    <script>
        function togglePanel(panel) {
            // Close all panels first
            var panels = document.querySelectorAll('.panel');
            panels.forEach(function(p) {
                if (p !== panel) {
                    p.classList.remove('open');
                    p.querySelector('.panel-body').style.display = 'none';
                }
            });

            // Toggle the selected panel
            var panelBody = panel.querySelector('.panel-body');
            var icon = panel.querySelector('.icon');

            if (panelBody.style.display === 'none' || panelBody.style.display === '') {
                panelBody.style.display = 'block';
                panel.classList.add('open');
            } else {
                panelBody.style.display = 'none';
                panel.classList.remove('open');
            }
        }
    </script>
</body>

</html>