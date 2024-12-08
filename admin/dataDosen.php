<?php
include 'getAdminName.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Data Dosen - Dashboard Admin</title>
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
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .main-header .navbar {
            background-color: #115599 !important; /* Mengganti warna navbar */
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
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
                <li><a href="dataMahasiswa.php"><i class="fa fa-users"></i> <span>Data Mahasiswa</span></a></li>
                <li class="active"><a href="dataDosen.php"><i class="fa fa-users"></i> <span>Data Dosen</span></a></li>
                <li><a href="dataDPA.php"><i class="fa fa-file-text-o"></i> <span>Data DPA</span></a></li>
                <li><a href="dataLaporan.php"><i class="fa fa-file-text-o"></i> <span>Data Laporan Pelanggaran</span></a></li>
                <li><a href="../logout.php"><i class="fa fa-sign-out"></i><span>Log Out</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Data Dosen</h1>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Daftar Dosen</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-10">
                            <input type="text" id="searchDosen" class="form-control" placeholder="Search">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addDosenModal">Tambah Dosen</button>
                        </div>
                    </div>
                    <!-- Tabel Data Dosen -->
                    <table id="dosenTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>TTL</th>
                                <th>Jenis Kelamin</th>
                                <th>Jabatan</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data Dosen Akan Ditampilkan Di Sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b><a href="">Jurusan Teknologi Informasi</a></b>
        </div>
        <strong><a href="">Politeknik Negeri Malang</a></strong>
    </footer>

</div>
<!-- ./wrapper -->

<!-- Modal Tambah Dosen -->
<div id="addDosenModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tambah Dosen</h4>
            </div>
            <div class="modal-body">
                <form id="addDosenForm">
                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="ttl">Tempat, Tanggal Lahir</label>
                        <input type="text" class="form-control" id="ttl" name="ttl" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" required>
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
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit Dosen -->
<div id="editDosenModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Dosen</h4>
            </div>
            <div class="modal-body">
                <form id="editDosenForm">
                    <input type="hidden" id="edit_nip" name="nip">
                    <div class="form-group">
                        <label for="edit_nama">Nama</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_ttl">Tempat, Tanggal Lahir</label>
                        <input type="text" class="form-control" id="edit_ttl" name="ttl" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control" id="edit_jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_jabatan">Jabatan</label>
                        <input type="text" class="form-control" id="edit_jabatan" name="jabatan" required>
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
                        <label for="edit_alamat">Alamat</label>
                        <textarea class="form-control" id="edit_alamat" name="alamat" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
    $(document).ready(function () {
        // Fungsi untuk mengambil data dosen dan mengisi tabel
        function fetchDosen() {
            $.ajax({
                url: 'get_dosen.php', // Ganti dengan URL file PHP yang sesuai
                method: 'GET',
                success: function (data) {
                    // Menampilkan data dosen ke dalam tabel
                    $('#dosenTable tbody').html(data);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat mengambil data dosen.");
                }
            });
        }

        // Search functionality
        $('#searchDosen').on('keyup', function () {
                var value = $(this).val().toLowerCase(); // Ambil input search dan ubah jadi lowercase
                $('#dosenTable tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1); // Filter rows berdasarkan query
                });
            });
    
        // Panggil fungsi fetchDosen saat halaman dimuat
        fetchDosen();
    
        // Fungsi untuk menghapus Dosen
        $(document).on('click', '.deleteDosen', function () {
            const nip = $(this).data('nip'); // Mendapatkan NIP dosen
            if (confirm('Yakin ingin menghapus data dosen dengan NIP ' + nip + '?')) { // Konfirmasi penghapusan
                $.ajax({
                    url: 'delete_dosen.php', // Endpoint untuk menghapus dosen
                    method: 'POST',
                    data: { nip: nip }, // Kirim data NIP untuk dihapus
                    success: function (response) {
                        alert(response); // Tampilkan pesan sukses
                        fetchDosen(); // Refresh data dosen setelah penghapusan
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText); // Tampilkan error di console
                        alert("Terjadi kesalahan saat menghapus data dosen."); // Tampilkan pesan error
                    }
                });
            }
        });
    
        // Fungsi untuk menambahkan Dosen
        $('#addDosenForm').on('submit', function (e) {
            e.preventDefault(); // Mencegah form untuk reload halaman
    
            $.ajax({
                url: 'add_dosen.php', // Endpoint PHP untuk menambahkan data dosen
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert(response); // Tampilkan pesan sukses
                    $('#addDosenModal').modal('hide'); // Tutup modal
                    $('#addDosenForm')[0].reset(); // Reset form
                    fetchDosen(); // Segarkan data dosen setelah penambahan
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat menyimpan data dosen.");
                }
            });
        });
            // Fungsi untuk membuka modal editDosen dengan data yang sudah ada
            $(document).on('click', '.editDosen', function () {
            const nip = $(this).data('nip'); // Ambil NIP dari tombol edit
            $.ajax({
                url: 'getdosennip.php', // Endpoint untuk mengambil data dosen berdasarkan NIP
                method: 'GET',
                data: { nip: nip },
                success: function (data) {
                    const dosen = JSON.parse(data); // Parse data menjadi JSON
                    $('#edit_nip').val(dosen.nip);
                    $('#edit_nama').val(dosen.nama);
                    $('#edit_ttl').val(dosen.TTL);
                    $('#edit_jenis_kelamin').val(dosen.jenis_kelamin);
                    $('#edit_jabatan').val(dosen.jabatan);
                    $('#edit_email').val(dosen.email);
                    $('#edit_no_phone').val(dosen.no_phone);
                    $('#edit_alamat').val(dosen.alamat);
                    $('#editDosenModal').modal('show'); // Tampilkan modal
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat mengambil data dosen.");
                }
            });
        });

        // Fungsi untuk menyimpan perubahan data dosen
        $('#editDosenForm').on('submit', function (e) {
            e.preventDefault(); // Mencegah reload halaman

            $.ajax({
                url: 'updateDosen.php', // Endpoint PHP untuk memperbarui data dosen
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert(response); // Tampilkan pesan sukses
                    $('#editDosenModal').modal('hide'); // Tutup modal
                    fetchDosen(); // Segarkan data dosen setelah update
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat menyimpan perubahan data dosen.");
                }
            });
        });
    });
    </script>
    
</body>
</html>
