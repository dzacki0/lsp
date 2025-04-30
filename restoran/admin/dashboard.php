<?php
include '../config/session.php';
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f6fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
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
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">🍽️ Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
            
                <li class="nav-item">
                    <a class="nav-link" href="meja.php">🪑 Kelola Meja</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="menu.php">🍽️ Kelola Menu</a>
                </li>
            </ul>
            <span class="text-white me-3">👤 <?= $_SESSION['username']; ?></span>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<!-- Content -->
<div class="container welcome-box">
    <div class="content-box">
        <h3>Selamat Datang, <strong><?= $_SESSION['username']; ?></strong>!</h3>
        <p>Gunakan menu di atas untuk mengelola sistem restoran dengan mudah dan cepat.</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
