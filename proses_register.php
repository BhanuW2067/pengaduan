<?php
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); 

    $query = "INSERT INTO user (nama, kelas, email, password) 
              VALUES ('$nama', '$kelas', '$email', '$password')";

    if (mysqli_query($conn, $query)) {
        echo "Registrasi berhasil!";
        header("Location: login.php");
        exit();
    } else {
        echo "Registrasi gagal: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
