<?php

$host = "localhost";
$connInfo = array(
    "Database" => "tatib", 
    "UID" => "", 
    "PWD" => ""
);

// Coba koneksi
$conn = sqlsrv_connect($host, $connInfo);

if ($conn) {
    echo "Koneksi Berhasil.<br/>";
} else {
    echo "Koneksi Gagal.<br/>";
    die(print_r(sqlsrv_errors(), true));
}
?>
