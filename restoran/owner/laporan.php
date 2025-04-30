<?php
include '../config/koneksi.php';
session_start();

// Ambil semua data pembayaran selesai
$query = mysqli_query($koneksi, "
    SELECT ps.waktu, pelanggan.nama_pelanggan, ps.id AS id_pembayaran
    FROM pembayaran_selesai ps
    JOIN pelanggan ON ps.id_pelanggan = pelanggan.id
    ORDER BY ps.waktu DESC
");

// Hitung total pemasukan hari ini
$tanggal_hari_ini = date('Y-m-d');
$total_hari_ini_query = mysqli_query($koneksi, "
    SELECT SUM(menu.harga * orders.jumlah) AS total_hari_ini
    FROM pembayaran_selesai ps
    JOIN orders ON orders.id_pelanggan = ps.id_pelanggan
    JOIN menu ON menu.id = orders.id_menu
    WHERE DATE(ps.waktu) = '$tanggal_hari_ini'
");

$total_hari_ini = 0;
if ($row_total = mysqli_fetch_assoc($total_hari_ini_query)) {
    $total_hari_ini = $row_total['total_hari_ini'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembayaran</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            padding-top: 100px;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
        }

        .table-container {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td {
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
            color: #d9534f;
        }

        /* Kustom CSS untuk Navbar */
        .navbar-custom {
            background-color: #FFA500; /* Warna Oranye */
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold me-4" href="dashboard.php">🍽️ Owner</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Navigasi kiri -->
            <ul class="navbar-nav me-auto">
                
                <li class="nav-item">
                    <a class="nav-link active" href="laporan.php">📊 Laporan</a>
                </li>
            </ul>

            <!-- Navigasi kanan -->
            <div class="d-flex align-items-center">
                <span class="text-white me-3">👤 <?= $_SESSION['username']; ?></span>
                <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </div>
</nav>

<!-- Konten -->
<div class="container">
    <h2>📄 Laporan Pembayaran - Warung Makan Kita</h2>

    <!-- Total Pemasukan Hari Ini -->
    <div class="mt-4">
        <h5 class="text-success">💰 Total Pemasukan Hari Ini (<?= date('d-m-Y') ?>): 
            <span class="total">Rp <?= number_format($total_hari_ini, 0, ',', '.') ?></span>
        </h5>
    </div>

    <!-- Tabel Laporan -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Pelanggan</th>
                    <th>Menu</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($query)) {
                    $id_pembayaran = $row['id_pembayaran'];
                    $menu_q = mysqli_query($koneksi, "
                        SELECT menu.nama_menu, menu.harga, orders.jumlah
                        FROM orders
                        JOIN menu ON orders.id_menu = menu.id
                        WHERE orders.id_pelanggan = (
                            SELECT id_pelanggan FROM pembayaran_selesai WHERE id = '$id_pembayaran'
                        )
                    ");

                    $menu_list = [];
                    $total = 0;

                    while ($m = mysqli_fetch_assoc($menu_q)) {
                        $menu_list[] = $m['nama_menu'] . " x" . $m['jumlah'];
                        $total += $m['harga'] * $m['jumlah'];
                    }

                    echo "<tr>
                            <td>" . date('d-m-Y H:i', strtotime($row['waktu'])) . "</td>
                            <td>{$row['nama_pelanggan']}</td>
                            <td>" . implode('<br>', $menu_list) . "</td>
                            <td class='total'>Rp " . number_format($total, 0, ',', '.') . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
