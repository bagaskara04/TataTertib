<?php
// Mulai sesi
session_start();

session_unset();

// Hapus sesi
session_destroy();

// Redirect ke halaman login
header("Location: loginPage.html");
exit();
?>
