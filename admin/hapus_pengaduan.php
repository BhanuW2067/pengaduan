<?php
require_once('cek_akses.php');
require_once('../config/database.php');

if (isset($_GET['id'])) {
    $id_pengaduan = $_GET['id'];
    
    // Mulai transaction
    $conn->begin_transaction();
    
    try {
        // Ambil informasi file foto
        $query_foto = "SELECT p.foto, t.foto_tanggapan 
                       FROM pengaduan p 
                       LEFT JOIN tanggapan t ON p.id_pengaduan = t.id_pengaduan 
                       WHERE p.id_pengaduan = $id_pengaduan";
        $result_foto = $conn->query($query_foto);
        $foto_data = $result_foto->fetch_assoc();

        // Hapus tanggapan terlebih dahulu (foreign key constraint)
        $query1 = "DELETE FROM tanggapan WHERE id_pengaduan = $id_pengaduan";
        $conn->query($query1);

        // Hapus pengaduan
        $query2 = "DELETE FROM pengaduan WHERE id_pengaduan = $id_pengaduan";
        $conn->query($query2);

        // Commit transaksi
        $conn->commit();

        // Hapus file foto jika ada
        foreach (['foto', 'foto_tanggapan'] as $foto_field) {
            if (!empty($foto_data[$foto_field])) {
                $foto_path = "../assets/fotolaporan/" . $foto_data[$foto_field];
                if (file_exists($foto_path)) {
                    unlink($foto_path);
                }
            }
        }

        header("Location: pengaduan.php?success=Pengaduan berhasil dihapus");
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $conn->rollback();
        header("Location: pengaduan.php?error=Gagal menghapus pengaduan: " . $e->getMessage());
    }
} else {
    header("Location: pengaduan.php?error=ID Pengaduan tidak valid");
}
?> 