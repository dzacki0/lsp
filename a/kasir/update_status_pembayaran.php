<?php
include '../config/session.php';
include '../config/koneksi.php';

if ($_SESSION['role'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id_transaksi'])) {
    $id_transaksi = $_GET['id_transaksi'];

    // Update status transaksi menjadi 'Lunas'
    $update_query = "UPDATE transaksi SET status = 'Lunas' WHERE id = '$id_transaksi'";
    $update_result = mysqli_query($koneksi, $update_query);

    if ($update_result) {
        echo 'success'; // Pembayaran berhasil
    } else {
        echo 'error'; // Jika gagal memperbarui status transaksi
    }
} else {
    echo 'error'; // Jika ID transaksi tidak ditemukan
}
?>
