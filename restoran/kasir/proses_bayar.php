<?php
include '../config/session.php';
include '../config/koneksi.php';

if ($_SESSION['role'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['id_order'])) {
    $id_order = $_POST['id_order'];

    // Update status order menjadi Lunas
    $update = mysqli_query($koneksi, "
        UPDATE orders 
        SET status = 'Lunas'
        WHERE id = '$id_order'
    ");

    if ($update) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
