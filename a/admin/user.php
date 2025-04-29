<?php
include '../config/session.php';
include '../config/koneksi.php';

// Hanya admin yang boleh akses
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

// Tambah user baru
if (isset($_POST['tambah'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $query = mysqli_query($koneksi, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");

    if ($query) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'User berhasil ditambahkan',
            timer: 1500,
            showConfirmButton: false
        }).then(function(){
            window.location.href = 'user.php';
        });
        </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'User gagal ditambahkan',
            timer: 1500,
            showConfirmButton: false
        }).then(function(){
            window.location.href = 'user.php';
        });
        </script>";
    }
}

// Edit user
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $role = $_POST['role'];

    // Only update password if a new one is provided
    $password = empty($_POST['password']) ? '' : password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $query = "UPDATE users SET username='$username', role='$role'";

    if ($password) {
        $query .= ", password='$password'";
    }

    $query .= " WHERE id='$id'";

    $update = mysqli_query($koneksi, $query);

    if ($update) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'User berhasil diperbarui',
            timer: 1500,
            showConfirmButton: false
        }).then(function(){
            window.location.href = 'user.php';
        });
        </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'User gagal diperbarui',
            timer: 1500,
            showConfirmButton: false
        }).then(function(){
            window.location.href = 'user.php';
        });
        </script>";
    }
}

// Hapus user
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $hapus = mysqli_query($koneksi, "DELETE FROM users WHERE id='$id'");

    if ($hapus) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'User berhasil dihapus',
            timer: 1500,
            showConfirmButton: false
        }).then(function(){
            window.location.href = 'user.php';
        });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User</title>
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

<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">🍽️ Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="user.php">👥 Kelola User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="meja.php">🪑 Kelola Meja</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="menu.php">🍽️ Kelola Menu</a>
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
        <h3>Kelola User</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">➕ Tambah User</button>
    </div>
    <table id="userTable" class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $user = mysqli_query($koneksi, "SELECT * FROM users");
            while ($row = mysqli_fetch_assoc($user)) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['role']}</td>
                        <td>
                            <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal{$row['id']}'>Edit</button>
                            <a href='?hapus={$row['id']}' onclick=\"return confirm('Yakin hapus?')\" class='btn btn-danger btn-sm'>Hapus</a>
                        </td>
                      </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="tambahModalLabel">Tambah User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="waiter">Waiter</option>
                        <option value="kasir">Kasir</option>
                        <option value="owner">Owner</option>
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

<!-- Modal Edit User -->
<?php
$userEditQuery = mysqli_query($koneksi, "SELECT * FROM users");
while ($row = mysqli_fetch_assoc($userEditQuery)) {
?>
<div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" value="<?= $row['username'] ?>" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Password (Kosongkan jika tidak ingin mengganti)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="waiter" <?= $row['role'] == 'waiter' ? 'selected' : '' ?>>Waiter</option>
                        <option value="kasir" <?= $row['role'] == 'kasir' ? 'selected' : '' ?>>Kasir</option>
                        <option value="owner" <?= $row['role'] == 'owner' ? 'selected' : '' ?>>Owner</option>
                    </select>
                </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="edit" class="btn btn-warning">Update</button>
          </div>
        </div>
    </form>
  </div>
</div>
<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#userTable').DataTable();
});
</script>

</body>
</html>
