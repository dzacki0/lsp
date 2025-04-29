<?php
include '../config/koneksi.php';

// Proses Registrasi
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    
    // Enkripsi password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Memasukkan data user baru ke dalam database
    $query = mysqli_query($koneksi, "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')");

    if ($query) {
        echo "<script>alert('Registrasi berhasil!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Gagal registrasi!'); window.location.href='register.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3>Registrasi User</h3>
    
    <form action="register.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="waiter">Waiter</option>
                <option value="kasir">Kasir</option>
                <option value="owner">Owner</option>
            </select>
        </div>

        <button type="submit" name="register" class="btn btn-primary">Daftar</button>
    </form>

    <p class="mt-3">Sudah punya akun? <a href="index.php">Login di sini</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
