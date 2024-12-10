<?php  
session_start();  
require_once '../koneksi.php';  

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
<html lang="id">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Edit Pengaduan</title>  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">  
</head>  
<body class="hold-transition sidebar-mini layout-fixed">  
<div class="wrapper">  

    <!-- Navbar -->  
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">  
        <ul class="navbar-nav">  
            <li class="nav-item">  
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fa fa-bars"></i></a>  
            </li>  
            <li class="nav-item d-none d-sm-inline-block">  
                <a href="#" class="nav-link">Home</a>  
            </li>  
        </ul>  
    </nav>  

    <!-- Sidebar -->  
    <aside class="main-sidebar sidebar-dark-primary elevation-4">  
        <a href="#" class="brand-link">  
            <span class="brand-text font-weight-light">SimpleAdminLTE</span>  
        </a>  
        <div class="sidebar">  
            <nav class="mt-2">  
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">  
                    <li class="nav-item">  
                        <a href="#" class="nav-link active">  
                            <i class="nav-icon fa fa-file"></i>  
                            <p>Edit Pengaduan</p>  
                        </a>  
                    </li>  
                </ul>  
            </nav>  
        </div>  
    </aside>  

    <!-- Content Wrapper -->  
    <div class="content-wrapper">  
        <div class="content-header">  
            <div class="container-fluid">  
                
            </div>  
        </div>  

        <div class="content">  
            <div class="container-fluid">  
                <h2>Form Edit Pengaduan</h2><br><br>  
                <?php if (isset($_SESSION['message'])): ?>  
                    <div class="alert alert-<?= $_SESSION['message_type']; ?>">  
                        <?= $_SESSION['message']; ?>  
                        <?php unset($_SESSION['message']); ?>  
                    </div>  
                <?php endif; ?>  
                
                <form action="uploads.php" method="POST" enctype="multipart/form-data">  
                    <input type="hidden" name="pengaduan_id" value="<?= htmlspecialchars($pengaduan['pengaduan_id']); ?>">  
                    <div class="form-group">  
                        <label for="nip">NIP:</label>  
                        <input type="text" class="form-control" id="nip" name="nip" value="<?= htmlspecialchars($pengaduan['nip']); ?>" required>  
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
                                <option value="<?= $pelanggaran['pelanggaran_id']; ?>" <?= $pelanggaran['pelanggaran_id'] == $pengaduan['pelanggaran_id'] ? 'selected' : ''; ?>><?= htmlspecialchars($pelanggaran['pelanggaran']); ?></option>  
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
                    <div class="status-display">
                                    Status Pengaduan  
                                <input type="hidden" id="status_pengaduan" name="status_pengaduan" value="proses">
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <span>Sedang Diproses</span>
                                </div>
                    </div> 
                    <button type="submit" name="update" class="btn btn-primary">Perbarui Pengaduan</button>  
                </form>  
            </div>  
        </div>  
    </div>  
</div>  

<!-- Scripts -->  
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>  

<script>
$(document).ready(function() {
    // Form validation
    $('form').submit(function(e) {
        var nip = $('#nip').val();
        var nim = $('#nim').val();
        
        // Validasi NIP (16 digit)
        if (!/^\d{16}$/.test(nip)) {
            alert('NIP harus 16 digit angka!');
            e.preventDefault();
            return false;
        }
        
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