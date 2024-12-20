<?php  
session_start();  
require_once '../koneksi.php';  

// Tambahkan pengecekan status submit
$isSubmitted = isset($_GET['status']) && $_GET['status'] === 'success';

if (!isset($_GET['id'])) {  
    header("Location: formPelanggaran.php");  
    exit();  
}  

$pengaduan_id = $_GET['id'];  
$sql = "SELECT * FROM pengaduan WHERE pengaduan_id = ?";  
$stmt = sqlsrv_query($conn, $sql, [$pengaduan_id]);  
$pengaduan = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);  

if (!$pengaduan) {  
    $_SESSION['message'] = "Pengaduan tidak ditemukan.";  
    $_SESSION['message_type'] = "danger";  
    header("Location: formPelanggaran.php");  
    exit();  
}  

// Fungsi untuk mendapatkan semua pelanggaran  
function getAllPelanggaran($conn) {  
    $sql = "SELECT * FROM pelanggaran ORDER BY pelanggaran";  
    $stmt = sqlsrv_query($conn, $sql);  

    $pelanggaran_list = []; // Inisialisasi array untuk menyimpan hasil  

    // Ambil data dari hasil query  
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {  
        $pelanggaran_list[] = $row; // Tambahkan setiap baris ke dalam array  
    }  

    return $pelanggaran_list; // Kembalikan array  
}  

$pelanggaran_list = getAllPelanggaran($conn);  
?>  

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengaduan</title>
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
            height: 50px;
            display: flex;
            align-items: center;
        }

        .form-box {
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
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

        <!-- Sidebar -->
        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <ul class="sidebar-menu">
                    <li class="header"> Menu </li>
                    <li><a href="../dosen/dashboardDosen.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li><a href="../dosen/dpaDosen.php"><i class="fa fa-users"></i> <span>Daftar DPA</span></a></li>
                    <li><a href="../dosen/daftarMahasiswa.php"><i class="fa fa-file-text-o"></i> <span>Daftar Mahasiswa</span></a></li>
                    <li class="active"><a href="formPelanggaran.php"><i class="fa fa-file-text-o"></i> <span>Laporan Pelanggaran</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content-header">
                <h1>Edit Pengaduan</h1>
            </section>

            <div class="content">
                <div class="container-fluid">
                    <?php if ($isSubmitted): ?>
                        <div class="alert alert-success alert-dismissible fade in" id="successAlert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Pengaduan berhasil diperbarui!
                        </div>
                    <?php endif; ?>
                        
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Pengaduan</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-box">
                                <form action="uploads.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="pengaduan_id" value="<?= htmlspecialchars($pengaduan['pengaduan_id']); ?>">
                                    <div class="form-group">
                                        <label for="nip">NIP :</label>
                                        <input type="text" class="form-control" id="nip" name="nip" value="<?php echo $_SESSION['nip']; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="nim">NIM:</label>
                                        <input type="text" class="form-control" id="nim" name="nim" value="<?= htmlspecialchars($pengaduan['nim']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="pelanggaran_id">Pelanggaran:</label>
                                        <select class="form-control" id="pelanggaran_id" name="pelanggaran_id" required>
                                            <option value="">Pilih Pelanggaran</option>
                                            <?php foreach ($pelanggaran_list as $pelanggaran): ?>
                                                <option value="<?= $pelanggaran['pelanggaran_id']; ?>" <?= $pelanggaran['pelanggaran_id'] == $pengaduan['pelanggaran_id'] ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($pelanggaran['pelanggaran']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="bukti_pelanggaran">Bukti Pelanggaran:</label>
                                        <input type="file" class="form-control" id="bukti_pelanggaran" name="bukti_pelanggaran">
                                        <small>Biarkan kosong jika tidak ingin mengubah bukti.</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="catatan">Catatan:</label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="3" required><?= htmlspecialchars($pengaduan['catatan']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="status-display">
                                            Status Pengaduan
                                            <input type="hidden" id="status_pengaduan" name="status_pengaduan" value="proses">
                                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                                <span>Sedang Diproses</span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" name="update" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Perbarui Pengaduan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    <script>
    $(document).ready(function() {
        // Auto hide alert after 3 seconds
        if ($("#successAlert").length > 0) {
            setTimeout(function() {
                $("#successAlert").fadeOut('slow');
            }, 3000);
        }

        // Form validation
        $('form').submit(function(e) {
            var nim = $('#nim').val();
            
            // Validasi NIM (10 digit)
            if (!/^\d{10}$/.test(nim)) {
                alert('NIM harus 10 digit angka!');
                e.preventDefault();
                return false;
            }
            
            // Validasi file jika ada
            var fileInput = $('#bukti_pelanggaran')[0];
            if (fileInput.files.length > 0) {
                var file = fileInput.files[0];
                var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                var maxSize = 2 * 1024 * 1024; // 2MB
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Hanya file gambar yang diperbolehkan (JPG, PNG, GIF)');
                    e.preventDefault();
                    return false;
                }
                
                if (file.size > maxSize) {
                    alert('Ukuran file maksimal 2MB');
                    e.preventDefault();
                    return false;
                }
            }
        });
    });
    </script>
</body>
</html>
