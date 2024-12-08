<?php
session_start();

// Cek apakah user sudah login
if(!isset($_SESSION['level']) || ($_SESSION['level'] != 'admin' && $_SESSION['level'] != 'petugas')) {
    header("Location: ../login.php?error=Silakan login terlebih dahulu!");
    exit();
}

// Tidak perlu cek level karena semua fitur bisa diakses oleh admin dan petugas
?>