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
          JOIN users u ON m.nim = u.nim
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

// Handle form submission to update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $ttl = $_POST['ttl'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $no_phone = $_POST['no_phone'];
    $phone_ortu = $_POST['phone_ortu'];

    // Handle file upload for mahasiswa_img
    if (isset($_FILES['mahasiswa_img']) && $_FILES['mahasiswa_img']['error'] === 0) {
        // Get file information
        $fileTmpPath = $_FILES['mahasiswa_img']['tmp_name'];
        $fileName = $_FILES['mahasiswa_img']['name'];
        $fileSize = $_FILES['mahasiswa_img']['size'];
        $fileType = $_FILES['mahasiswa_img']['type'];

        // Get file extension
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Set upload directory and file name
        $uploadDir = 'image/';
        $newFileName = 'profile_' . $nim . '.' . $fileExtension;
        $uploadPath = $uploadDir . $newFileName;

        // Allowed file types (image only)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            // Move file to the upload directory
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $mahasiswa_img = $uploadPath; // Store the file path in database
            } else {
                $errorMessage = "File upload failed!";
            }
        } else {
            $errorMessage = "Only image files are allowed (jpg, jpeg, png, gif).";
        }
    } else {
        $mahasiswa_img = $data['mahasiswa_img']; // Keep the existing image if no new one is uploaded
    }

    // Update query
    $updateQuery = "UPDATE mahasiswa SET nama = ?, TTL = ?, jenis_kelamin = ?, alamat = ?, email = ?, no_phone = ?, phone_ortu = ?, mahasiswa_img = ? 
                    WHERE nim = ?";
    $params = array($nama, $ttl, $jenis_kelamin, $alamat, $email, $no_phone, $phone_ortu, $mahasiswa_img, $nim);
    $updateStmt = sqlsrv_query($conn, $updateQuery, $params);

    if ($updateStmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "<script>alert('Profil berhasil diperbarui');</script>";
        header("Location: Profile.php"); // Redirect back to profile page
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Profil Mahasiswa</title>
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
        }

        .profile-header h3 {
            font-size: 24px;
            margin-top: 10px;
        }

        .profile-header p {
            font-size: 18px;
            color: #777;
        }

        .profile-form {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-form input,
        .profile-form select,
        .profile-form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn-primary {
            background-color: #115599;
            border-color: #115599;
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
                    <li><a href="Profile.php"><i class="fa fa-user"></i> <span>Profil</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i> <span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Header Konten -->
            <section class="content-header">
                <h1>Edit Profil Mahasiswa</h1>
            </section>

            <!-- Konten Profil -->
            <section class="content">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="profile-form">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="nim" value="<?php echo htmlspecialchars($data['nim']); ?>">
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="ttl">TTL</label>
                                    <input type="date" name="ttl" class="form-control" value="<?php echo htmlspecialchars($data['TTL']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-control">
                                        <option value="L" <?php echo $data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                                        <option value="P" <?php echo $data['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" name="alamat" class="form-control" value="<?php echo htmlspecialchars($data['alamat']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_phone">Nomor Telepon</label>
                                    <input type="text" name="no_phone" class="form-control" value="<?php echo htmlspecialchars($data['no_phone']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone_ortu">Nomor Telepon Orang Tua</label>
                                    <input type="text" name="phone_ortu" class="form-control" value="<?php echo htmlspecialchars($data['phone_ortu']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="mahasiswa_img">Ganti Foto Profil</label>
                                    <input type="file" name="mahasiswa_img" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 1.0
            </div>
            <strong>&copy; 2024 <a href="#">SITATIB</a>.</strong> All rights reserved.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="../plugins/jQuery/jQuery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>

</html>