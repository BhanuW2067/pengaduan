<?php
session_start();
require_once('../config/database.php');

if(!isset($_SESSION['id_admin'])) {
    header("Location: ../login_admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pengaduan = $_POST['id_pengaduan'];
    
    $status_check = "SELECT status FROM pengaduan WHERE id_pengaduan = $id_pengaduan";
    $result = $conn->query($status_check);
    $pengaduan = $result->fetch_assoc();
    
    if ($pengaduan['status'] == 'selesai') {
        header("Location: pengaduan.php?error=Pengaduan yang sudah selesai tidak dapat diedit");
        exit();
    }

    $tanggapan = $_POST['tanggapan'];
    $status = $_POST['status'];
    $tgl_tanggapan = date('Y-m-d');
    $id_admin = $_SESSION['id_admin'];
    $foto_tanggapan = null;

    if (isset($_FILES['foto_tanggapan']) && $_FILES['foto_tanggapan']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filetype = strtolower(pathinfo($_FILES['foto_tanggapan']['name'], PATHINFO_EXTENSION));

        if (in_array($filetype, $allowed) && $_FILES['foto_tanggapan']['size'] <= 2000000) {
            $new_filename = time() . '_' . uniqid() . '.' . $filetype;
            $upload_path = '../assets/fototanggapan/' . $new_filename;

            if (move_uploaded_file($_FILES['foto_tanggapan']['tmp_name'], $upload_path)) {
                $foto_tanggapan = $new_filename;
            } else {
                header("Location: pengaduan.php?error=Gagal upload foto");
                exit();
            }
        } else {
            header("Location: pengaduan.php?error=Format atau ukuran file tidak diizinkan");
            exit();
        }
    }

    $conn->begin_transaction();

    try {
        $check_query = "SELECT id_tanggapan FROM tanggapan WHERE id_pengaduan = $id_pengaduan";
        $result = $conn->query($check_query);

        if ($result->num_rows > 0) {
            $query = "UPDATE tanggapan SET tanggapan = '$tanggapan', tgl_tanggapan = '$tgl_tanggapan', id_admin = '$id_admin'" .
                     ($foto_tanggapan ? ", foto_tanggapan = '$foto_tanggapan'" : "") . 
                     " WHERE id_pengaduan = $id_pengaduan";
            $conn->query($query);
        } else {
            $query = "INSERT INTO tanggapan (id_pengaduan, tgl_tanggapan, tanggapan, foto_tanggapan, id_admin) 
                      VALUES ($id_pengaduan, '$tgl_tanggapan', '$tanggapan', '$foto_tanggapan', '$id_admin')";
            $conn->query($query);
        }

        $status_query = "UPDATE pengaduan SET status = '$status' WHERE id_pengaduan = $id_pengaduan";
        $conn->query($status_query);

        $conn->commit();
        header("Location: pengaduan.php?success=Tanggapan berhasil " . ($result->num_rows > 0 ? "diupdate" : "ditambahkan"));
    } catch (Exception $e) {
        $conn->rollback();
        if ($foto_tanggapan) {
            unlink('../assets/fototanggapan/' . $foto_tanggapan);
        }
        header("Location: pengaduan.php?error=Gagal memproses tanggapan");
    }
}
?>
