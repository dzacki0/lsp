<?php
include '../config/session.php';
include '../config/koneksi.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

// Pastikan ID ada dalam URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus menu berdasarkan ID
    $query = mysqli_query($koneksi, "DELETE FROM menu WHERE id='$id'");

    if ($query) {
        header("Location: menu.php?status=success&message=Menu berhasil dihapus");
    } else {
        header("Location: menu.php?status=error&message=Gagal menghapus menu");
    }
} else {
    header("Location: menu.php?status=error&message=ID tidak ditemukan");
}
?>
