<?php
session_start();
require_once('../config/database.php');

if(!isset($_SESSION['id_admin'])) {
    header("Location: ../login_admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_admin = $_SESSION['id_admin'];
    $nama_admin = $_POST['nama_admin'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    $check_username = "SELECT id_admin FROM admin WHERE nama_admin = '$nama_admin' AND id_admin != $id_admin";
    $result = $conn->query($check_username);
    if ($result->num_rows > 0) {
        header("Location: profil.php?error=Username sudah digunakan");
        exit();
    }

    if (!empty($password)) {
        if ($password !== $konfirmasi_password) {
            header("Location: profil.php?error=Password dan konfirmasi password tidak cocok");
            exit();
        }

        $password_hash = md5($password);
        $query = "UPDATE admin SET nama_admin = '$nama_admin', email ='$email', password = '$password_hash' WHERE id_admin = $id_admin";
    } else {
        $query = "UPDATE admin SET nama_admin = '$nama_admin' WHERE id_admin = $id_admin";
    }

    if ($conn->query($query)) {
        $_SESSION['nama_admin'] = $nama_admin;
        $_SESSION['email'] = $email;
        
        header("Location: profil.php?success=Profil berhasil diupdate");
    } else {
        header("Location: profil.php?error=Gagal mengupdate profil");
    }
} else {
    header("Location: profil.php");
}
?> 
