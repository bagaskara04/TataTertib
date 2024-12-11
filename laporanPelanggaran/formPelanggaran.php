<?php  
session_start();  
require_once '../koneksi.php';  

// Fungsi untuk validasi image
function validateImage($file) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2MB
    
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('Hanya file gambar yang diperbolehkan (JPG, PNG, GIF)');
    }
    
    if ($file['size'] > $max_size) {
        throw new Exception('Ukuran file maksimal 2MB');
    }
    
    return true;
}

// Fungsi untuk upload file yang aman
function uploadFile($file) {
    $target_dir = "uploads/";
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Membuat direktori jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Validasi tipe file
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_extension, $allowed_types)) {
        throw new Exception("Tipe file tidak diizinkan");
    }
    
    // Validasi ukuran
    if ($file['size'] > 2000000) {
        throw new Exception("Ukuran file maksimal 2MB");
    }
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $target_file;
    }
    
    throw new Exception("Gagal mengupload file");
}

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
    $data = [];  

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {  
        $data[] = $row;  
    }  

    return $data;  
}

// Fungsi untuk mendapatkan semua pelanggaran  
function getAllPelanggaran($conn) {  
    $sql = "SELECT * FROM pelanggaran ORDER BY pelanggaran";  
    $stmt = sqlsrv_query($conn, $sql);  
    return $stmt ? $stmt : [];  
}  

// Fungsi helper untuk badge status
function getStatusBadge($status) {
    switch(strtolower($status)) {
        case 'proses':
            return 'warning';
        case 'valid':
            return 'success';
        case 'tidak valid':
            return 'danger';
        default:
            return 'secondary';
    }
}

// Endpoint untuk mendapatkan nama berdasarkan NIM
if (isset($_GET['get_nama']) && isset($_GET['nim'])) {
    $nim = $_GET['nim'];
    $sql = "SELECT nama FROM mahasiswa WHERE nim = ?";
    $stmt = sqlsrv_query($conn, $sql, [$nim]);
    
    if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo json_encode(['success' => true, 'nama' => $row['nama']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nama tidak ditemukan']);
    }
    exit();
}

// Proses form submission untuk CREATE dan UPDATE  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
    if (isset($_POST['submit'])) {  
        try {
            $nip = $_POST['nip'];  
            $nim = $_POST['nim'];  
            $pelanggaran_id = $_POST['pelanggaran_id'];  
            $status_pengaduan = $_POST['status_pengaduan'];  
            $catatan = $_POST['catatan'];  

            $target_file = '';
            if (isset($_FILES['bukti_pelanggaran']) && $_FILES['bukti_pelanggaran']['error'] === UPLOAD_ERR_OK) {
                validateImage($_FILES['bukti_pelanggaran']);
                $target_file = uploadFile($_FILES['bukti_pelanggaran']);
            }

            // Ambil ID terakhir dan tambahkan 1 untuk ID baru  
            $sql = "SELECT MAX(pengaduan_id) AS max_id FROM pengaduan";  
            $stmt = sqlsrv_query($conn, $sql);  
            $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);  
            $new_id = $result['max_id'] + 1;  

            // Simpan data ke database dengan ID baru  
            $sql = "INSERT INTO pengaduan (pengaduan_id, nip, nim, pelanggaran_id, bukti_pelanggaran, tanggal_pengaduan, status_pengaduan, catatan) VALUES (?, ?, ?, ?, ?, GETDATE(), ?, ?)";  
            $params = [$new_id, $nip, $nim, $pelanggaran_id, $target_file, $status_pengaduan, $catatan];  
            $stmt = sqlsrv_query($conn, $sql, $params);  
            
            if ($stmt) {  
                $_SESSION['message'] = "Pengaduan berhasil ditambahkan!";  
                $_SESSION['message_type'] = "success";  
            } else {  
                throw new Exception(print_r(sqlsrv_errors(), true));
            }  
        } catch (Exception $e) {  
            $_SESSION['message'] = "Error: " . $e->getMessage();  
            $_SESSION['message_type'] = "danger";  
        }  

        header("Location: formPelanggaran.php");  
        exit();  
    }  
}  

// Proses untuk DELETE  
if (isset($_GET['delete_id'])) {  
    $delete_id = $_GET['delete_id'];  
    try {  
        // Ambil nama file bukti sebelum menghapus
        $sql = "SELECT bukti_pelanggaran FROM pengaduan WHERE pengaduan_id = ?";
        $stmt = sqlsrv_query($conn, $sql, [$delete_id]);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        
        if ($row && file_exists($row['bukti_pelanggaran'])) {
            unlink($row['bukti_pelanggaran']); // Hapus file bukti
        }

        $sql = "DELETE FROM pengaduan WHERE pengaduan_id = ?";  
        $stmt = sqlsrv_query($conn, $sql, [$delete_id]);  
        
        if ($stmt) {  
            $_SESSION['message'] = "Pengaduan berhasil dihapus!";  
            $_SESSION['message_type'] = "success";  
        } else {  
            throw new Exception(print_r(sqlsrv_errors(), true));
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    
    <style>
    .loading {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.8);
        display: none;
        z-index: 1000;
    }
    .loading-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    #preview {
        max-width: 200px;
        max-height: 200px;
        margin-top: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
        display: none;
    }
    
    #modalImage {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
    }
    
    .modal-body {
        text-align: center;
        padding: 20px;
    }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">  
<div class="wrapper">  

    <!-- Loading Indicator -->
    <div class="loading">
        <div class="loading-content">
            <i class="fa fa-spinner fa-spin fa-3x"></i>
            <p>Memproses...</p>
        </div>
    </div>

    <!-- Navbar -->  
    <nav class="main-header navbar navbar-expand navbar-blue navbar-dark">  
        <ul class="navbar-nav">  
            <li class="nav-item">  
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fa fa-bars"></i>
                </a>  
            </li>  
            <li class="nav-item d-none d-sm-inline-block">  
                <a href="#" class="nav-link"></a>  
            </li>  
        </ul>  
    </nav>  

    <!-- Sidebar -->  
        <aside class="main-sidebar sidebar-dark-primary elevation-4">  
            <a href="#" class="brand-link">  
                <span class="brand-text font-weight-light">SiTatib</span>  
            </a>  
            <div class="sidebar">  
                <nav class="mt-2">  
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">  
                        <li class="nav-item">  
                        <li class="active"><a href="../dosen/dashboardDosen.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li> 
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
                <h1 class="m-0">Laporan Pelanggaran</h1>  
            </div>  
        </div>  

        <div class="content">  
            <div class="container-fluid">  
                <!-- Form Upload -->  
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Form Pengaduan</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['message'])): ?>  
                            <div class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show">  
                                <?= $_SESSION['message']; ?>  
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?php unset($_SESSION['message']); ?>  
                            </div>  
                        <?php endif; ?>  
                        <form id="formPengaduan" action="formPelanggaran.php" method="POST" enctype="multipart/form-data">  
                            <div class="form-group">  
                                <label for="nip">NIP :</label>  
                                <input type="text" class="form-control" id="nip" name="nip" required>  
                            </div>  
                            <div class="form-group">  
                                <label for="nim">NIM :</label>  
                                <input type="text" class="form-control" id="nim" name="nim" required>  
                            </div>  
                            <div class="form-group">  
                                <label for="nama">Nama Mahasiswa :</label>  
                                <input type="text" class="form-control" id="nama" name="nama" readonly>  
                            </div>
                            <div class="form-group">  
                                <label for="pelanggaran_id">Pelanggaran:</label>  
                                <select class="form-control" id="pelanggaran_id" name="pelanggaran_id" required>  
                                    <option value="">Pilih Pelanggaran</option>  
                                    <?php while ($pelanggaran = sqlsrv_fetch_array($pelanggaran_list, SQLSRV_FETCH_ASSOC)): ?>  
                                        <option value="<?= $pelanggaran['pelanggaran_id']; ?>">
                                            <?= htmlspecialchars($pelanggaran['pelanggaran']); ?>
                                        </option>  
                                    <?php endwhile; ?>  
                                </select>  
                            </div>  
                            <div class="form-group">  
                                <label for="bukti_pelanggaran">Bukti Pelanggaran :</label>  
                                <input type="file" class="form-control" id="bukti_pelanggaran" 
                                name="bukti_pelanggaran" accept="image/*" onchange="previewImage(this);" required>  
                                <img id="preview" src="#" alt="Preview">
                            </div>  
                            <div class="form-group">  
                                <label for="catatan">Catatan:</label>  
                                <textarea class="form-control" id="catatan" name="catatan" rows="3" required></textarea>  
                            </div>  
                            <div class="form-group">  
                                <div class="status-display">
                                    Status Pengaduan  
                                    <input type="hidden" id="status_pengaduan" name="status_pengaduan" value="proses">
                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                        <span>proses</span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fa fa-paper-plane"></i> Kirim Pengaduan
                            </button>  
                        </form>  
                    </div>
                </div>  

                <!-- Tabel Data Pengaduan -->  
                <div class="card mt-4">  
                    <div class="card-header">  
                        <h3 class="card-title">Daftar Pengaduan</h3>
                    </div>  
                    <div class="card-body">  
                        <table id="tabelPengaduan" class="table table-bordered table-striped">  
                            <thead>  
                                <tr>  
                                    <th>ID</th>  
                                    <th>NIP</th>  
                                    <th>NIM</th>  
                                    <th>Pelanggaran</th>  
                                    <th>Bukti</th>  
                                    <th>Catatan</th>  
                                    <th>Tanggal</th>  
                                    <th>Status</th>  
                                    <th>Aksi</th>  
                                </tr>  
                            </thead>  
                            <tbody>  
                            <?php if (!empty($data)): ?>  
                                <?php foreach ($data as $index => $row): ?>  
                                    <tr>  
                                        <td><?= htmlspecialchars($row['pengaduan_id']); ?></td>   
                                        <td><?= htmlspecialchars($row['nip']); ?></td>  
                                        <td><?= htmlspecialchars($row['nim']); ?></td>  
                                        <td><?= htmlspecialchars($row['pelanggaran']); ?></td>  
                                        <td class="text-center">  
                                            <?php if ($row['bukti_pelanggaran'] && file_exists($row['bukti_pelanggaran'])): ?>  
                                                <button type="button" class="btn btn-info btn-sm"
                                                        onclick="showImage('<?= htmlspecialchars($row['bukti_pelanggaran']); ?>')">
                                                    <i class="fa fa-eye"></i> Lihat
                                                </button>
                                            <?php else: ?>  
                                                <span class="badge badge-warning">Tidak ada file</span>  
                                            <?php endif; ?>  
                                        </td>  
                                        <td><?= htmlspecialchars($row['catatan']); ?></td>  
                                        <td><?= $row['tanggal_pengaduan'] instanceof DateTime ? 
                                               $row['tanggal_pengaduan']->format('Y-m-d H:i:s') : 
                                               $row['tanggal_pengaduan']; ?></td>  
                                        <td>
                                            <span class="badge badge-<?= getStatusBadge($row['status_pengaduan']); ?>">
                                                <?= htmlspecialchars($row['status_pengaduan']); ?>
                                            </span>
                                        </td>  
                                        <td class="text-center">  
                                            <div class="btn-group">
                                                <a href="edit.php?id=<?= $row['pengaduan_id']; ?>" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="confirmDelete(<?= $row['pengaduan_id']; ?>)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>  
                                    </tr>  
                                <?php endforeach; ?>  
                            <?php else: ?>  
                                <tr>  
                                    <td colspan="10" class="text-center">Tidak ada data pengaduan.</td>  
                                </tr>  
                            <?php endif; ?>  
                            </tbody>  
                        </table>  
                    </div>  
                </div>  
            </div>  
        </div>  
    </div>  
</div>

<!-- Modal Preview Image -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Bukti</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" style="max-width:100%; max-height:80vh;">
                <div id="imageError" class="alert alert-danger mt-2" style="display:none;">
                    Gambar tidak dapat ditampilkan atau file tidak ditemukan
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

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>
// Fungsi untuk preview image sebelum upload
function previewImage(input) {
    const preview = document.getElementById('preview');
    const file = input.files[0];
    
    if (file) {
        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Hanya file gambar yang diperbolehkan (JPG, PNG, GIF)');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Validasi ukuran
        if (file.size > 2000000) {
            alert('Ukuran file maksimal 2MB');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = '#';
        preview.style.display = 'none';
    }
}

// Fungsi untuk menampilkan gambar di modal
function showImage(url) {
    const modalImage = document.getElementById('modalImage');
    const imageError = document.getElementById('imageError');
    
    modalImage.src = url;
    modalImage.style.display = 'block';
    imageError.style.display = 'none';
    
    modalImage.onerror = function() {
        modalImage.style.display = 'none';
        imageError.style.display = 'block';
    };
    
    $('#imageModal').modal('show');
}

// Fungsi untuk konfirmasi delete
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')) {
        window.location.href = 'formPelanggaran.php?delete_id=' + id;
    }
}

// Inisialisasi DataTables dan event handlers
$(document).ready(function() {
    // DataTables initialization
    $('#tabelPengaduan').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
        },
        "order": [[6, "desc"]],
        "pageLength": 10,
        "responsive": true
    });

    // Event handler untuk input NIM
    $('#nim').on('input', function() {
        var nim = $(this).val();
        
        // Reset nama field
        $('#nama').val('');
        
        // Validasi NIM (10 digit)
        if (nim.length === 10 && /^\d{10}$/.test(nim)) {
            // Tampilkan loading
            $('.loading').show();
            
            // Ajax request untuk mendapatkan nama
            $.ajax({
                url: 'formPelanggaran.php',
                method: 'GET',
                data: {
                    get_nama: true,
                    nim: nim
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#nama').val(response.nama);
                    } else {
                        $('#nama').val('Mahasiswa tidak ditemukan');
                    }
                },
                error: function() {
                    $('#nama').val('Error mengambil data');
                },
                complete: function() {
                    $('.loading').hide();
                }
            });
        }
    });

    // Form validation
    $('#formPengaduan').submit(function(e) {
        var nip = $('#nip').val();
        var nim = $('#nim').val();
        var nama = $('#nama').val();
        
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
        
        // Validasi nama mahasiswa
        if (!nama || nama === 'Mahasiswa tidak ditemukan' || nama === 'Error mengambil data') {
            alert('Data mahasiswa tidak valid!');
            e.preventDefault();
            return false;
        }
        
        // Tampilkan loading indicator
        $('.loading').show();
    });
});
</script>
</body>
</html>