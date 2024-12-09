<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Query to check email in mahasiswa, staff, and dosen tables
    $query = "
        SELECT 'mahasiswa' AS table_name, email FROM mahasiswa WHERE email = ?
        UNION
        SELECT 'staff', email FROM staff WHERE email = ?
        UNION
        SELECT 'dosen', email FROM dosen WHERE email = ?
    ";

    $stmt = sqlsrv_prepare($conn, $query, array($email, $email, $email));

    if (!$stmt) {
        die(print_r(sqlsrv_errors(), true));
    }

    $result = sqlsrv_execute($stmt);
    if ($result === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($stmt)) {
        $token = bin2hex(random_bytes(32)); // Generate a unique token
        $expiryTime = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Determine which table the email belongs to
        $table = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $table = $row['table_name'];
            break; // Stop once we find the table
        }

        // Update the user with the reset token
        $updateQuery = "
            UPDATE $table
            SET reset_token = ?, token_expiry = ?
            WHERE email = ?
        ";

        $updateStmt = sqlsrv_prepare($conn, $updateQuery, array($token, $expiryTime, $email));
        if (!$updateStmt) {
            die(print_r(sqlsrv_errors(), true));
        }

        $updateResult = sqlsrv_execute($updateStmt);
        if ($updateResult === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Simulate sending email with reset link
        $resetLink = "https://example.com/resetPassword.php?token=$token";
        // Uncomment and configure mail functionality if needed
        // mail($email, "Reset Password", "Click this link to reset your password: $resetLink");

        echo "<script>
                alert('Link pemulihan telah dikirim ke email Anda.');
                window.location.href = 'loginPage.html';
              </script>";
    } else {
        echo "<script>
                alert('Email tidak terdaftar!');
                window.history.back();
              </script>";
    }
} else {
    header("Location: lupaPassword.html");
    exit();
}
?>
