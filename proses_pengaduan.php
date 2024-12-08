<?php
session_start();
require_once('config/database.php');

// Cek login dan NIK
if (!isset($_SESSION['username']) || !isset($_SESSION['nik'])) {
    header("Location: login.php?message=Silakan login terlebih dahulu");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isi_laporan = $_POST['isi_laporan'];
    $tgl_pengaduan = date('Y-m-d');
    $nik = $_SESSION['nik']; // NIK dari session
    $status = '0';
    $foto = null;

    // Handle file upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filetype = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

        if (in_array(strtolower($filetype), $allowed)) {
            $upload_dir = 'assets/fotolaporan/';

            $new_filename = time() . '.' . $filetype;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                $foto = $new_filename;
            } else {
                header("Location: pengaduan.php?status=error&message=Gagal upload file");
                exit();
            }
        } else {
            header("Location: pengaduan.php?status=error&message=Format file tidak diizinkan");
            exit();
        }
    }

    // Insert pengaduan
    $query = "INSERT INTO pengaduan (tgl_pengaduan, nik, isi_laporan, foto, status) 
              VALUES ('$tgl_pengaduan', '$nik', '$isi_laporan', '$foto', '$status')";

    if ($conn->query($query)) {
        header("Location: pengaduan.php?status=success");
    } else {
        header("Location: pengaduan.php?status=error&message=Gagal menyimpan pengaduan");
    }
}

$conn->close();
?>