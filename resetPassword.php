<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Validasi input
    if (empty($username) || empty($current_password) || empty($new_password)) {
        die("Semua kolom wajib diisi.");
    }

    // Cek user dan password saat ini
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $params = array($username, $current_password);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($stmt)) {
        // User ditemukan dan password cocok
        $update_query = "UPDATE users SET password = ? WHERE username = ?";
        $update_params = array($new_password, $username);
        $update_stmt = sqlsrv_query($conn, $update_query, $update_params);

        if ($update_stmt) {
            echo "<script>
                alert('Password berhasil dirubah');
                window.location.href = 'loginPage.html';
            </script>";
            exit(); // Menghentikan eksekusi untuk mencegah konten lainnya ditampilkan
        } else {
            echo "<script>
                alert('Terjadi kesalahan saat memperbarui password.');
                window.location.href = 'lupaPassword.html';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            alert('Username atau password saat ini salah.');
            window.location.href = 'lupaPassword.html';
        </script>";
        exit();
    }

    // Menutup statement
    sqlsrv_free_stmt($stmt);
} else {
    echo "Metode tidak valid.";
}

// Menutup koneksi
sqlsrv_close($conn);
?>
