<?php
include '../config/session.php';
include '../config/koneksi.php';

// Pastikan hanya waiter yang dapat mengakses
if ($_SESSION['role'] != 'waiter') {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id_order'])) {
    $id_order = $_GET['id_order'];

    // Ambil data pesanan dari tabel orders
    $query = "SELECT orders.id, orders.id_pelanggan, orders.id_menu, orders.jumlah, menu.harga
              FROM orders 
              JOIN menu ON orders.id_menu = menu.id
              WHERE orders.id = '$id_order'";
    $result = mysqli_query($koneksi, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $id_pelanggan = $row['id_pelanggan'];
        $id_menu = $row['id_menu'];
        $jumlah = $row['jumlah'];
        $harga = $row['harga'];
        $total_bayar = $harga * $jumlah;
        $status = "Dikirim ke Kasir"; // Status yang baru

        // Masukkan data transaksi ke tabel transaksi
        $insert_transaksi = "INSERT INTO transaksi (id_pelanggan, id_menu, jumlah, total_bayar, status)
                             VALUES ('$id_pelanggan', '$id_menu', '$jumlah', '$total_bayar', '$status')";
        $insert_result = mysqli_query($koneksi, $insert_transaksi);

        // Jika berhasil memasukkan transaksi, update status pesanan di tabel orders
        if ($insert_result) {
            $update_order = "UPDATE orders SET status = '$status' WHERE id = '$id_order'";
            $update_result = mysqli_query($koneksi, $update_order);

            if ($update_result) {
                echo 'success'; // Pesanan berhasil dikirim ke kasir
            } else {
                echo 'error'; // Jika gagal update status pesanan
            }
        } else {
            echo 'error'; // Jika gagal memasukkan transaksi
        }
    } else {
        echo 'error'; // Pesanan tidak ditemukan
    }
} else {
    echo 'error'; // Jika ID tidak ditemukan
}
?>
