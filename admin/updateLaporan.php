<?php
include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $query = "UPDATE pengaduan SET status_pengaduan = ? WHERE pengaduan_id = ?";
    $params = array($status, $id);

    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Kesalahan saat mengupdate data.', 'error' => sqlsrv_errors()]);
    } else {
        echo json_encode(['success' => true, 'message' => 'Status pengaduan berhasil diperbarui.']);
    }
    sqlsrv_close($conn);
} else {
    echo json_encode(['success' => false, 'message' => 'Metode request tidak valid.']);
}
?>
