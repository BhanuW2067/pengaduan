<?php
require_once('cek_akses.php');
require_once('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pengaduan = $_POST['id_pengaduan'];
    
    // Cek status pengaduan terlebih dahulu
    $status_check = "SELECT status FROM pengaduan WHERE id_pengaduan = $id_pengaduan";
    $result = $conn->query($status_check);
    $pengaduan = $result->fetch_assoc();

    // Jika pengaduan sudah selesai, tidak bisa diedit
    if ($pengaduan['status'] == 'selesai') {
        header("Location: pengaduan.php?error=Pengaduan yang sudah selesai tidak dapat diedit");
        exit();
    }

    $tanggapan = $_POST['tanggapan'];
    $status = $_POST['status'];
    $tgl_tanggapan = date('Y-m-d');
    $id_petugas = $_SESSION['id_petugas'];
    $foto_tanggapan = null;

    // Proses upload file
    if (isset($_FILES['foto_tanggapan']) && $_FILES['foto_tanggapan']['error'] == 0 && $_FILES['foto_tanggapan']['size'] > 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['foto_tanggapan']['name'];
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($filetype, $allowed)) {
            if ($_FILES['foto_tanggapan']['size'] <= 2000000) {
                $upload_dir = '../assets/fototanggapan/';

                $new_filename = time() . '_' . uniqid() . '.' . $filetype;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($_FILES['foto_tanggapan']['tmp_name'], $upload_path)) {
                    $foto_tanggapan = $new_filename;
                } else {
                    header("Location: pengaduan.php?error=Gagal upload foto");
                    exit();
                }
            } else {
                header("Location: pengaduan.php?error=Ukuran file terlalu besar (max 2MB)");
                exit();
            }
        } else {
            header("Location: pengaduan.php?error=Format file tidak diizinkan");
            exit();
        }
    }

    // Mulai transaction
    mysqli_begin_transaction($conn);

    try {
        // Cek apakah sudah ada tanggapan
        $check_query = "SELECT id_tanggapan FROM tanggapan WHERE id_pengaduan = $id_pengaduan";
        $result = $conn->query($check_query);

        if ($result->num_rows > 0) {
            // Update tanggapan yang ada
            $query = "UPDATE tanggapan SET tanggapan = '$tanggapan', tgl_tanggapan = '$tgl_tanggapan', id_petugas = '$id_petugas'";
            if ($foto_tanggapan) {
                $query .= ", foto_tanggapan = '$foto_tanggapan'";
            }
            $query .= " WHERE id_pengaduan = $id_pengaduan";
            $conn->query($query);
        } else {
            // Tambah tanggapan baru
            $query = "INSERT INTO tanggapan (id_pengaduan, tgl_tanggapan, tanggapan, foto_tanggapan, id_petugas) 
                      VALUES ($id_pengaduan, '$tgl_tanggapan', '$tanggapan', '$foto_tanggapan', '$id_petugas')";
            $conn->query($query);
        }

        // Update status pengaduan
        $status_query = "UPDATE pengaduan SET status = '$status' WHERE id_pengaduan = $id_pengaduan";
        $conn->query($status_query);
        
        // Commit jika semua berhasil
        mysqli_commit($conn);
        header("Location: pengaduan.php?success=Tanggapan berhasil " . ($result->num_rows > 0 ? "diupdate" : "ditambahkan"));
    } catch (Exception $e) {
        // Rollback jika ada error
        mysqli_rollback($conn);
        // Hapus file jika ada
        if ($foto_tanggapan && file_exists($upload_dir . $foto_tanggapan)) {
            unlink($upload_dir . $foto_tanggapan);
        }
        header("Location: pengaduan.php?error=Gagal memproses tanggapan: " . $e->getMessage());
    }
}
?> 