<?php
include '../config/session.php';
include '../config/koneksi.php';

if ($_SESSION['role'] != 'waiter') {
    header("Location: ../index.php");
    exit;
}

// Tambah Pelanggan
if (isset($_POST['tambah'])) {
    $nama_pelanggan = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
    $kontak = mysqli_real_escape_string($koneksi, $_POST['kontak']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);

    $query = mysqli_query($koneksi, "INSERT INTO pelanggan (nama_pelanggan, kontak, alamat, jenis_kelamin) 
                                      VALUES ('$nama_pelanggan', '$kontak', '$alamat', '$jenis_kelamin')");

    if ($query) {
        success('Pelanggan berhasil ditambahkan', 'pelanggan.php');
    } else {
        error('Gagal menambahkan pelanggan', 'pelanggan.php');
    }
}

// SweetAlert2 Notifikasi
function success($message, $redirect) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '$message', timer: 1500, showConfirmButton: false }).then(function(){ window.location.href = '$redirect'; });
    </script>";
}
function error($message, $redirect) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({ icon: 'error', title: 'Gagal!', text: '$message', timer: 1500, showConfirmButton: false }).then(function(){ window.location.href = '$redirect'; });
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f6fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: #6c757d;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            margin-right: 10px;
            padding: 8px 16px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: #fff !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">🍽️ Waiter Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="pelanggan.php">📋 Pelanggan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="order.php">📝 Pemesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="daftar_pesanan.php">🍽️ Daftar Pesanan</a>
                </li>
            </ul>
            <span class="text-white me-3">👤 <?= $_SESSION['username']; ?></span>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h3>Kelola Pelanggan</h3>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Pelanggan</button>
    <table id="pelangganTable" class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>No. HP</th>
                <th>Alamat</th>
                <th>Jenis Kelamin</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan");
            while ($row = mysqli_fetch_assoc($pelanggan)) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['nama_pelanggan']}</td>
                        <td>{$row['kontak']}</td>
                        <td>{$row['alamat']}</td>
                        <td>{$row['jenis_kelamin']}</td>
                      </tr>";
                      
                $no++;
            }
            
            ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Pelanggan -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="tambahModalLabel">Tambah Pelanggan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
                <div class="mb-3">
                    <label>Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>No. HP</label>
                    <input type="text" name="kontak" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Alamat</label>
                    <input type="text" name="alamat" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="form-control">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-Laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
          </div>
        </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#pelangganTable').DataTable();
});
</script>

</body>
</html>
