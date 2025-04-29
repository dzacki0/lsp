<?php
include '../config/koneksi.php';

if (!isset($_GET['id'])) {
    die('ID tidak tersedia.');
}

$id_pembayaran = $_GET['id'];

$pembayaran = mysqli_query($koneksi, "
    SELECT ps.*, pelanggan.nama_pelanggan 
    FROM pembayaran_selesai ps 
    JOIN pelanggan ON ps.id_pelanggan = pelanggan.id 
    WHERE ps.id = '$id_pembayaran'
");

$data = mysqli_fetch_assoc($pembayaran);
if (!$data) {
    die('Data pembayaran tidak ditemukan.');
}

$pesanan = mysqli_query($koneksi, "
    SELECT menu.nama_menu, menu.harga, orders.jumlah 
    FROM orders 
    JOIN menu ON orders.id_menu = menu.id 
    WHERE orders.id_pelanggan = '{$data['id_pelanggan']}'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: monospace;
            font-size: 14px;
            width: 300px;
            margin: 0 auto;
        }
        .struk-container {
            border: 1px dashed #000;
            padding: 10px;
            margin-top: 20px;
        }
        .center {
            text-align: center;
        }
        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }
        th, td {
            padding: 3px 0;
        }
        .totals td {
            font-weight: bold;
        }
        @media print {
            body {
                margin: 0;
            }
            .btn-cetak {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="struk-container">
    <div class="center">
        <h3>📋 STRUK PEMBAYARAN</h3>
        <p>Warung Makan Kita</p>
        <p>------------------------------</p>
    </div>

    <p><strong>Pelanggan:</strong> <?= $data['nama_pelanggan'] ?></p>
    <p><strong>Waktu:</strong> <?= $data['waktu'] ?></p>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            if (mysqli_num_rows($pesanan) > 0) {
                while ($item = mysqli_fetch_assoc($pesanan)) {
                    $subtotal = $item['harga'] * $item['jumlah'];
                    $total += $subtotal;
                    echo "<tr>
                            <td>{$item['nama_menu']}</td>
                            <td>{$item['jumlah']}</td>
                            <td style='text-align: right;'>Rp " . number_format($subtotal, 0, ',', '.') . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Data menu tidak tersedia</td></tr>";
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="totals">
                <td colspan="2">Total</td>
                <td style="text-align: right;">Rp <?= number_format($total, 0, ',', '.') ?></td>
            </tr>
            <tr class="totals">
                <td colspan="2">Uang Diterima</td>
                <td style="text-align: right;">Rp <?= number_format($data['uang_diterima'], 0, ',', '.') ?></td>
            </tr>
            <tr class="totals">
                <td colspan="2">Kembalian</td>
                <td style="text-align: right;">Rp <?= number_format($data['kembalian'], 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="center mt-3">
        <p>Terima kasih! 🙏</p>
        <p>------------------------------</p>
        <p>~ Sistem Kasir ~</p>
    </div>
</div>

<div class="center mt-3">
    <button class="btn-cetak" onclick="window.print()">🖨️ Cetak Struk</button>
</div>

</body>
</html>
