<?php
include 'koneksi.php'; // Pastikan file koneksi database tersedia

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Query untuk memeriksa token
    $query = "
        SELECT * FROM mahasiswa WHERE reset_token = ? AND token_expiry > GETDATE()
        UNION
        SELECT * FROM staff WHERE reset_token = ? AND token_expiry > GETDATE()
        UNION
        SELECT * FROM dosen WHERE reset_token = ? AND token_expiry > GETDATE()
    ";

    // Siapkan statement dan bind parameter
    $stmt = sqlsrv_prepare($conn, $query, array($token, $token, $token));

    if (!$stmt) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Eksekusi query
    $result = sqlsrv_execute($stmt);

    if ($result === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Jika token valid
    if (sqlsrv_has_rows($stmt)) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            if ($newPassword === $confirmPassword) {
                // Hash password baru
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update password di tabel yang sesuai
                $updateQuery = "
                    UPDATE mahasiswa SET password = ? WHERE reset_token = ?
                    UNION
                    UPDATE staff SET password = ? WHERE reset_token = ?
                    UNION
                    UPDATE dosen SET password = ? WHERE reset_token = ?
                ";

                // Siapkan statement untuk update
                $updateStmt = sqlsrv_prepare($conn, $updateQuery, array($hashedPassword, $token, $hashedPassword, $token, $hashedPassword, $token));
                if (!$updateStmt) {
                    die(print_r(sqlsrv_errors(), true));
                }

                // Eksekusi query update
                $updateResult = sqlsrv_execute($updateStmt);
                if ($updateResult === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                // Hapus token setelah reset
                $clearTokenQuery = "
                    UPDATE mahasiswa SET reset_token = NULL, token_expiry = NULL WHERE reset_token = ?
                    UNION
                    UPDATE staff SET reset_token = NULL, token_expiry = NULL WHERE reset_token = ?
                    UNION
                    UPDATE dosen SET reset_token = NULL, token_expiry = NULL WHERE reset_token = ?
                ";
                $clearStmt = sqlsrv_prepare($conn, $clearTokenQuery, array($token, $token, $token));
                sqlsrv_execute($clearStmt);

                echo "<script>
                        alert('Password berhasil diperbarui!');
                        window.location.href = 'loginPage.html';
                      </script>";
            } else {
                echo "<script>alert('Password tidak cocok!'); window.history.back();</script>";
            }
        }
    } else {
        echo "<script>alert('Token tidak valid atau sudah kedaluwarsa!'); window.location.href = 'loginPage.html';</script>";
    }
} else {
    echo "<script>alert('Token tidak ditemukan!'); window.location.href = 'loginPage.html';</script>";
}
?>
