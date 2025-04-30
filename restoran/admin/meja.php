<?php
include '../config/session.php';
include '../config/koneksi.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

// Tambah meja
if (isset($_POST['tambah'])) {
    $nomor_meja = mysqli_real_escape_string($koneksi, $_POST['nomor_meja']);
    $cek = mysqli_query($koneksi, "SELECT * FROM meja WHERE nomor_meja='$nomor_meja'");
    if (mysqli_num_rows($cek) > 0) {
        error('Nomor meja sudah ada, pilih nomor meja lain!', 'meja.php');
    } else {
        $query = mysqli_query($koneksi, "INSERT INTO meja (nomor_meja) VALUES ('$nomor_meja')");
        $query ? success('Meja berhasil ditambahkan', 'meja.php') : error('Gagal menambahkan meja', 'meja.php');
    }
}

// Edit meja
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nomor_meja = mysqli_real_escape_string($koneksi, $_POST['nomor_meja']);
    
    $cek = mysqli_query($koneksi, "SELECT * FROM meja WHERE nomor_meja='$nomor_meja' AND id != '$id'");
    if (mysqli_num_rows($cek) > 0) {
        error('Nomor meja sudah ada, pilih nomor meja lain!', 'meja.php');
    } else {
        $query = mysqli_query($koneksi, "UPDATE meja SET nomor_meja='$nomor_meja' WHERE id='$id'");
        $query ? success('Meja berhasil diperbarui', 'meja.php') : error('Gagal memperbarui meja', 'meja.php');
    }
}

// Hapus meja
if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Pertama, periksa apakah ada pesanan yang terkait dengan meja ini
    $cekOrders = mysqli_query($koneksi, "SELECT * FROM orders WHERE id_meja='$id'");

    // Jika ada pesanan, kita harus menghapus pesanan tersebut terlebih dahulu
    if (mysqli_num_rows($cekOrders) > 0) {
        // Hapus pesanan terkait meja
        $deleteOrders = mysqli_query($koneksi, "DELETE FROM orders WHERE id_meja='$id'");
        
        if (!$deleteOrders) {
            // Jika gagal menghapus pesanan, tampilkan pesan error
            error('Gagal menghapus pesanan terkait meja!', 'meja.php');
            exit;
        }
    }

    // Sekarang, lanjutkan untuk menghapus data meja
    $cekMeja = mysqli_query($koneksi, "SELECT * FROM meja WHERE id='$id'");
    if (mysqli_num_rows($cekMeja) > 0) {
        $hapus = mysqli_query($koneksi, "DELETE FROM meja WHERE id='$id'");
        $hapus ? success('Meja berhasil dihapus', 'meja.php') : error('Gagal menghapus meja', 'meja.php');
    } else {
        error('Meja tidak ditemukan', 'meja.php');
    }
}

function success($message, $redirect) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '$message', timer: 1500, showConfirmButton: false })
    .then(() => window.location.href = '$redirect');
    </script>";
}

function error($message, $redirect) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({ icon: 'error', title: 'Gagal!', text: '$message', timer: 1500, showConfirmButton: false })
    .then(() => window.location.href = '$redirect');
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Meja</title>
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
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="dashboard.php">🍽️ Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="meja.php">🪑 Kelola Meja</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="menu.php">🍽️ Kelola Menu</a>
                </li>
            </ul>
            <span class="text-white me-3">👤 <?= $_SESSION['username']; ?></span>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<!-- Konten -->
<div class="container mt-5 pt-5">
    <h3 class="mb-4">🪑 Kelola Meja</h3>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">+ Tambah Meja</button>
    <div class="table-responsive">
        <table id="mejaTable" class="table table-striped table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Nomor Meja</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $meja = mysqli_query($koneksi, "SELECT * FROM meja");
                while ($row = mysqli_fetch_assoc($meja)) {
                    echo "<tr>
                            <td>{$no}</td>
                            <td>{$row['nomor_meja']}</td>
                            <td>
                                <a href='#' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' data-id='{$row['id']}' data-nomor_meja='{$row['nomor_meja']}'>Edit</a>
                                <a href='?hapus={$row['id']}' onclick=\"return confirm('Yakin hapus?')\" class='btn btn-danger btn-sm'>Hapus</a>
                            </td>
                          </tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Meja -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="tambahModalLabel">Tambah Meja</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nomor Meja</label>
                    <input type="text" name="nomor_meja" required class="form-control">
                </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
          </div>
        </div>
    </form>
  </div>
</div>

<!-- Modal Edit Meja -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit Meja</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
                <input type="hidden" name="id" id="editId">
                <div class="mb-3">
                    <label class="form-label">Nomor Meja</label>
                    <input type="text" name="nomor_meja" id="editNomorMeja" required class="form-control">
                </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="edit" class="btn btn-primary">Update</button>
          </div>
        </div>
    </form>
  </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#mejaTable').DataTable();

    // Set data to edit modal
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nomor_meja = button.data('nomor_meja');
        
        var modal = $(this);
        modal.find('#editId').val(id);
        modal.find('#editNomorMeja').val(nomor_meja);
    });
});
</script>

</body>
</html>
