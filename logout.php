<?php
// Mulai sesi
session_start();

// Hapus semua session
session_unset();

// Hapus sesi yang ada
session_destroy();

// Redirect ke halaman login
header("Location: loginPage.html");
exit();
?>
