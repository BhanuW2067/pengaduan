<?php
session_start();
require_once('config/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; // Menghapus mysqli_real_escape_string
    $password = md5($_POST['password']);
    $level = $_POST['level'];

    // Login berdasarkan level
    $table = ($level == 'siswa') ? 'pengaduan_siswa' : 'petugas';
    $query = "SELECT * FROM $table WHERE username = '$username' AND password = '$password'" . ($level != 'siswa' ? " AND level = '$level'" : "");

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $username;
        if ($level == 'siswa') {
            $_SESSION['nik'] = $user['nik'];
            $_SESSION['level'] = 'siswa';
            header("Location: dashboard.php");
        } else {
            $_SESSION['id_petugas'] = $user['id_petugas'];
            $_SESSION['nama_petugas'] = $user['nama_petugas'];
            $_SESSION['level'] = $user['level'];
            header("Location: admin/dashboard.php");
        }
    } else {
        header("Location: index.php?error=Username atau password salah!");
    }
}

$conn->close();
?>