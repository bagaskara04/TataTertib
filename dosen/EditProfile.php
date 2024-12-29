<?php

include 'getDosenName.php';
// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: loginPage.html");
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari session

// Query untuk mengambil data mahasiswa berdasarkan user_id dari tabel users
$query = "SELECT d.nip, d.nama, d.TTL, d.jenis_kelamin, d.jabatan, d.email, d.no_phone, 
                 d.alamat, d.dosen_img, k.nama_kelas
          FROM dosen d
          LEFT JOIN kelas k ON d.nip = k.nip  -- Menghubungkan berdasarkan nip jika dosen sebagai DPA
          JOIN users u ON d.nip = u.nip  -- Menghubungkan berdasarkan nip
          WHERE u.user_id = ?";  // Kondisi untuk user_id dari session
$params = array($user_id);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Ambil hasil query
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);


// Handle form submission to update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $ttl = $_POST['ttl'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    $no_phone = $_POST['no_phone'];
    $alamat = $_POST['alamat'];

    // Handle file upload for mahasiswa_img
    if (isset($_FILES['dosen_img']) && $_FILES['dosen_img']['error'] === 0) {
        // Get file information
        $fileTmpPath = $_FILES['dosen_img']['tmp_name'];
        $fileName = $_FILES['dosen_img']['name'];
        $fileSize = $_FILES['dosen_img']['size'];
        $fileType = $_FILES['dosen_img']['type'];

        // Get file extension
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Set upload directory and file name
        $uploadDir = 'image/';
        $newFileName = 'profile_' . $nip . '.' . $fileExtension;
        $uploadPath = $uploadDir . $newFileName;

        // Allowed file types (image only)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            // Move file to the upload directory
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $dosen_img = $uploadPath; // Store the file path in database
            } else {
                $errorMessage = "File upload failed!";
            }
        } else {
            $errorMessage = "Only image files are allowed (jpg, jpeg, png, gif).";
        }
    } else {
        $dosen_img = $data['dosen_img']; // Keep the existing image if no new one is uploaded
    }

    // Update query
    $updateQuery = "UPDATE dosen SET nama = ?, TTL = ?, jenis_kelamin = ?, jabatan = ?, email = ?, no_phone = ?, alamat = ?, dosen_img = ? 
                    WHERE nip = ?";
    $params = array($nama, $ttl, $jenis_kelamin, $jabatan, $email, $no_phone, $alamat, $dosen_img, $nip);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Dosen</title>
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
        <!-- Header -->
        <header class="main-header">
            <a href="dashboardDosen.php" class="logo">
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
                <div class="user-panel" onclick="window.location.href='../dosen/Profile.php'" style="cursor: pointer;">
                    <div class="pull-left image">
                        <img src="<?php echo htmlspecialchars($data['dosen_img']); ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo htmlspecialchars($nama_dosen); ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <ul class="sidebar-menu">
                    <li class="header">Menu</li>
                    <li><a href="dashboardDosen.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li><a href="dpaDosen.php"><i class="fa fa-users"></i> <span>Daftar DPA</span></a></li>
                    <li><a href="daftarMahasiswa.php"><i class="fa fa-file-text-o"></i> <span>Daftar Mahasiswa</span></a></li>
                    <li><a href="formPelanggaran.php"><i class="fa fa-file-text-o"></i> <span> Laporan Pelanggaran</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Header Konten -->
            <section class="content-header">
                <h1>Edit Profil Dosen</h1>
            </section>

            <!-- Konten Profil -->
            <section class="content">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="profile-form">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="nip" value="<?php echo htmlspecialchars($data['nip']); ?>">
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
                                    <label for="jabatan">Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control" value="<?php echo htmlspecialchars($data['jabatan']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_phone">No HP</label>
                                    <input type="text" name="no_phone" class="form-control" value="<?php echo htmlspecialchars($data['no_phone']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" name="alamat" class="form-control" value="<?php echo htmlspecialchars($data['alamat']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="dosen_img">Ganti Foto Profil</label>
                                    <input type="file" name="dosen_img" class="form-control">
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
                <b><a href="https://jti.polinema.ac.id/" target="_blank">Jurusan Teknologi Informasi</a></b>
            </div>
            <strong><a href="https://polinema.ac.id" target="_blank">Politeknik Negeri Malang</a></strong>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="../plugins/jQuery/jQuery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>

</html>