<?php  
session_start();  
require_once '../koneksi.php';  

// Fungsi untuk mendapatkan semua data pengaduan  
function getAllData($conn) {  
    $sql = "SELECT   
                p.pengaduan_id,   
                p.nip,   
                p.nim,   
                p.pelanggaran_id,   
                p.bukti_pelanggaran,   
                p.tanggal_pengaduan,   
                p.status_pengaduan,   
                p.catatan,   
                m.nama,   
                pel.pelanggaran  
            FROM pengaduan p  
            LEFT JOIN mahasiswa m ON p.nim = m.nim  
            LEFT JOIN pelanggaran pel ON p.pelanggaran_id = pel.pelanggaran_id  
            ORDER BY p.tanggal_pengaduan DESC";  

    $stmt = sqlsrv_query($conn, $sql);  
    $data = []; // Inisialisasi array untuk menyimpan hasil  

    // Ambil data dari hasil query  
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {  
        $data[] = $row; // Tambahkan setiap baris ke dalam array  
    }  

    return $data; // Kembalikan array  
}
// Fungsi untuk mendapatkan semua pelanggaran  
function getAllPelanggaran($conn) {  
    $sql = "SELECT * FROM pelanggaran ORDER BY pelanggaran";  
    $stmt = sqlsrv_query($conn, $sql);  
    return $stmt ? $stmt : [];  
}  

// Proses form submission untuk CREATE dan UPDATE  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
    if (isset($_POST['submit'])) {  
        $nip = $_POST['nip'];  
        $nim = $_POST['nim'];  
        $pelanggaran_id = $_POST['pelanggaran_id'];  
        $status_pengaduan = $_POST['status_pengaduan'];  
        $catatan = $_POST['catatan'];  

        // Mengelola upload file  
        $bukti_pelanggaran = $_FILES['bukti_pelanggaran']['name'];  
        $target_dir = "uploads/";  
        $target_file = $target_dir . basename($bukti_pelanggaran);  
        $uploadOk = 1;  

        // Cek apakah file sudah ada  
        if (file_exists($target_file)) {  
            $_SESSION['message'] = "File sudah ada.";  
            $_SESSION['message_type'] = "danger";  
            $uploadOk = 0;  
        }  

        // Cek ukuran file  
        if ($_FILES['bukti_pelanggaran']['size'] > 2000000) { // 2MB  
            $_SESSION['message'] = "File terlalu besar.";  
            $_SESSION['message_type'] = "danger";  
            $uploadOk = 0;  
        }  

        // Cek tipe file  
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));  
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {  
            $_SESSION['message'] = "Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";  
            $_SESSION['message_type'] = "danger";  
            $uploadOk = 0;  
        }  

        // Jika semua cek lolos, upload file  
        if ($uploadOk == 1) {  
            if (move_uploaded_file($_FILES['bukti_pelanggaran']['tmp_name'], $target_file)) {  
                try {  
                    // Ambil ID terakhir dan tambahkan 1 untuk ID baru  
                    $sql = "SELECT MAX(pengaduan_id) AS max_id FROM pengaduan";  
                    $stmt = sqlsrv_query($conn, $sql);  
                    $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);  
                    $new_id = $result['max_id'] + 1; // Menghasilkan ID baru  

                    // Simpan data ke database dengan ID baru  
                    $sql = "INSERT INTO pengaduan (pengaduan_id, nip, nim, pelanggaran_id, bukti_pelanggaran, tanggal_pengaduan, status_pengaduan, catatan) VALUES (?, ?, ?, ?, ?, GETDATE(), ?, ?)";  
                    $params = [$new_id, $nip, $nim, $pelanggaran_id, $target_file, $status_pengaduan, $catatan];  
                    $stmt = sqlsrv_query($conn, $sql, $params);  
                    
                    if ($stmt) {  
                        $_SESSION['message'] = "Pengaduan berhasil ditambahkan!";  
                        $_SESSION['message_type'] = "success";  
                    } else {  
                        $_SESSION['message'] = "Error: " . print_r(sqlsrv_errors(), true);  
                        $_SESSION['message_type'] = "danger";  
                    }  
                } catch (Exception $e) {  
                    $_SESSION['message'] = "Error: " . $e->getMessage();  
                    $_SESSION['message_type'] = "danger";  
                }  
            } else {  
                $_SESSION['message'] = "Terjadi kesalahan saat mengunggah file.";  
                $_SESSION['message_type'] = "danger";  
            }  
        }  

        header("Location: formPelanggaran.php");  
        exit();  
    }  
}  

// Proses untuk DELETE  
if (isset($_GET['delete_id'])) {  
    $delete_id = $_GET['delete_id'];  
    try {  
        $sql = "DELETE FROM pengaduan WHERE pengaduan_id = ?";  
        $stmt = sqlsrv_query($conn, $sql, [$delete_id]);  
        
        if ($stmt) {  
            $_SESSION['message'] = "Pengaduan berhasil dihapus!";  
            $_SESSION['message_type'] = "success";  
        } else {  
            $_SESSION['message'] = "Error: " . print_r(sqlsrv_errors(), true);  
            $_SESSION['message_type'] = "danger";  
        }  
    } catch(Exception $e) {  
        $_SESSION['message'] = "Error: " . $e->getMessage();  
        $_SESSION['message_type'] = "danger";  
    }  

    header("Location: formPelanggaran.php");  
    exit();  
}  

$data = getAllData($conn);  
$pelanggaran_list = getAllPelanggaran($conn);  
?>  

<!DOCTYPE html>  
<html lang="id">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Data Pengaduan</title>  
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
                            <p>Data Pengaduan</p>  
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
                <h1 class="m-0">Data Pengaduan</h1>  
            </div>  
        </div>  

        <div class="content">  
            <div class="container-fluid">  
                <!-- Form Upload -->  
                <div class="container mt-4">  
                    <h2>Form Pengaduan</h2>  
                    <?php if (isset($_SESSION['message'])): ?>  
                        <div class="alert alert-<?= $_SESSION['message_type']; ?>">  
                            <?= $_SESSION['message']; ?>  
                            <?php unset($_SESSION['message']); ?>  
                        </div>  
                    <?php endif; ?>  
                    
                    <form action="formPelanggaran.php" method="POST" enctype="multipart/form-data">  
                        <div class="form-group">  
                            <label for="nip">NIP:</label>  
                            <input type="text" class="form-control" id="nip" name="nip" required>  
                        </div>  
                        <div class="form-group">  
                            <label for="nim">NIM:</label>  
                            <input type="text" class="form-control" id="nim" name="nim" required>  
                        </div>  
                        <div class="form-group">  
                            <label for="pelanggaran_id">Pelanggaran:</label>  
                            <select class="form-control" id="pelanggaran_id" name="pelanggaran_id" required>  
                                <option value="">Pilih Pelanggaran</option>  
                                <?php while ($pelanggaran = sqlsrv_fetch_array($pelanggaran_list, SQLSRV_FETCH_ASSOC)): ?>  
                                    <option value="<?= $pelanggaran['pelanggaran_id']; ?>"><?= htmlspecialchars($pelanggaran['pelanggaran']); ?></option>  
                                <?php endwhile; ?>  
                            </select>  
                        </div>  
                        <div class="form-group">  
                            <label for="bukti_pelanggaran">Bukti Pelanggaran:</label>  
                            <input type="file" class="form-control" id="bukti_pelanggaran" name="bukti_pelanggaran" required>  
                        </div>  
                        <div class="form-group">  
                            <label for="catatan">Catatan:</label>  
                            <textarea class="form-control" id="catatan" name="catatan" rows="3" required></textarea>  
                        </div>  
                        <div class="form-group">  
                            <label for="status_pengaduan">Status Pengaduan:</label>  
                            <select class="form-control" id="status_pengaduan" name="status_pengaduan" required>  
                                <option value="proses">Proses</option>  
                                <option value="tidak valid">Tidak Valid</option>  
                                <option value="valid">Valid</option>  
                            </select>  
                        </div>  
                        <button type="submit" name="submit" class="btn btn-primary">Kirim Pengaduan</button>  
                    </form>  
                </div>  

                <!-- Tabel Data Pengaduan -->  
                <div class="card">  
                    <div class="card-header">  
                        <h3 class="card-title">Daftar Pengaduan</h3>  
                    </div>  
                    <div class="card-body">  
                        <table class="table table-bordered table-striped">  
                            <thead>  
                                <tr>  
                                    <th>Pengaduan ID</th> <!-- Tambahkan kolom untuk Pengaduan ID -->  
                                    <th>#</th>  
                                    <th>NIP</th>  
                                    <th>NIM</th>  
                                    <th>Pelanggaran</th>  
                                    <th>Bukti</th>  
                                    <th>Catatan</th>  
                                    <th>Tanggal Pengaduan</th>  
                                    <th>Status Pengaduan</th>  
                                    <th>Aksi</th>  
                                </tr>  
                            </thead>  
                            <tbody>  
                            <tbody>  
    <?php if (!empty($data)): ?>  
        <?php foreach ($data as $index => $row): ?>  
            <tr>  
                <td><?= htmlspecialchars($row['pengaduan_id']); ?></td>  
                <td><?= $index + 1; ?></td>  
                <td><?= htmlspecialchars($row['nip']); ?></td>  
                <td><?= htmlspecialchars($row['nim']); ?></td>  
                <td><?= htmlspecialchars($row['pelanggaran']); ?></td>  
                <td>  
                    <?php if ($row['bukti_pelanggaran']): ?>  
                        <a href="<?= $row['bukti_pelanggaran']; ?>" target="_blank">Lihat Bukti</a>  
                    <?php else: ?>  
                        No file  
                    <?php endif; ?>  
                </td>  
                <td><?= htmlspecialchars($row['catatan']); ?></td>  
                <td><?= htmlspecialchars($row['tanggal_pengaduan'] instanceof DateTime ? $row['tanggal_pengaduan']->format('Y-m-d H:i:s') : $row['tanggal_pengaduan']); ?></td> 
                <td><?= htmlspecialchars($row['status_pengaduan']); ?></td>  
                <td>  
                    <a href="edit.php?id=<?= $row['pengaduan_id']; ?>" class="btn btn-warning btn-sm">  
                        <i class="fa fa-edit"></i> Edit  
                    </a>  
                    <a href="formPelanggaran.php?delete_id=<?= $row['pengaduan_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">  
                        <i class="fa fa-trash"></i> Hapus  
                    </a>  
                </td>  
            </tr>  
        <?php endforeach; ?>  
    <?php else: ?>  
        <tr>  
            <td colspan="10" class="text-center">Tidak ada data tersedia.</td>  
        </tr>  
    <?php endif; ?>  
</tbody>
                                              
                            </tbody>  
                        </table>  
                    </div>  
                </div>  
            </div>  
        </div>  
    </div>  
</div>  

<!-- Scripts -->  
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>  
</body>  
</html>