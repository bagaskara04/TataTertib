<?php
include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $nim = $_POST['id'];

    // Mulai transaksi untuk memastikan kedua penghapusan terjadi atau tidak sama sekali
    sqlsrv_begin_transaction($conn);

    try {
        // Query untuk menghapus data dari tabel users berdasarkan nim
        $queryDeleteUser = "DELETE FROM dbo.users WHERE nim = ?";
        $paramsUser = array($nim);
        $stmtUser = sqlsrv_query($conn, $queryDeleteUser, $paramsUser);

        if (!$stmtUser) {
            throw new Exception("Gagal menghapus data dari tabel users. Error: " . print_r(sqlsrv_errors(), true));
        }

        // Query untuk menghapus data dari tabel mahasiswa berdasarkan nim
        $queryDeleteMahasiswa = "DELETE FROM dbo.mahasiswa WHERE nim = ?";
        $paramsMahasiswa = array($nim);
        $stmtMahasiswa = sqlsrv_query($conn, $queryDeleteMahasiswa, $paramsMahasiswa);

        if (!$stmtMahasiswa) {
            throw new Exception("Gagal menghapus data dari tabel mahasiswa. Error: " . print_r(sqlsrv_errors(), true));
        }

        // Jika kedua query berhasil, commit transaksi
        sqlsrv_commit($conn);

        // Tampilkan pesan sukses
        echo "Mahasiswa dengan NIM $nim berhasil dihapus beserta data pengguna terkait.";
    } catch (Exception $e) {
        // Jika ada error, rollback transaksi
        sqlsrv_rollback($conn);

        // Tampilkan pesan error
        echo "Terjadi kesalahan saat menghapus data: " . $e->getMessage();
    }
} else {
    echo "Tidak ada ID yang diterima atau metode salah.";
}
?>
