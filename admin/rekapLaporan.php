<?php
// Gunakan require_once untuk menghindari pemanggilan dua kali
require_once('../fpdf/fpdf.php');  // Memastikan fpdf.php dimuat sekali
require_once('../fpdf/FPDF_Table.php');  // FPDF_Table tidak mendeklarasikan kelas FPDF lagi

include '../koneksi.php'; // Koneksi ke database

// Fetch laporan pelanggaran berdasarkan query yang baru
$query = "
    SELECT 
        p.pengaduan_id,
        d.nama AS dosen_nama,
        m.nama AS mahasiswa_nama,
        m.nim,
        pelang.tingkat,
        FORMAT(p.tanggal_pengaduan, 'yyyy-MM-dd HH:mm:ss') AS tanggal_pengaduan,
        p.status_pengaduan,
        p.catatan
    FROM pengaduan p
    JOIN dosen d ON p.nip = d.nip
    JOIN mahasiswa m ON p.nim = m.nim
    JOIN pelanggaran pelang ON p.pelanggaran_id = pelang.pelanggaran_id
    ORDER BY p.pengaduan_id DESC
";
$stmt = sqlsrv_query($conn, $query);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Gagal mengambil data laporan']);
    exit;
}

// Buat instance PDF dan FPDF_Table
$pdf = new FPDF_Table('L', 'mm', 'A4'); // Mengubah menjadi Landscape
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Rekap Data Laporan Pengaduan', 0, 1, 'C');

// Set header
$header = ['ID', 'Dosen Pelapor', 'Nama Mahasiswa', 'NIM', 'Tingkat', 'Tanggal Pengaduan', 'Status', 'Catatan'];

// Tentukan lebar kolom
$pdf->SetWidths([5, 60, 50, 25, 20, 40, 25, 60]); // Lebar kolom sesuai panjang data

// Tentukan perataan kolom header
$pdf->SetAligns(['C', 'C', 'C', 'C', 'C', 'C', 'C', 'C']);  // Atur agar header dan data sejajar dengan baik

// Set header
$pdf->Row($header);

// Isi data tabel
$pdf->SetFont('Arial', '', 10);

// Menyimpan panjang teks kolom untuk mengatur lebar kolom secara dinamis
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Data untuk setiap baris
    $pdf->Cell($pdf->widths[0], 7, $row['pengaduan_id'], 1, 0, 'C');  // ID
    $pdf->Cell($pdf->widths[1], 7, $row['dosen_nama'], 1, 0, 'L');  // Dosen Pelapor
    $pdf->Cell($pdf->widths[2], 7, $row['mahasiswa_nama'], 1, 0, 'L');  // Nama Mahasiswa
    $pdf->Cell($pdf->widths[3], 7, $row['nim'], 1, 0, 'C');  // NIM
    $pdf->Cell($pdf->widths[4], 7, $row['tingkat'], 1, 0, 'L');  // Tingkat
    $pdf->Cell($pdf->widths[5], 7, $row['tanggal_pengaduan'], 1, 0, 'C');  // Tanggal Pengaduan
    $pdf->Cell($pdf->widths[6], 7, $row['status_pengaduan'], 1, 0, 'C');  // Status Pengaduan
    $pdf->MultiCell($pdf->widths[7], 7, $row['catatan'], 1, 'L');  // Catatan

    $pdf->Ln();  // Pindah ke baris berikutnya
}

// Simpan PDF
$fileName = 'Rekap_Laporan_' . date('Y-m') . '.pdf';
$filePath = 'rekap/' . $fileName; // Ganti ke folder pdf di atas direktori

// Membuat folder jika belum ada
if (!file_exists('../pdf')) {
    mkdir('../pdf', 0777, true);
}

// Output PDF ke file
$pdf->Output('F', $filePath);

// Mengembalikan URL PDF yang telah dibuat
echo json_encode(['success' => true, 'pdf_url' => $filePath]);
?>
