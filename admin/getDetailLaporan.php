<?php
// Sertakan koneksi database
include('../koneksi.php');

// Cek jika parameter 'id' ada di dalam request GET
if (isset($_GET['id'])) {
    $laporanId = $_GET['id'];

    // Base URL untuk folder uploads
    $baseUrl = 'http://localhost/TataTertib/laporanPelanggaran/';

    // Query SQL untuk menggabungkan tabel yang diperlukan untuk mengambil detail laporan
    $query = "
        SELECT 
            p.pengaduan_id, 
            d.nama AS dosen_nama,
            m.nama AS mahasiswa_nama,
            m.nim,
            pelang.pelanggaran,
            pelang.tingkat,
            sp.nama_sanksi AS sanksi,
            FORMAT(p.tanggal_pengaduan, 'yyyy-MM-dd HH:mm:ss') AS tanggal_pengaduan,
            p.status_pengaduan,
            p.catatan,
            CASE 
                WHEN p.bukti_pelanggaran IS NOT NULL 
                THEN CONCAT('$baseUrl', p.bukti_pelanggaran) 
                ELSE NULL 
            END AS bukti_pelanggaran -- Menambahkan path lengkap ke bukti pelanggaran
        FROM pengaduan p
        JOIN dosen d ON p.nip = d.nip
        JOIN mahasiswa m ON p.nim = m.nim
        JOIN pelanggaran pelang ON p.pelanggaran_id = pelang.pelanggaran_id
        JOIN sanksi_pelanggaran sp ON pelang.sanksi_id = sp.sanksi_id
        WHERE p.pengaduan_id = ?
    ";

    // Menyiapkan statement SQL dan mengikat parameter
    if ($stmt = sqlsrv_prepare($conn, $query, array(&$laporanId))) {
        if (sqlsrv_execute($stmt)) {
            $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            // Jika data ditemukan, return data dalam format JSON
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'Data tidak ditemukan.']);
            }
        } else {
            echo json_encode(['error' => 'Gagal mengeksekusi query.']);
        }
    } else {
        echo json_encode(['error' => 'Gagal mempersiapkan query.']);
    }
} else {
    // Jika parameter 'id' tidak ada
    echo json_encode(['error' => 'ID laporan diperlukan.']);
}

// Menutup koneksi database
sqlsrv_close($conn);
?>
