<?php
$pembayaran_selesai = mysqli_query($koneksi, "
    SELECT pembayaran_selesai.*, pelanggan.nama_pelanggan
    FROM pembayaran_selesai
    JOIN pelanggan ON pembayaran_selesai.id_pelanggan = pelanggan.id
");
?>

<h3 class="mt-5">Pesanan Sudah Dibayar</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Pelanggan</th>
            <th>Total Bayar</th>
            <th>Uang Diterima</th>
            <th>Kembalian</th>
            <th>Waktu</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($pembayaran_selesai)) {
            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['nama_pelanggan']}</td>
                    <td>Rp " . number_format($row['total_bayar'], 0, ',', '.') . "</td>
                    <td>Rp " . number_format($row['uang_diterima'], 0, ',', '.') . "</td>
                    <td>Rp " . number_format($row['kembalian'], 0, ',', '.') . "</td>
                    <td>{$row['waktu']}</td>
                  </tr>";
            $no++;
        }
        ?>
    </tbody>
</table>
