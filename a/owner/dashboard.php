<?php
include '../config/session.php';

if ($_SESSION['role'] != 'owner') {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f6fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: #fd7e14; /* Oranye */
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
            color: #fd7e14 !important;
        }
        .content-container {
            margin-top: 120px;
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
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Owner Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto ms-3">
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
<div class="container content-container">
    <h3>Dashboard Owner</h3>
    <p>Selamat datang, <?= $_SESSION['username']; ?>. Anda login sebagai Owner.</p>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
