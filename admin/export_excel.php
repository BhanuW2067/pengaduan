<?php
require_once('cek_akses.php');
require_once('../config/database.php');

// Set header untuk download file excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Pengaduan_" . date('Y-m-d') . ".xls");

// Query untuk mengambil data
$query = "SELECT p.*, ps.nama, t.tanggapan, t.tgl_tanggapan, pt.nama_petugas 
          FROM pengaduan p 
          LEFT JOIN pengaduan_siswa ps ON p.nik = ps.nik
          LEFT JOIN tanggapan t ON p.id_pengaduan = t.id_pengaduan
          LEFT JOIN petugas pt ON t.id_petugas = pt.id_petugas
          ORDER BY p.tgl_pengaduan DESC";
$result = $conn->query($query);
?>
<style>
    table {
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #000;
        padding: 5px;
    }
</style>

<table border="1">
    <thead>
        <tr>
            <th colspan="8" style="text-align: center; font-size: 16px;">LAPORAN PENGADUAN SISWA</th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center;">Periode: <?php echo date('d/m/Y'); ?></th>
        </tr>
        <tr>
            <th>No</th>
            <th>Tanggal Pengaduan</th>
            <th>Nama Pelapor</th>
            <th>Isi Laporan</th>
            <th>Status</th>
            <th>Tanggapan</th>
            <th>Petugas</th>
            <th>Tanggal Tanggapan</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        while($row = $result->fetch_assoc()): 
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo date('d/m/Y', strtotime($row['tgl_pengaduan'])); ?></td>
            <td><?php echo $row['nama']; ?></td>
            <td><?php echo $row['isi_laporan']; ?></td>
            <td><?php 
                switch($row['status']) {
                    case '0': echo 'Menunggu'; break;
                    case 'proses': echo 'Diproses'; break;
                    case 'selesai': echo 'Selesai'; break;
                }
            ?></td>
            <td><?php echo $row['tanggapan'] ?? '-'; ?></td>
            <td><?php echo $row['nama_petugas'] ?? '-'; ?></td>
            <td><?php echo $row['tgl_tanggapan'] ? date('d/m/Y', strtotime($row['tgl_tanggapan'])) : '-'; ?></td>
        </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="8" style="text-align: right; padding: 10px;">
                Jakarta, <?php echo date('d F Y'); ?><br><br>
                Petugas,<br><br><br>
                <?php echo $_SESSION['nama_petugas']; ?>
            </td>
        </tr>
    </tbody>
</table> 