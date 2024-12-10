<?php
include('../koneksi.php');

$nim = $_GET['nim'];
$query = "SELECT * FROM dbo.mahasiswa WHERE nim = ?";
$params = array($nim);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt) {
    $student = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    echo json_encode($student);
} else {
    echo "Error fetching student data.";
}

sqlsrv_close($conn);
?>
