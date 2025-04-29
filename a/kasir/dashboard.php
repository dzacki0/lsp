<?php
include '../config/session.php';

if ($_SESSION['role'] != 'kasir') {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f6fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: #28a745; /* Hijau */
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
            color: #0d6efd !important;
        }
        .welcome-box {
            margin-top: 100px;
        }
        .content-box {
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
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">🍽️ Kasir Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto ms-3">
                <li class="nav-item">
                    <a class="nav-link" href="pembayaran.php">💳 Proses Pembayaran</a>
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

<!-- Content -->
<div class="container" style="margin-top: 100px;">
    <div class="dashboard-box text-center">
        <div class="dashboard-title mb-3">Selamat Datang, <?= $_SESSION['username']; ?> 👋</div>
        <p class="text-muted">Silakan gunakan menu di atas untuk melanjutkan tugas Anda sebagai kasir.</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
