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
    <title>Data Kompensasi - Dashboard Admin</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fonts/font-awesome.min.css">
    <link rel="stylesheet" href="../fonts/ionicons.min.css">
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
    <style>
        .main-header .navbar {
            background-color: #115599 !important;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table th,
        table td {
            white-space: nowrap;
        }

        #searchStudent {
            margin-bottom: 15px;
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
                    <li><a href="dashboardAdmin.php"><i class="fa fa-dashboard"></i> <span> Dashboard</span></a></li>
                    <li><a href="dataMahasiswa.php"><i class="fa fa-users"></i> <span>Data Mahasiswa</span></a></li>
                    <li><a href="dataDosen.php"><i class="fa fa-users"></i> <span>Data Dosen</span></a></li>
                    <li><a href="dataDPA.php"><i class="fa fa-file-text-o"></i> <span>Data DPA</span></a></li>
                    <li><a href="dataLaporan.php"><i class="fa fa-file-text-o"></i> <span>Data Laporan Pelanggaran</span></a></li>
                    <li class="active"><a href="dataKompensasi.php"><i class="fa fa-file-text-o"></i> <span>Data Kompensasi</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Data Kompensasi</h1>
            </section>

            <section class="content">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Daftar Riwayat Kompensasi</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="riwayatTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Riwayat ID</th>
                                        <th>NIM</th>
                                        <th>Pengaduan ID</th>
                                        <th>Status Kompen</th>
                                        <th>Catatan Kompen</th>
                                        <th>Bukti Kompen</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data Riwayat Kompensasi Akan Ditampilkan Di Sini -->
                                </tbody>
                            </table>
                        </div>
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

    <!-- Modal Edit Status -->
    <div class="modal fade" id="editStatusModal" tabindex="-1" role="dialog" aria-labelledby="editStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStatusModalLabel">Edit Status Kompensasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editStatusForm">
                    <div class="modal-body">
                        <input type="hidden" id="riwayatId" name="riwayat_id">
                        <div class="form-group">
                            <label for="status_kompen">Pilih Status Kompensasi:</label>
                            <select class="form-control" id="status_kompen" name="status_kompen">
                                <option value="baru">Baru</option>
                                <option value="proses">Proses</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="../plugins/fastclick/fastclick.js"></script>
    <script src="../dist/js/app.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fungsi untuk validasi kompensasi dan langsung menampilkan modal edit status
            $(document).on('click', '.validRiwayat', function() {
                var riwayatId = $(this).data('id');
                var currentStatus = 'proses'; // Set default status

                // Set riwayat_id ke dalam input hidden
                $('#riwayatId').val(riwayatId);
                $('#status_kompen').val(currentStatus);

                // Tampilkan modal
                $('#editStatusModal').modal('show');
            });

            // Fungsi untuk mengedit status kompensasi
            $('#editStatusForm').on('submit', function(e) {
                e.preventDefault();
                var riwayatId = $('#riwayatId').val();
                var statusKompensasi = $('#status_kompen').val();

                $.ajax({
                    url: 'validasiKompensasi.php',
                    method: 'POST',
                    data: {
                        riwayat_id: riwayatId,
                        status_kompen: statusKompensasi
                    },
                    success: function(response) {
                        if (response == 'success') {
                            alert('Status kompensasi berhasil diubah!');
                            $('#editStatusModal').modal('hide');
                            location.reload();
                        } else {
                            alert('Terjadi kesalahan saat mengubah status kompensasi.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Terjadi kesalahan pada server.');
                    }
                });
            });

            // Fungsi untuk mengambil data riwayat kompensasi
            function fetchRiwayat() {
                $.ajax({
                    url: 'getRiwayat.php',
                    method: 'GET',
                    success: function(data) {
                        $('#riwayatTable tbody').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Terjadi kesalahan saat mengambil data riwayat kompensasi.");
                    }
                });
            }

            fetchRiwayat();
        });
    </script>
</body>

</html>