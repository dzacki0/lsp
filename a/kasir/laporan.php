<?php
include '../config/session.php';
include '../config/koneksi.php';

if ($_SESSION['role'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}

$today = date('Y-m-d');
$bulan = date('Y-m');
$tahun = date('Y');

$hari_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT SUM(total_bayar) as total FROM pembayaran_selesai 
    WHERE DATE(waktu) = '$today'
"))['total'] ?? 0;

$bulan_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT SUM(total_bayar) as total FROM pembayaran_selesai 
    WHERE DATE_FORMAT(waktu, '%Y-%m') = '$bulan'
"))['total'] ?? 0;

$tahun_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT SUM(total_bayar) as total FROM pembayaran_selesai 
    WHERE YEAR(waktu) = '$tahun'
"))['total'] ?? 0;

$menu_terlaris = mysqli_query($koneksi, "
    SELECT m.nama_menu, SUM(t.jumlah) AS total_jumlah
    FROM transaksi t
    JOIN menu m ON t.id_menu = m.id
    GROUP BY t.id_menu
    ORDER BY total_jumlah DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemasukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: #28a745;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            margin-right: 10px;
            padding: 8px 16px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff !important;
        }

        .btn-outline-light:hover {
            background-color: #fff;
            color: #28a745 !important;
        }

        h2, h3, h4 {
            font-weight: bold;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .container {
            padding-top: 100px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="dashboard.php">🍽️ Kasir</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto ms-3">
                <li class="nav-item">
                    <a class="nav-link " href="pembayaran.php">💳 Pembayaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="laporan.php">📊 Laporan</a>
                </li>
            </ul>
            <span class="text-white me-3">👤 <?= $_SESSION['username']; ?></span>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>
<br>
<br>
<div class="container py-4">
    <h2 class="mb-4">📊 Laporan Pemasukan <div>
        <a href="cetak_laporan.php" target="_blank" class="btn btn-outline-dark me-2">🖨️ Cetak</a>
       
    </div></h2>

    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card text-bg-success shadow">
                <div class="card-body">
                    <h5 class="card-title">Pemasukan Hari Ini</h5>
                    <p class="fs-4">Rp <?= number_format($hari_ini, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-warning shadow">
                <div class="card-body">
                    <h5 class="card-title">Pemasukan Bulan Ini</h5>
                    <p class="fs-4">Rp <?= number_format($bulan_ini, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-info shadow">
                <div class="card-body">
                    <h5 class="card-title">Pemasukan Tahun Ini</h5>
                    <p class="fs-4">Rp <?= number_format($tahun_ini, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-3">🔥 Menu Terlaris</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Nama Menu</th>
                    <th>Total Terjual</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($menu = mysqli_fetch_assoc($menu_terlaris)) {
                    echo "<tr>
                            <td>{$no}</td>
                            <td>{$menu['nama_menu']}</td>
                            <td>{$menu['total_jumlah']}</td>
                          </tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
    
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
