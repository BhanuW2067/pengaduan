<?php
require_once('cek_akses.php');
require_once('../config/database.php');

// Pastikan yang mengakses adalah admin
if ($_SESSION['level'] != 'admin') {
    header("Location: dashboard.php?error=Anda tidak memiliki akses");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $nama_petugas = $_POST['nama_petugas'];
    $username = $_POST['username'];
    $level = $_POST['level'];

    if ($action == 'add') {
        // Validasi password untuk petugas baru
        if (empty($_POST['password'])) {
            header("Location: petugas.php?error=Password tidak boleh kosong");
            exit();
        }
        
        $password = md5($_POST['password']); // Menggunakan md5
        
        // Cek username sudah ada atau belum
        $check = $conn->query("SELECT id_petugas FROM petugas WHERE username = '$username'");
        if ($check->num_rows > 0) {
            header("Location: petugas.php?error=Username sudah digunakan");
            exit();
        }

        // Tambah petugas baru
        $query = "INSERT INTO petugas (nama_petugas, username, password, level) VALUES ('$nama_petugas', '$username', '$password', '$level')";
        if ($conn->query($query)) {
            header("Location: petugas.php?success=Data petugas berhasil ditambahkan");
        } else {
            header("Location: petugas.php?error=Gagal memproses data");
        }

    } else if ($action == 'edit') {
        $id_petugas = $_POST['id_petugas'];
        
        // Cek username sudah ada atau belum (kecuali username sendiri)
        $check = $conn->query("SELECT id_petugas FROM petugas WHERE username = '$username' AND id_petugas != $id_petugas");
        if ($check->num_rows > 0) {
            header("Location: petugas.php?error=Username sudah digunakan");
            exit();
        }

        $query = "UPDATE petugas SET nama_petugas = '$nama_petugas', username = '$username', level = '$level' WHERE id_petugas = $id_petugas";
        if (!empty($_POST['password'])) {
            $password = md5($_POST['password']); // Menggunakan md5
            $query = "UPDATE petugas SET nama_petugas = '$nama_petugas', username = '$username', password = '$password', level = '$level' WHERE id_petugas = $id_petugas";
        }

        if ($conn->query($query)) {
            header("Location: petugas.php?success=Data petugas berhasil diupdate");
        } else {
            header("Location: petugas.php?error=Gagal memproses data");
        }
    }

} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id_petugas = $_GET['id'];

    // Cek apakah ini adalah admin terakhir
    $check = $conn->query("SELECT COUNT(*) as admin_count FROM petugas WHERE level = 'admin'");
    $admin_count = $check->fetch_assoc()['admin_count'];

    // Cek level petugas yang akan dihapus
    $check_level = $conn->query("SELECT level FROM petugas WHERE id_petugas = $id_petugas");
    $petugas_level = $check_level->fetch_assoc()['level'];

    if ($admin_count <= 1 && $petugas_level == 'admin') {
        header("Location: petugas.php?error=Tidak dapat menghapus admin terakhir");
        exit();
    }

    $query = "DELETE FROM petugas WHERE id_petugas = $id_petugas";
    if ($conn->query($query)) {
        header("Location: petugas.php?success=Data petugas berhasil dihapus");
    } else {
        header("Location: petugas.php?error=Gagal menghapus data");
    }
}
?>