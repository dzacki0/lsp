<?php
include '../config/session.php';
include '../config/koneksi.php';

if ($_SESSION['role'] != 'waiter' && $_SESSION['role'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}

// Menambah Order
if (isset($_POST['order'])) {
    $id_pelanggan = mysqli_real_escape_string($koneksi, $_POST['id_pelanggan']);
    $id_menu = mysqli_real_escape_string($koneksi, $_POST['id_menu']);
    $id_meja = mysqli_real_escape_string($koneksi, $_POST['id_meja']);
    $jumlah = mysqli_real_escape_string($koneksi, $_POST['jumlah']);

    $query = mysqli_query($koneksi, "INSERT INTO orders (id_pelanggan, id_menu, id_meja, jumlah) 
    VALUES ('$id_pelanggan', '$id_menu', '$id_meja', '$jumlah')");

    if ($query) {
        success('Pesanan berhasil ditambahkan', 'order.php');
    } else {
        error('Gagal menambahkan pesanan', 'order.php');
    }
}

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



?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemesanan</title>
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
        .btn-outline-light:hover {
            background-color: #fff;
            color: #6c757d !important;
        }
        .container {
            margin-top: 40px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">🍽️ Waiter Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                
                <li class="nav-item">
                    <a class="nav-link active" href="order.php">📝 Pemesanan</a>
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

<div class="container">
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Pelanggan</button>
    <h3 class="mb-4">Tambah Pesanan</h3>

    <form method="post" class="mb-5">
        <div class="mb-3">
            <label for="id_pelanggan" class="form-label">Pelanggan</label>
            <select name="id_pelanggan" id="id_pelanggan" class="form-control" required>
                <option value="">-- Pilih Pelanggan --</option>
                <?php
                $pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan");
                while ($row = mysqli_fetch_assoc($pelanggan)) {
                    echo "<option value='{$row['id']}'>{$row['nama_pelanggan']} - {$row['kontak']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_menu" class="form-label">Menu</label>
            <select name="id_menu" id="id_menu" class="form-control" required>
                <option value="">-- Pilih Menu --</option>
                <?php
                $menu = mysqli_query($koneksi, "SELECT * FROM menu");
                while ($row = mysqli_fetch_assoc($menu)) {
                    echo "<option value='{$row['id']}'>{$row['nama_menu']} - Rp " . number_format($row['harga']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_meja" class="form-label">Meja</label>
            <select name="id_meja" id="id_meja" class="form-control" required>
                <option value="">-- Pilih Meja --</option>
                <?php
                $meja = mysqli_query($koneksi, "SELECT * FROM meja WHERE nomor_meja IS NOT NULL");
                while ($row = mysqli_fetch_assoc($meja)) {
                    echo "<option value='{$row['id']}'>Meja Nomor {$row['nomor_meja']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" required>
        </div>

        <button type="submit" name="order" class="btn btn-primary">Tambah Pesanan</button>
    </form>

    <h3 class="mb-3">Daftar Pesanan</h3>
    <table id="orderTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Meja</th>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $orders = mysqli_query($koneksi, "
                SELECT orders.id, pelanggan.nama_pelanggan, menu.nama_menu, menu.harga, meja.nomor_meja, orders.jumlah
                FROM orders
                JOIN pelanggan ON orders.id_pelanggan = pelanggan.id
                JOIN menu ON orders.id_menu = menu.id
                LEFT JOIN meja ON orders.id_meja = meja.id
            ");
            while ($row = mysqli_fetch_assoc($orders)) {
                $total = $row['harga'] * $row['jumlah'];
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['nama_pelanggan']}</td>
                        <td>Meja {$row['nomor_meja']}</td>
                        <td>{$row['nama_menu']}</td>
                        <td>{$row['jumlah']}</td>
                        <td>Rp " . number_format($total, 0, ',', '.') . "</td>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#orderTable').DataTable();
    });
</script>

</body>
</html>
