<?php
session_start();
require_once('config/database.php');

if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Set header untuk download Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Riwayat_Pengaduan.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Ambil data pengaduan
$nik = $_SESSION['nik'];
$query = "SELECT p.*, t.tanggapan, t.tgl_tanggapan, pet.nama_petugas 
          FROM pengaduan p 
          LEFT JOIN tanggapan t ON p.id_pengaduan = t.id_pengaduan
          LEFT JOIN petugas pet ON t.id_petugas = pet.id_petugas 
          WHERE p.nik = ? 
          ORDER BY p.tgl_pengaduan DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nik);
$stmt->execute();
$result = $stmt->get_result();
?>

<table border="1">
    <tr>
        <th>Tanggal Pengaduan</th>
        <th>Isi Laporan</th>
        <th>Status</th>
        <th>Tanggapan</th>
        <th>Petugas</th>
        <th>Tanggal Tanggapan</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo date('d/m/Y', strtotime($row['tgl_pengaduan'])); ?></td>
            <td><?php echo $row['isi_laporan']; ?></td>
            <td>
                <?php
                switch($row['status']) {
                    case '0': echo 'Menunggu'; break;
                    case 'proses': echo 'Diproses'; break;
                    case 'selesai': echo 'Selesai'; break;
                }
                ?>
            </td>
            <td><?php echo $row['tanggapan'] ?? '-'; ?></td>
            <td><?php echo $row['nama_petugas'] ?? '-'; ?></td>
            <td><?php echo $row['tgl_tanggapan'] ? date('d/m/Y', strtotime($row['tgl_tanggapan'])) : '-'; ?></td>
        </tr>
    <?php endwhile; ?>
</table> 