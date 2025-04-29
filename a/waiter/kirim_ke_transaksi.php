<?php
include '../config/koneksi.php';

if (isset($_POST['id_pelanggan']) && isset($_POST['id_menu']) && isset($_POST['jumlah']) && isset($_POST['total_bayar'])) {
    $id_pelanggan = mysqli_real_escape_string($koneksi, $_POST['id_pelanggan']);
    $id_menu = mysqli_real_escape_string($koneksi, $_POST['id_menu']);
    $jumlah = mysqli_real_escape_string($koneksi, $_POST['jumlah']);
    $total_bayar = mysqli_real_escape_string($koneksi, $_POST['total_bayar']);

    $query = "INSERT INTO transaksi (id_pelanggan, id_menu, jumlah, total_bayar, created_at) 
              VALUES ('$id_pelanggan', '$id_menu', '$jumlah', '$total_bayar', NOW())";

    if (mysqli_query($koneksi, $query)) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
