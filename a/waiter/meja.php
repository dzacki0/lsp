<?php
session_start();
include '../config/koneksi.php';

// Cek apakah pengguna adalah waiter
if ($_SESSION['role'] != 'waiter') {
    header("Location: ../index.php");
    exit;
}

// Ambil data meja
$query = mysqli_query($koneksi, "SELECT * FROM meja");
$meja = mysqli_fetch_all($query, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Waiter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3>Dashboard Waiter</h3>
    <p>Selamat datang, <?php echo $_SESSION['username']; ?></p>

    <!-- Tabel Meja -->
    <h4>Meja</h4>
    <table id="mejaTable" class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Meja</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($meja as $row) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['nomor_meja']}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <a href='order.php?meja_id={$row['id']}' class='btn btn-primary btn-sm'>Pesan</a>
                        </td>
                      </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal untuk menambahkan order -->
<!-- Saring data menu dan pelanggan saat pemesanan -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#mejaTable').DataTable();
});
</script>

</body>
</html>
