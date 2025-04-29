<?php
include '../config/session.php';
include '../config/koneksi.php';

if ($_SESSION['role'] != 'owner') {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f6fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: #fd7e14; /* Oranye */
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

        .nav-link:hover {
            background-color: rgba(255,255,255,0.2);
            color: #fff !important;
        }

        .btn-outline-light:hover {
            background-color: #fff;
            color: #fd7e14 !important;
        }

        .content-container {
            margin-top: 120px;
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            text-align: center;
        }

        h3 {
            font-weight: bold;
        }

        p {
            color: #666;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #fff;
        }

        .card p.fs-4 {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .card-body i {
            font-size: 3rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Owner Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto ms-3">
                <li class="nav-item">
                    <a class="nav-link" href="laporan.php">📊 Laporan</a>
                </li>
            </ul>
            <span class="text-white me-3">👤 <?= $_SESSION['username']; ?></span>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="content-container">
    <div class="section-title">
        <h2>📊 Laporan Pemasukan</h2>
        <a href="cetak_laporan.php" target="_blank" class="btn btn-outline-dark">🖨️ Cetak</a>
    </div>
<br>
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card text-bg-success">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-cash-stack"></i></div>
                    <h5 class="card-title">Pemasukan Hari Ini</h5>
                    <p class="fs-4">Rp <?= number_format($hari_ini, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-calendar3"></i></div>
                    <h5 class="card-title">Pemasukan Bulan Ini</h5>
                    <p class="fs-4">Rp <?= number_format($bulan_ini, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-bg-info">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-bar-chart-line"></i></div>
                    <h5 class="card-title">Pemasukan Tahun Ini</h5>
                    <p class="fs-4">Rp <?= number_format($tahun_ini, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-3">🔥 Menu Terlaris</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
