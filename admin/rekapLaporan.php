<?php
require('../fpdf/fpdf.php'); // Pastikan library FPDF sudah tersedia
include '../koneksi.php'; // Koneksi ke database

// Fetch laporan pelanggaran berdasarkan query yang baru
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
        p.bukti_pelanggaran
    FROM pengaduan p
    JOIN dosen d ON p.nip = d.nip
    JOIN mahasiswa m ON p.nim = m.nim
    JOIN pelanggaran pelang ON p.pelanggaran_id = pelang.pelanggaran_id
    JOIN sanksi_pelanggaran sp ON pelang.sanksi_id = sp.sanksi_id
    ORDER BY p.pengaduan_id DESC
";
$stmt = sqlsrv_query($conn, $query);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Gagal mengambil data laporan']);
    exit;
}

// Buat instance PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Rekap Data Laporan Pengaduan', 0, 1, 'C');

// Header tabel
$pdf->SetFont('Arial', 'B', 10);

// Tentukan lebar kolom secara default
$columnWidths = [
    20, // ID
    40, // Dosen
    40, // Mahasiswa
    20, // NIM
    40, // Pelanggaran
    30, // Sanksi
    30, // Status
];

$header = ['ID', 'Dosen', 'Mahasiswa', 'NIM', 'Pelanggaran', 'Sanksi', 'Status'];

foreach ($header as $key => $title) {
    $pdf->Cell($columnWidths[$key], 10, $title, 1);
}
$pdf->Ln();

// Isi tabel
$pdf->SetFont('Arial', '', 10);
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Tentukan lebar kolom sesuai panjang data
    $columnWidths[0] = max(20, $pdf->GetStringWidth($row['pengaduan_id']) + 4);
    $columnWidths[1] = max(40, $pdf->GetStringWidth($row['dosen_nama']) + 4);
    $columnWidths[2] = max(40, $pdf->GetStringWidth($row['mahasiswa_nama']) + 4);
    $columnWidths[3] = max(20, $pdf->GetStringWidth($row['nim']) + 4);
    $columnWidths[4] = max(40, $pdf->GetStringWidth($row['pelanggaran']) + 4);
    $columnWidths[5] = max(30, $pdf->GetStringWidth($row['sanksi']) + 4);
    $columnWidths[6] = max(30, $pdf->GetStringWidth($row['status_pengaduan']) + 4);

    // Isi data
    $pdf->Cell($columnWidths[0], 10, $row['pengaduan_id'], 1);
    $pdf->Cell($columnWidths[1], 10, $row['dosen_nama'], 1);
    $pdf->Cell($columnWidths[2], 10, $row['mahasiswa_nama'], 1);
    $pdf->Cell($columnWidths[3], 10, $row['nim'], 1);
    $pdf->Cell($columnWidths[4], 10, $row['pelanggaran'], 1);
    $pdf->Cell($columnWidths[5], 10, $row['sanksi'], 1);
    $pdf->Cell($columnWidths[6], 10, $row['status_pengaduan'], 1);
    $pdf->Ln();
}

// Simpan PDF
$fileName = 'Rekap_Laporan_' . date('Y-m') . '.pdf';
$filePath = 'rekap/' . $fileName;
$pdf->Output('F', $filePath);

if (!file_exists('../pdf')) {
    mkdir('../pdf', 0777, true);
}

echo json_encode(['success' => true, 'pdf_url' => $filePath]);
?>
