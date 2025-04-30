<?php
include '../config/session.php';
include '../config/koneksi.php';

if ($_SESSION['role'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}

// Proses pembayaran
if (isset($_POST['proses_pembayaran'])) {
    $id_pelanggan = $_POST['id_pelanggan'];
    $uang_diterima = $_POST['uang_diterima'];
    $total_bayar = $_POST['total_bayar'];
    $kembalian = $uang_diterima - $total_bayar;

    if ($kembalian >= 0) {
        $simpan = mysqli_query($koneksi, "
            INSERT INTO pembayaran_selesai (id_pelanggan, total_bayar, uang_diterima, kembalian)
            VALUES ('$id_pelanggan', '$total_bayar', '$uang_diterima', '$kembalian')
        ");

        if ($simpan) {
            $sukses_bayar = true;
        } else {
            $error = "Gagal menyimpan pembayaran.";
        }
    } else {
        $error = "Uang yang diterima kurang.";
    }
}

// Ambil data pelanggan dari tabel orders
$pelanggan = mysqli_query($koneksi, "
    SELECT DISTINCT pelanggan.id, pelanggan.nama_pelanggan
    FROM orders
    JOIN pelanggan ON orders.id_pelanggan = pelanggan.id
");

$id_pelanggan_pilih = $_POST['id_pelanggan'] ?? null;
$pesanan = null;
$total_pesanan = 0;
if ($id_pelanggan_pilih) {
    $pesanan = mysqli_query($koneksi, "
        SELECT orders.*, menu.nama_menu, menu.harga 
        FROM orders 
        JOIN menu ON orders.id_menu = menu.id 
        WHERE orders.id_pelanggan = '$id_pelanggan_pilih'
    ");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>
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
        <a class="navbar-brand fw-bold" href="#">🍽️ Kasir</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto ms-3">
                <li class="nav-item">
                    <a class="nav-link active" href="pembayaran.php">💳 Pembayaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="laporan.php">📊 Laporan</a>
                </li>
            </ul>
            <span class="text-white me-3">👤 <?= $_SESSION['username']; ?></span>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">

    <!-- Form pilih pelanggan -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="id_pelanggan" class="col-form-label">Pilih Pelanggan:</label>
                    </div>
                    <div class="col-auto">
                        <select name="id_pelanggan" id="id_pelanggan" class="form-select" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php while ($row = mysqli_fetch_assoc($pelanggan)) { ?>
                                <option value="<?= $row['id'] ?>" <?= ($id_pelanggan_pilih == $row['id']) ? 'selected' : '' ?>>
                                    <?= $row['nama_pelanggan'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Tampilkan Pesanan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Pesanan -->
    <?php if ($pesanan && mysqli_num_rows($pesanan) > 0) { ?>
        <div class="card mb-4">
            <div class="card-body">
                <h4>Daftar Pesanan:</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Menu</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($pesanan)) {
                            $subtotal = $row['harga'] * $row['jumlah'];
                            $total_pesanan += $subtotal;
                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>{$row['nama_menu']}</td>
                                    <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
                                    <td>{$row['jumlah']}</td>
                                    <td>Rp " . number_format($subtotal, 0, ',', '.') . "</td>
                                    <td>{$row['tanggal']}</td>
                                  </tr>";
                            $no++;
                        }
                        ?>
                        <tr>
                            <th colspan="4" class="text-end">Total</th>
                            <th colspan="2">Rp <?= number_format($total_pesanan, 0, ',', '.') ?></th>
                        </tr>
                    </tbody>
                </table>

                <!-- Form Pembayaran -->
                <form method="POST">
                    <input type="hidden" name="id_pelanggan" value="<?= $id_pelanggan_pilih ?>">
                    <input type="hidden" name="total_bayar" value="<?= $total_pesanan ?>">
                    <div class="mb-3">
                        <label for="uang_diterima" class="form-label">Uang Diterima:</label>
                        <input type="number" name="uang_diterima" class="form-control" required>
                    </div>
                    <button type="submit" name="proses_pembayaran" class="btn btn-success">💳 Proses Pembayaran</button>
                </form>
            </div>
        </div>
    <?php } ?>

    <!-- Notifikasi -->
    <?php if (isset($sukses_bayar) && $sukses_bayar): ?>
        <div class="alert alert-success">✅ Pembayaran berhasil!</div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger">❌ <?= $error ?></div>
    <?php endif; ?>

    <!-- Riwayat pembayaran selesai -->
    <h3 class="mt-5">Pesanan Sudah Dibayar</h3>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Total Bayar</th>
                        <th>Uang Diterima</th>
                        <th>Kembalian</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $riwayat = mysqli_query($koneksi, "
                        SELECT ps.*, pelanggan.nama_pelanggan
                        FROM pembayaran_selesai ps
                        JOIN pelanggan ON ps.id_pelanggan = pelanggan.id
                        ORDER BY ps.waktu DESC
                    ");
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($riwayat)) {
                        echo "<tr>
                                <td>{$no}</td>
                                <td>{$row['nama_pelanggan']}</td>
                                <td>Rp " . number_format($row['total_bayar'], 0, ',', '.') . "</td>
                                <td>Rp " . number_format($row['uang_diterima'], 0, ',', '.') . "</td>
                                <td>Rp " . number_format($row['kembalian'], 0, ',', '.') . "</td>
                                <td>{$row['waktu']}</td>
                                <td>
                                    <a href='cetak_struk.php?id={$row['id']}' class='btn btn-sm btn-outline-primary' target='_blank'>🧾 Cetak Struk</a>
                                </td>
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
