<?php
include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $nim = $_POST['id'];

    // Mulai transaksi
    sqlsrv_begin_transaction($conn);

    try {
        // Query hapus level user
        $queryDeleteUser = "DELETE FROM dbo.users WHERE nim = ?";
        $paramsUser = array($nim);
        $stmtUser = sqlsrv_query($conn, $queryDeleteUser, $paramsUser);

        if (!$stmtUser) {
            throw new Exception("Gagal menghapus data dari tabel users. Error: " . print_r(sqlsrv_errors(), true));
        }

        // Query hapus data tabel mahasiswa
        $queryDeleteMahasiswa = "DELETE FROM dbo.mahasiswa WHERE nim = ?";
        $paramsMahasiswa = array($nim);
        $stmtMahasiswa = sqlsrv_query($conn, $queryDeleteMahasiswa, $paramsMahasiswa);

        if (!$stmtMahasiswa) {
            throw new Exception("Gagal menghapus data dari tabel mahasiswa. Error: " . print_r(sqlsrv_errors(), true));
        }
        sqlsrv_commit($conn);

    
        echo "Mahasiswa dengan NIM $nim berhasil dihapus beserta data pengguna terkait.";
    } catch (Exception $e) {
        // tollback error
        sqlsrv_rollback($conn);

   
        echo "Terjadi kesalahan saat menghapus data: " . $e->getMessage();
    }
} else {
    echo "Tidak ada ID yang diterima atau metode salah.";
}
?>
