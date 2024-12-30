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
    <title>Data Mahasiswa</title>
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
            /* Hindari pemotongan teks */
        }

        #searchStudent {
            margin-bottom: 15px;
            /* Mengatur jarak bawah tombol search */
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
                    <li class="active"><a href="dataMahasiswa.php"><i class="fa fa-users"></i> <span>Data Mahasiswa</span></a></li>
                    <li><a href="dataDosen.php"><i class="fa fa-users"></i> <span>Data Dosen</span></a></li>
                    <li><a href="dataDPA.php"><i class="fa fa-file-text-o"></i> <span>Data DPA</span></a></li>
                    <li><a href="dataLaporan.php"><i class="fa fa-file-text-o"></i> <span>Data Laporan Pelanggaran</span></a></li>
                    <li><a href="dataKompensasi.php"><i class="fa fa-file-text-o"></i> <span>Data Kompensasi</span></a></li>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
                </ul>
            </section>
        </aside>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Data Mahasiswa</h1>
            </section>

            <section class="content">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Daftar Mahasiswa</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text" id="searchStudent" class="form-control" placeholder="Search">
                            </div>
                            <div class="col-md-2 text-right">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#addStudentModal">Tambah Mahasiswa</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="studentTable">
                                <thead>
                                    <tr>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>TTL</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Alamat</th>
                                        <th>Email</th>
                                        <th>No. Telepon</th>
                                        <th>Prodi</th>
                                        <th>Kelas</th>
                                        <th>Phone Ortu</th>
                                        <th>Tingkat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- Modal Tambah Mahasiswa -->
        <div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStudentLabel">Tambah Mahasiswa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addStudentForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nim">NIM</label>
                                <input type="text" class="form-control" id="nim" name="nim" required>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="form-group">
                                <label for="ttl">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="ttl" name="ttl" required>
                            </div>
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="L">Laki - laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="no_phone">No. Telepon</label>
                                <input type="text" class="form-control" id="no_phone" name="no_phone" required>
                            </div>
                            <div class="form-group">
                                <label for="prodi">Program Studi</label>
                                <select class="form-control" id="prodi" name="prodi_id" required>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <select class="form-control" id="kelas" name="kelas_id" required>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="phone_ortu">No. Telepon Orang Tua</label>
                                <input type="text" class="form-control" id="phone_ortu" name="phone_ortu" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Mahasiswa -->
        <div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStudentLabel">Edit Data Mahasiswa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editStudentForm">
                        <div class="modal-body">
                            <input type="hidden" id="edit_nim" name="nim">
                            <div class="form-group">
                                <label for="edit_nama">Nama</label>
                                <input type="text" class="form-control" id="edit_nama" name="nama" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_ttl">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="edit_ttl" name="ttl" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                                <select class="form-control" id="edit_jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="L">Laki - laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_alamat">Alamat</label>
                                <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_email">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_no_phone">No. Telepon</label>
                                <input type="text" class="form-control" id="edit_no_phone" name="no_phone" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_prodi">Program Studi</label>
                                <select class="form-control" id="edit_prodi" name="prodi_id" required>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_kelas">Kelas</label>
                                <select class="form-control" id="edit_kelas" name="kelas_id" required>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_phone_ortu">No. Telepon Orang Tua</label>
                                <input type="text" class="form-control" id="edit_phone_ortu" name="phone_ortu" required>
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
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b><a href="https://jti.polinema.ac.id/" target="_blank">Jurusan Teknologi Informasi</a></b>
            </div>
            <strong><a href="https://polinema.ac.id" target="_blank">Politeknik Negeri Malang</a></strong>
        </footer>
    </div>

    <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="../plugins/fastclick/fastclick.js"></script>
    <script src="../dist/js/app.min.js"></script>
    <script src="../dist/js/demo.js"></script>

    <script>
        $(document).ready(function() {
            // Data Mahasiswa
            function fetchStudents() {
                $.ajax({
                    url: 'get_student.php',
                    method: 'GET',
                    success: function(data) {
                        $('#studentTable tbody').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Terjadi kesalahan saat mengambil data mahasiswa.");
                    }
                });
            }

            // Search 
            $('#searchStudent').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#studentTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Fungsi data Prodi dan dropdown Prodi
            function fetchProdi() {
                $.ajax({
                    url: 'get_prodi.php',
                    method: 'GET',
                    success: function(data) {
                        $('#prodi').empty().append('<option value="">Pilih Prodi</option>');
                        $('#prodi').append(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Terjadi kesalahan saat mengambil data Prodi.");
                    }
                });
            }

            // Fungsi untuk data kelas
            function fetchKelas(prodiId) {
                if (!prodiId) {
                    $('#kelas').empty().append('<option value="">Pilih Kelas</option>');
                    return;
                }

                $.ajax({
                    url: 'get_kelas.php',
                    method: 'GET',
                    data: {
                        prodi_id: prodiId
                    }, // Kirim prodi_id
                    success: function(data) {
                        $('#kelas').empty().append('<option value="">Pilih Kelas</option>');
                        $('#kelas').append(data); // dropdown Kelas
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Terjadi kesalahan saat mengambil data Kelas.");
                    }
                });
            }

            // Panggil fungsi fetchStudents
            fetchStudents();

            // menampilkan prodi edit
            $('#addStudentModal').on('show.bs.modal', function() {
                fetchProdi();
                $('#kelas').empty().append('<option value="">Pilih Kelas</option>');
            });

            //panggil fetchKelas untuk memuat data Kelas
            $('#prodi').on('change', function() {
                const selectedProdiId = $(this).val();
                fetchKelas(selectedProdiId); // data kelas berdasarkan Prodi
            });

            // Fungsi untuk menghapus Mahasiswa
            $(document).on('click', '.deleteStudent', function() {
                const studentId = $(this).data('id');
                if (confirm('Yakin ingin menghapus data ini?')) {
                    $.ajax({
                        url: 'deleteMahasiswa.php',
                        method: 'POST',
                        data: {
                            id: studentId
                        },
                        success: function(response) {
                            alert(response);
                            fetchStudents();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            alert("Terjadi kesalahan saat menghapus data mahasiswa.");
                        }
                    });
                }
            });

            // fungsi modal edit
            $(document).on('click', '.editStudent', function() {
                const studentId = $(this).data('id'); // Mendapatkan ID mahasiswa

                // Memuat data pada modal
                $.ajax({
                    url: 'get_student_by_nim.php', // mengambil data mahasiswa berdasarkan NIM
                    method: 'GET',
                    data: {
                        nim: studentId
                    },
                    success: function(data) {
                        // Isi data ke dalam form edit
                        const student = JSON.parse(data);
                        $('#edit_nim').val(student.nim);
                        $('#edit_nama').val(student.nama);
                        $('#edit_ttl').val(student.ttl);
                        $('#edit_jenis_kelamin').val(student.jenis_kelamin);
                        $('#edit_alamat').val(student.alamat);
                        $('#edit_email').val(student.email);
                        $('#edit_no_phone').val(student.no_phone);
                        $('#edit_phone_ortu').val(student.phone_ortu);
                        fetchProdiEdit(student.prodi_id);
                        fetchKelasEdit(student.prodi_id, student.kelas_id);
                        $('#editStudentModal').modal('show'); // modal edit
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Terjadi kesalahan saat memuat data mahasiswa.");
                    }
                });
            });

            // Fungsi data Prodi pada modal Edit
            function fetchProdiEdit(selectedProdiId) {
                $.ajax({
                    url: 'get_prodi.php',
                    method: 'GET',
                    success: function(data) {
                        $('#edit_prodi').empty().append('<option value="">Pilih Prodi</option>');
                        $('#edit_prodi').append(data);
                        $('#edit_prodi').val(selectedProdiId);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Terjadi kesalahan saat mengambil data Prodi.");
                    }
                });
            }

            // Fungsi data kelas pada modal edit
            function fetchKelasEdit(prodiId, selectedKelasId) {
                $.ajax({
                    url: 'get_kelas.php',
                    method: 'GET',
                    data: {
                        prodi_id: prodiId
                    },
                    success: function(data) {
                        $('#edit_kelas').empty().append('<option value="">Pilih Kelas</option>');
                        $('#edit_kelas').append(data);
                        $('#edit_kelas').val(selectedKelasId);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Terjadi kesalahan saat mengambil data Kelas.");
                    }
                });
            }

            // Fungsi untuk memperbarui data mahasiswa
            $('#editStudentForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: 'updateMahasiswa.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        $('#editStudentModal').modal('hide');
                        fetchStudents();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Terjadi kesalahan saat menyimpan perubahan.");
                    }
                });
            });

            // Tambah Mahasiswa
            $('#addStudentForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: 'addMahasiswa.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        $('#addStudentModal').modal('hide');
                        $('#addStudentForm')[0].reset();
                        fetchStudents();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Terjadi kesalahan saat menyimpan data.");
                    }
                });
            });
        });
    </script>



</body>

</html>