<?php
include '../config/session.php';
include '../config/koneksi.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

// Tambah Menu
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);

    $query = mysqli_query($koneksi, "INSERT INTO menu (nama_menu, harga, kategori) VALUES ('$nama', '$harga', '$kategori')");

    if ($query) {
        success('Menu berhasil ditambahkan', 'menu.php');
    } else {
        error('Gagal menambahkan menu', 'menu.php');
    }
}

// Hapus Menu
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $hapus = mysqli_query($koneksi, "DELETE FROM menu WHERE id='$id'");

    if ($hapus) {
        success('Menu berhasil dihapus', 'menu.php');
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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f6fc;
        }
        .navbar {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .nav-link {
            color: white !important;
            font-weight: 500;
            margin-right: 10px;
            padding: 8px 16px;
            border-radius: 8px;
            transition: 0.3s;
        }
        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(255,255,255,0.2);
        }
        .table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }
        .modal-content {
            border-radius: 12px;
        }
        .btn-primary {
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-light">
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">🍽️ Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="user.php">👥 Kelola User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="meja.php">🪑 Kelola Meja</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="menu.php">🍽️ Kelola Menu</a>
                </li>
            </ul>
            <span class="text-white me-3">👤 <?= $_SESSION['username']; ?></span>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>
<br>
<br>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Menu</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">➕ Tambah Menu</button>
    </div>
    <table id="menuTable" class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Nama Menu</th>
                <th>Harga</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $menu = mysqli_query($koneksi, "SELECT * FROM menu");
            while ($row = mysqli_fetch_assoc($menu)) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['nama_menu']}</td>
                        <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
                        <td>{$row['kategori']}</td>
                        <td>
                            <a href='?hapus={$row['id']}' onclick=\"return confirm('Yakin hapus menu ini?')\" class='btn btn-sm btn-danger'>Hapus</a>
                        </td>
                      </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Menu -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="tambahModalLabel">Tambah Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
                <div class="mb-3">
                    <label>Nama Menu</label>
                    <input type="text" name="nama" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Harga</label>
                    <input type="number" name="harga" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                    </select>
                </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="tambah" class="btn btn-success">Simpan</button>
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
    $('#menuTable').DataTable();
});
</script>

</body>
</html>
