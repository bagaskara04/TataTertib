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

// Buat instance PDF dan FPDF_Table
$pdf = new PDF(); // Pastikan menggunakan FPDF_Table yang sudah extend FPDF
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Rekap Data Laporan Pengaduan', 0, 1, 'C');

// Set lebar kolom dan header
$header = ['ID', 'Dosen', 'Mahasiswa', 'NIM', 'Pelanggaran', 'Sanksi', 'Status'];
$pdf->SetWidths([20, 40, 40, 20, 40, 30, 30]); // Set lebar kolom

// Set header
$pdf->Row($header);

// Isi data tabel
$pdf->SetFont('Arial', '', 10);
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $data = [
        $row['pengaduan_id'],
        $row['dosen_nama'],
        $row['mahasiswa_nama'],
        $row['nim'],
        $row['pelanggaran'],
        $row['sanksi'],
        $row['status_pengaduan']
    ];
    $pdf->Row($data); // Tambahkan baris data
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
