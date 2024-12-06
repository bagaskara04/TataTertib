<?php
include('../koneksi.php'); // Pastikan koneksi ke database sudah benar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = $_POST['nip'];

    // Memulai transaksi
    sqlsrv_begin_transaction($conn);

    try {
        // Query untuk menghapus data dosen
        $query_dosen = "DELETE FROM dbo.dosen WHERE nip = ?";
        $params_dosen = array($nip);
        $stmt_dosen = sqlsrv_query($conn, $query_dosen, $params_dosen);

        if (!$stmt_dosen) {
            $errors_dosen = sqlsrv_errors();
            throw new Exception("Gagal menghapus data dosen. Error: " . print_r($errors_dosen, true));
        }

        // Query untuk menghapus data di tabel users
        $query_users = "DELETE FROM dbo.users WHERE nip = ?";
        $params_users = array($nip);
        $stmt_users = sqlsrv_query($conn, $query_users, $params_users);

        if (!$stmt_users) {
            $errors_users = sqlsrv_errors();
            throw new Exception("Gagal menghapus data dari tabel users. Error: " . print_r($errors_users, true));
        }

        // Commit transaksi jika semua berhasil
        sqlsrv_commit($conn);
        echo "success"; // Mengirimkan respons sukses

    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        sqlsrv_rollback($conn);
        echo "error"; // Mengirimkan respons error
    }
} else {
    echo "Metode tidak diizinkan."; // Jika bukan POST
}
?>
