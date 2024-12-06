<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Update password di database
    $query = "UPDATE users SET password = '$password' WHERE username = '$nim'";  // Pastikan kolom username sesuai
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
} else if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h3>Reset Password</h3>
    <form action="resetPassword.php" method="POST">
        <input type="hidden" name="nim" value="<?php echo htmlspecialchars($nim); ?>">
        <label for="password">Masukkan Kata Sandi Baru</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
<?php
} else {
    header("Location: loginPage.html");
    exit();
}
?>
