<?php
include 'getAdminName.php';
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
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../dist/img/profile3.png" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                <p><?php echo htmlspecialchars($nama_admin);?></p>
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
                        <div class="col-md-2">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addStudentModal">Tambah Mahasiswa</button>
                        </div>
                    </div>
                    <table class="table table-bordered" id="studentTable">
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
                                <th>Jumlah Pelanggaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data fetched dynamically -->
                        </tbody>
                    </table>
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
                            <!-- Data Prodi akan diisi dengan Ajax -->
                        </select>
                    </div>
            
                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <select class="form-control" id="kelas" name="kelas_id" required>
                            <!-- Data Kelas akan diisi dengan Ajax -->
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
                <h5 class="modal-title" id="editStudentLabel">Edit Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editStudentForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_nim" name="nim"> <!-- Hidden field for NIM -->
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
                            <!-- Data Prodi akan diisi dengan Ajax -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_kelas">Kelas</label>
                        <select class="form-control" id="edit_kelas" name="kelas_id" required>
                            <!-- Data Kelas akan diisi dengan Ajax -->
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



</div>

<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../dist/js/app.min.js"></script>

<script>
    $(document).ready(function () {
        // Fungsi untuk mengambil data mahasiswa dan mengisi tabel
        function fetchStudents() {
            $.ajax({
                url: 'get_student.php',
                method: 'GET',
                success: function (data) {
                    $('#studentTable tbody').html(data);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat mengambil data mahasiswa.");
                }
            });
        }

                // Search functionality
            $('#searchStudent').on('keyup', function () {
                var value = $(this).val().toLowerCase(); // Ambil input search dan ubah jadi lowercase
                $('#studentTable tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1); // Filter rows berdasarkan query
                });
            });

        // Fungsi untuk mengambil data Prodi dan mengisi dropdown Prodi
        function fetchProdi() {
            $.ajax({
                url: 'get_prodi.php', // Ganti dengan URL file PHP yang sesuai
                method: 'GET',
                success: function (data) {
                    $('#prodi').empty().append('<option value="">Pilih Prodi</option>'); // Kosongkan dropdown terlebih dahulu
                    $('#prodi').append(data); // Isi dropdown Prodi
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat mengambil data Prodi.");
                }
            });
        }

        // Fungsi untuk mengambil data Kelas berdasarkan Prodi yang dipilih
        function fetchKelas(prodiId) {
            if (!prodiId) {
                $('#kelas').empty().append('<option value="">Pilih Kelas</option>'); // Reset dropdown Kelas
                return;
            }

            $.ajax({
                url: 'get_kelas.php', // Ganti dengan URL file PHP yang sesuai
                method: 'GET',
                data: { prodi_id: prodiId }, // Kirim prodi_id sebagai parameter
                success: function (data) {
                    $('#kelas').empty().append('<option value="">Pilih Kelas</option>'); // Kosongkan dropdown Kelas
                    $('#kelas').append(data); // Isi dropdown Kelas
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat mengambil data Kelas.");
                }
            });
        }

        // Panggil fungsi fetchStudents saat halaman dimuat
        fetchStudents();

        // Panggil fungsi fetchProdi saat modal ditampilkan
        $('#addStudentModal').on('show.bs.modal', function () {
            fetchProdi(); // Memuat data Prodi
            $('#kelas').empty().append('<option value="">Pilih Kelas</option>'); // Reset dropdown Kelas
        });

        // Ketika Prodi dipilih, panggil fetchKelas untuk memuat data Kelas
        $('#prodi').on('change', function () {
            const selectedProdiId = $(this).val();
            fetchKelas(selectedProdiId); // Memuat data Kelas berdasarkan Prodi
        });

        // Fungsi untuk menghapus Mahasiswa
        $(document).on('click', '.deleteStudent', function () {
            const studentId = $(this).data('id'); // Mendapatkan ID mahasiswa
            if (confirm('Yakin ingin menghapus data ini?')) { // Konfirmasi penghapusan
                $.ajax({
                    url: 'deleteMahasiswa.php', // Endpoint penghapusan
                    method: 'POST',
                    data: { id: studentId }, // Kirim data ID mahasiswa untuk dihapus
                    success: function (response) {
                        alert(response); // Tampilkan pesan sukses
                        fetchStudents(); // Refresh data mahasiswa setelah penghapusan
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText); // Tampilkan error di console
                        alert("Terjadi kesalahan saat menghapus data mahasiswa."); // Tampilkan pesan error
                    }
                });
            }
        });

        // Fungsi untuk menampilkan data mahasiswa dalam modal edit
        $(document).on('click', '.editStudent', function () {
            const studentId = $(this).data('id'); // Mendapatkan ID mahasiswa

            // Memuat data mahasiswa berdasarkan NIM menggunakan Ajax
            $.ajax({
                url: 'get_student_by_nim.php', // Endpoint untuk mengambil data mahasiswa berdasarkan NIM
                method: 'GET',
                data: { nim: studentId },
                success: function (data) {
                    // Isi data ke dalam form edit
                    const student = JSON.parse(data); // Asumsikan response dalam format JSON
                    $('#edit_nim').val(student.nim);
                    $('#edit_nama').val(student.nama);
                    $('#edit_ttl').val(student.ttl);
                    $('#edit_jenis_kelamin').val(student.jenis_kelamin);
                    $('#edit_alamat').val(student.alamat);
                    $('#edit_email').val(student.email);
                    $('#edit_no_phone').val(student.no_phone);
                    $('#edit_phone_ortu').val(student.phone_ortu);
                    fetchProdiEdit(student.prodi_id); // Memuat Prodi berdasarkan data mahasiswa
                    fetchKelasEdit(student.prodi_id, student.kelas_id); // Memuat Kelas berdasarkan Prodi dan Kelas mahasiswa
                    $('#editStudentModal').modal('show'); // Tampilkan modal edit
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat memuat data mahasiswa.");
                }
            });
        });

        // Fungsi untuk mengambil data Prodi pada modal Edit
        function fetchProdiEdit(selectedProdiId) {
            $.ajax({
                url: 'get_prodi.php', // URL file PHP yang sesuai
                method: 'GET',
                success: function (data) {
                    $('#edit_prodi').empty().append('<option value="">Pilih Prodi</option>');
                    $('#edit_prodi').append(data);
                    $('#edit_prodi').val(selectedProdiId); // Pilih Prodi yang sudah ada
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat mengambil data Prodi.");
                }
            });
        }

        // Fungsi untuk mengambil data Kelas pada modal Edit
        function fetchKelasEdit(prodiId, selectedKelasId) {
            $.ajax({
                url: 'get_kelas.php', // URL file PHP yang sesuai
                method: 'GET',
                data: { prodi_id: prodiId },
                success: function (data) {
                    $('#edit_kelas').empty().append('<option value="">Pilih Kelas</option>');
                    $('#edit_kelas').append(data);
                    $('#edit_kelas').val(selectedKelasId); // Pilih Kelas yang sudah ada
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat mengambil data Kelas.");
                }
            });
        }

        // Fungsi untuk memperbarui data mahasiswa
        $('#editStudentForm').on('submit', function (e) {
            e.preventDefault(); // Mencegah form untuk reload halaman

            $.ajax({
                url: 'updateMahasiswa.php', // Endpoint PHP untuk memperbarui data mahasiswa
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert(response); // Tampilkan pesan sukses
                    $('#editStudentModal').modal('hide'); // Tutup modal
                    fetchStudents(); // Segarkan data mahasiswa setelah perubahan
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat menyimpan perubahan.");
                }
            });
        });

        // Tambah Mahasiswa
        $('#addStudentForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: 'addMahasiswa.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert(response);
                    $('#addStudentModal').modal('hide');
                    $('#addStudentForm')[0].reset();
                    fetchStudents();
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat menyimpan data.");
                }
            });
        });
    });
</script>



</body>
</html>
