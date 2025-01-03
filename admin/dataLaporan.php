<?php
include 'getAdminName.php';
// Query to get student's data
$query = "
    SELECT s.staff_id, s.nama_staff, s.staff_img 
    FROM staff s
    JOIN users u ON s.staff_id = u.staff_id
    WHERE u.user_id = ?
";
$params = array($user_id);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch the student's data
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Data Laporan Pelanggaran - Dashboard Admin</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../fonts/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../fonts/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">


    <style>
        .main-header .navbar {
            background-color: #115599 !important;
        }

        .truncate {
            white-space: nowrap;
            /* Mencegah teks membungkus ke baris baru */
            overflow: hidden;
            /* Menyembunyikan teks yang melampaui batas */
            text-overflow: ellipsis;
            /* Menambahkan ellipsis (...) untuk teks yang terpotong */
            max-width: 150px;
            /* Atur lebar maksimum sesuai kebutuhan */
        }

        .page-title {
            font-size: 40px;
            font-weight: bold;
            color: #115599;
            text-align: left;
            margin-bottom: 30px;
        }

        .user-panel {
            display: flex;
            align-items: center;
        }

        .user-panel .pull-left.image {
            margin-right: 15px;
            /* Space between image and name */
        }

        .user-panel .pull-left.image img {
            border-radius: 50%;
            /* Makes the image circular */
            width: 45px;
            /* Adjust the size of the profile image */
            height: 45px;
            /* Adjust the size of the profile image */
            object-fit: cover;
            /* Ensures the image fits well inside the circle */
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <a href="dashboardAdmin.php" class="logo">
                <span class="logo-mini"><b>S</b>TB</span>
                <span class="logo-lg">SI<b>TATIB</b></span>
            </a>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
            </nav>
        </header>

        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel" onclick="window.location.href='Profile.php'" style="cursor: pointer;">
                    <div class="pull-left image">
                        <img src="<?php echo htmlspecialchars($data['staff_img']); ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo htmlspecialchars($nama_admin); ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <ul class="sidebar-menu">
                    <li class="header">MAIN NAVIGATION</li>
                    <li><a href="dashboardAdmin.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li><a href="dataMahasiswa.php"><i class="fa fa-users"></i> <span>Data Mahasiswa</span></a></li>
                    <li><a href="dataDosen.php"><i class="fa fa-users"></i> <span>Data Dosen</span></a></li>
                    <li><a href="dataDPA.php"><i class="fa fa-file-text-o"></i> <span>Data DPA</span></a></li>
                    <li class="active"><a href="dataLaporan.php"><i class="fa fa-file-text-o"></i> <span>Data Laporan Pelanggaran</span></a></li>
                    <li><a href="dataKompensasi.php"><i class="fa fa-file-text-o"></i> <span>Data Kompensasi</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Data Laporan Pelanggaran</h1>
            </section>

            <section class="content">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Daftar Laporan Pelanggaran</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text" id="searchLaporan" class="form-control" placeholder="Search">
                            </div>
                        </div>
                        <table id="laporanTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID Laporan</th>
                                    <th>Dosen Pelapor</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Pelanggaran</th>
                                    <th>Tingkat</th>
                                    <th>Sanksi</th>
                                    <th>Tanggal Pengaduan</th>
                                    <th>Status Pengaduan</th>
                                    <th>Catatan</th>
                                    <th>Bukti Pelanggaran</th>
                                    <th style="width: 135px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer with-border">
                        <button class="btn btn-info pull-right" id="rekapLaporanBtn">Rekap Data</button>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b><a href="https://jti.polinema.ac.id/" target="_blank">Jurusan Teknologi Informasi</a></b>
            </div>
            <strong><a href="https://polinema.ac.id" target="_blank">Politeknik Negeri Malang</a></strong>
        </footer>

    </div>


    <div id="detailLaporanModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Detail Laporan</h4>
                </div>
                <div class="modal-body">
                    <form id="detailLaporanForm">
                        <div class="form-group">
                            <label for="dosenNama">Nama Dosen</label>
                            <input type="text" class="form-control" id="dosenNama" readonly>
                        </div>
                        <div class="form-group">
                            <label for="mahasiswaNama">Nama Mahasiswa</label>
                            <input type="text" class="form-control" id="mahasiswaNama" readonly>
                        </div>
                        <div class="form-group">
                            <label for="pelanggaran">Pelanggaran</label>
                            <textarea class="form-control" id="pelanggaran" rows="3" readonly></textarea>
                        </div>
                        <div class="form-group">
                            <label for="tingkat">Tingkat</label>
                            <input type="text" class="form-control" id="tingkat" readonly>
                        </div>
                        <div class="form-group">
                            <label for="sanksi">Sanksi</label>
                            <textarea class="form-control" id="sanksi" rows="6" readonly></textarea>
                        </div>
                        <div class="form-group">
                            <label for="tanggalPengaduan">Tanggal Pengaduan</label>
                            <input type="text" class="form-control" id="tanggalPengaduan" readonly>
                        </div>
                        <div class="form-group">
                            <label for="statusPengaduan">Status Pengaduan</label>
                            <input type="text" class="form-control" id="statusPengaduan" readonly>
                        </div>
                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea class="form-control" id="catatan" rows="3" readonly></textarea>
                        </div>
                        <div class="form-group">
                            <label for="buktiPelanggaran">Bukti Pelanggaran</label>
                            <img id="buktiPelanggaran" src="" alt="Bukti Pelanggaran" style="max-width: 100%; height: auto; display: none;">
                        </div>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div id="editLaporanModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Status Pengaduan</h4>
                </div>
                <div class="modal-body">
                    <form id="editLaporanForm">
                        <input type="hidden" id="editPengaduanId">
                        <div class="form-group">
                            <label for="editStatusPengaduan">Status Pengaduan</label>
                            <select class="form-control" id="editStatusPengaduan">
                                <option value="valid">Valid</option>
                                <option value="tidak valid">Tidak Valid</option>
                                <option value="proses">Proses</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" id="saveEditLaporan">Simpan</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery 2.2.3 -->
    <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../dist/js/demo.js"></script>
    <!-- DataTable (For displaying data in the table) -->
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

            function fetchLaporan() {
                $.ajax({
                    url: 'getLaporan.php',
                    method: 'GET',
                    success: function(data) {
                        // Menampilkan data laporan pelanggaran ke dalam tabel
                        $('#laporanTable tbody').html(data);

                        // Menambahkan tombol berwarna pada kolom status_pengaduan
                        $('#laporanTable tbody tr').each(function() {
                            var statusPengaduan = $(this).find('td').eq(8).text().trim(); // Status Pengaduan berada di kolom ke-9 (indeks 8)

                            var statusClass = ''; // Kelas untuk tombol
                            var statusText = '';

                            switch (statusPengaduan) {
                                case 'baru':
                                    statusClass = 'btn-primary'; // Warna biru
                                    statusText = 'Baru';
                                    break;
                                case 'proses':
                                    statusClass = 'btn-warning'; // Warna kuning
                                    statusText = 'Proses';
                                    break;
                                case 'valid':
                                    statusClass = 'btn-success'; // Warna hijau
                                    statusText = 'Valid';
                                    break;
                                case 'tidak valid':
                                    statusClass = 'btn-danger'; // Warna merah
                                    statusText = 'Tidak Valid';
                                    break;
                                default:
                                    statusClass = 'btn-secondary'; // Warna abu-abu jika status tidak dikenali
                                    statusText = 'Tidak Dikenal';
                            }

                            // Gantikan teks status pengaduan dengan tombol berwarna
                            $(this).find('td').eq(8).html('<button class="btn ' + statusClass + ' btn-sm">' + statusText + '</button>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Terjadi kesalahan saat mengambil data laporan.");
                    }
                });
            }

            // Search 
            $('#searchLaporan').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#laporanTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });


            fetchLaporan();


            $(document).on('click', '.detailBtn', function() {
                var laporanId = $(this).data('id');
                // Ambil data detail laporan berdasarkan laporanId
                $.ajax({
                    url: 'getDetailLaporan.php',
                    method: 'GET',
                    data: {
                        id: laporanId
                    },
                    success: function(data) {
                        var detail = JSON.parse(data);

                        if (detail.error) {
                            alert(detail.error);
                        } else {
                            // Update modal
                            $('#dosenNama').val(detail.dosen_nama);
                            $('#mahasiswaNama').val(detail.mahasiswa_nama);
                            $('#pelanggaran').val(detail.pelanggaran);
                            $('#tingkat').val(detail.tingkat);
                            $('#sanksi').val(detail.sanksi);
                            $('#tanggalPengaduan').val(detail.tanggal_pengaduan);
                            $('#statusPengaduan').val(detail.status_pengaduan);
                            $('#catatan').val(detail.catatan);
                            if (detail.bukti_pelanggaran) {
                                $('#buktiPelanggaran').attr('src', detail.bukti_pelanggaran);
                                $('#buktiPelanggaran').show();
                            } else {
                                $('#buktiPelanggaran').attr('href', '#');
                                $('#buktiPelanggaran').hide()
                            }
                            // Tampilkan modal
                            $('#detailLaporanModal').modal('show');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Terjadi kesalahan saat mengambil data laporan.");
                    }
                });
            });
            $(document).on('click', '.editBtn', function() {
                var laporanId = $(this).data('id');
                var status = $(this).data('status');

                $('#editPengaduanId').val(laporanId);
                $('#editStatusPengaduan').val(status);
                $('#editLaporanModal').modal('show');
            });

            $('#saveEditLaporan').on('click', function() {
                var laporanId = $('#editPengaduanId').val();
                var newStatus = $('#editStatusPengaduan').val();

                $.ajax({
                    url: 'updateLaporan.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: laporanId,
                        status: newStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            $('#editLaporanModal').modal('hide');
                            fetchLaporan();
                        } else {
                            alert('Gagal: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Terjadi kesalahan saat mengupdate status pengaduan.");
                    }
                });
            });

            $(document).on('click', '#rekapLaporanBtn', function() {
                $.ajax({
                    url: 'rekapLaporan.php',
                    method: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Redirect ke file PDF yang dihasilkan
                            window.open(response.pdf_url, '_blank');
                        } else {
                            alert('Gagal: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Terjadi kesalahan saat merekap laporan.");
                    }
                });
            });




        });
    </script>
</body>

</html>