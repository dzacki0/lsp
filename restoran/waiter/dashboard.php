<?php
include '../config/session.php';
include '../config/koneksi.php';

if ($_SESSION['role'] != 'waiter') {
    header("Location: ../index.php");
    exit;
}

$orders = mysqli_query($koneksi, "
    SELECT orders.id, pelanggan.nama_pelanggan, menu.nama_menu, menu.harga, meja.nomor_meja, orders.jumlah, orders.id_pelanggan, orders.id_menu
    FROM orders 
    JOIN pelanggan ON orders.id_pelanggan = pelanggan.id
    JOIN menu ON orders.id_menu = menu.id
    LEFT JOIN meja ON orders.id_meja = meja.id
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f6fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: #6c757d;
        }
        .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            margin-right: 10px;
            padding: 8px 16px;
            border-radius: 8px;
        }
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.2);
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
                    <a class="nav-link" href="order.php">📝 Pemesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="daftar_pesanan.php">🍽️ Daftar Pesanan</a>
                </li>
            </ul>
            <span class="text-white me-3">👤 <?= $_SESSION['username']; ?></span>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<!-- Konten -->
<div class="container mt-5">
    <h2 class="mb-4">Daftar Pesanan</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">Kembali</a>

    <table id="orderTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Meja</th>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Total Bayar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($orders)) {
                $nomor_meja = $row['nomor_meja'] ? "Meja " . $row['nomor_meja'] : "-";
                $total_bayar = $row['harga'] * $row['jumlah'];
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['nama_pelanggan']}</td>
                        <td>{$nomor_meja}</td>
                        <td>{$row['nama_menu']}</td>
                        <td>{$row['jumlah']}</td>
                        <td>Rp " . number_format($total_bayar, 0, ',', '.') . "</td>
                        <td>
                            <button class='btn btn-success btn-sm kirimKasir' 
                                data-idorder='{$row['id']}' 
                                data-idpelanggan='{$row['id_pelanggan']}' 
                                data-idmenu='{$row['id_menu']}' 
                                data-jumlah='{$row['jumlah']}' 
                                data-total='{$total_bayar}'>
                                Kirim ke Kasir
                            </button>
                        </td>
                      </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#orderTable').DataTable();

    $('.kirimKasir').click(function() {
        var id_pelanggan = $(this).data('idpelanggan');
        var id_menu = $(this).data('idmenu');
        var jumlah = $(this).data('jumlah');
        var total_bayar = $(this).data('total');

        $.ajax({
            url: 'kirim_ke_transaksi.php',
            method: 'POST',
            data: { id_pelanggan: id_pelanggan, id_menu: id_menu, jumlah: jumlah, total_bayar: total_bayar },
            success: function(response) {
                if (response == 'success') {
                    alert('Pesanan berhasil dikirim ke kasir');
                    location.reload();
                } else {
                    alert('Gagal mengirim pesanan ke kasir');
                }
            }
        });
    });
});
</script>

</body>
</html>
