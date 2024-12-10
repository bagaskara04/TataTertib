 <?php  
session_start();  
require_once '../koneksi.php';  

// Proses form submission untuk UPDATE  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
    $nip = $_POST['nip'];  
    $nim = $_POST['nim'];  
    $pelanggaran_id = $_POST['pelanggaran_id'];  
    $status_pengaduan = $_POST['status_pengaduan'];  
    $catatan = $_POST['catatan'];  
    $pengaduan_id = $_POST['pengaduan_id']; // Ambil ID pengaduan dari form  

    // Mengelola upload file  
    $bukti_pelanggaran = $_FILES['bukti_pelanggaran']['name'];  
    $target_dir = "uploads/";  
    $uploadOk = 1;  
    $bukti_pelanggaran_path = null; // Inisialisasi path bukti pelanggaran  

    // Ambil bukti pelanggaran sebelumnya dari database  
    $sql = "SELECT bukti_pelanggaran FROM pengaduan WHERE pengaduan_id = ?";  
    $stmt = sqlsrv_query($conn, $sql, [$pengaduan_id]);  
    $previous_data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);  
    $previous_bukti = $previous_data['bukti_pelanggaran'];  

    // Jika ada file yang diupload  
    if (!empty($bukti_pelanggaran)) {  
        $target_file = $target_dir . basename($bukti_pelanggaran);  

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
                $bukti_pelanggaran_path = $target_file; // Simpan path file yang diupload  
            } else {  
                $_SESSION['message'] = "Terjadi kesalahan saat mengunggah file.";  
                $_SESSION['message_type'] = "danger";  
            }  
        }  
    }  

    // Jika tidak ada file baru yang diupload, gunakan bukti sebelumnya  
    if (empty($bukti_pelanggaran_path)) {  
        $bukti_pelanggaran_path = $previous_bukti; // Gunakan bukti sebelumnya  
    }  

    // Update data pengaduan  
    try {  
        $sql = "UPDATE pengaduan SET nip = ?, nim = ?, pelanggaran_id = ?, bukti_pelanggaran = ?, status_pengaduan = ?, catatan = ? WHERE pengaduan_id = ?";  
        $params = [$nip, $nim, $pelanggaran_id, $bukti_pelanggaran_path, $status_pengaduan, $catatan, $pengaduan_id];  
        $stmt = sqlsrv_query($conn, $sql, $params);  

        if ($stmt) {  
            $_SESSION['message'] = "Pengaduan berhasil diperbarui!";  
            $_SESSION['message_type'] = "success";  
        } else {  
            $_SESSION['message'] = "Error: " . print_r(sqlsrv_errors(), true);  
            $_SESSION['message_type'] = "danger";  
        }  
    } catch (Exception $e) {  
        $_SESSION['message'] = "Error: " . $e->getMessage();  
        $_SESSION['message_type'] = "danger";  
    }  

    header("Location: formPelanggaran.php");  
    exit();  
}
?>    