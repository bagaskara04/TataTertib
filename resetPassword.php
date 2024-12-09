<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Enkripsi password menggunakan bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Update password di database
    $query = "UPDATE users SET password = '$hashedPassword' WHERE username = '$username'";
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Password berhasil diperbarui! Silakan login.');
                window.location.href = 'loginPage.html';
              </script>";
    } else {
        echo "<script>
                alert('Terjadi kesalahan. Silakan coba lagi.');
                window.history.back();
              </script>";
    }
} else if (isset($_GET['username'])) {
    $username = $_GET['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css"> <!-- Tambahkan CSS jika diperlukan -->
</head>
<body>
    <div class="reset-password-container">
        <h3>Reset Password</h3>
        <form action="resetPassword.php" method="POST">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
            <label for="password">Masukkan Kata Sandi Baru</label>
            <input type="password" name="password" id="password" placeholder="Kata Sandi Baru" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
<?php
} else {
    header("Location: loginPage.html");
    exit();
}
?>
