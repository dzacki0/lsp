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
    <title>Cetak Laporan Pemasukan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container my-4">
    <div class="text-center mb-4">
        <h2>Laporan Pemasukan</h2>
        <small><?= date('d-m-Y H:i:s') ?></small>
    </div>

    <h4>Pemasukan</h4>
    <table class="table table-bordered mb-4">
        <tbody>
            <tr>
                <th>Hari Ini</th>
                <td>Rp <?= number_format($hari_ini, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Bulan Ini</th>
                <td>Rp <?= number_format($bulan_ini, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Tahun Ini</th>
                <td>Rp <?= number_format($tahun_ini, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <h4>Menu Terlaris</h4>
    <table class="table table-bordered">
        <thead>
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

    <div class="text-end mt-5">
        <p>Dicetak oleh: <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)</p>
    </div>

    <div class="no-print text-center mt-4">
        <a href="laporan.php" class="btn btn-secondary">⬅️ Kembali ke Halaman Laporan</a>
    </div>
</div>

</body>
</html>
