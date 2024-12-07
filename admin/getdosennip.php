<?php
include('../koneksi.php');

if (isset($_GET['nip'])) {
    $nip = $_GET['nip'];

    $query = "SELECT * FROM dosen WHERE nip = ?";
    $params = array($nip);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        $dosen = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo json_encode($dosen);
    } else {
        echo "Error: " . print_r(sqlsrv_errors(), true);
    }
} else {
    echo "NIP tidak ditemukan.";
}
?>
