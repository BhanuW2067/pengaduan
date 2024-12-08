<?php
require_once('cek_akses.php');
require_once('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_petugas = $_SESSION['id_petugas'];
    $nama_petugas = $_POST['nama_petugas'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Validasi username unik
    $check_username = "SELECT id_petugas FROM petugas WHERE username = '$username' AND id_petugas != $id_petugas";
    $result = $conn->query($check_username);
    if ($result->num_rows > 0) {
        header("Location: profil.php?error=Username sudah digunakan");
        exit();
    }

    // Jika password diisi
    if (!empty($password)) {
        // Validasi konfirmasi password
        if ($password !== $konfirmasi_password) {
            header("Location: profil.php?error=Password dan konfirmasi password tidak cocok");
            exit();
        }

        // Update dengan password baru
        $password_hash = md5($password);
        $query = "UPDATE petugas SET nama_petugas = '$nama_petugas', username = '$username', password = '$password_hash' WHERE id_petugas = $id_petugas";
    } else {
        // Update tanpa password
        $query = "UPDATE petugas SET nama_petugas = '$nama_petugas', username = '$username' WHERE id_petugas = $id_petugas";
    }

    if ($conn->query($query)) {
        // Update session
        $_SESSION['nama_petugas'] = $nama_petugas;
        $_SESSION['username'] = $username;
        
        header("Location: profil.php?success=Profil berhasil diupdate");
    } else {
        header("Location: profil.php?error=Gagal mengupdate profil");
    }
} else {
    header("Location: profil.php");
}
?> 