<?php
require_once('cek_akses.php');
require_once('../config/database.php');

// Query untuk mengambil data
$query = "SELECT p.*, ps.nama, t.tanggapan, t.tgl_tanggapan, pt.nama_petugas 
          FROM pengaduan p 
          LEFT JOIN pengaduan_siswa ps ON p.nik = ps.nik
          LEFT JOIN tanggapan t ON p.id_pengaduan = t.id_pengaduan
          LEFT JOIN petugas pt ON t.id_petugas = pt.id_petugas
          ORDER BY p.tgl_pengaduan DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengaduan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: white;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h2 {
            margin: 0;
            padding: 0;
        }
        
        .header p {
            margin: 5px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        
        .ttd {
            margin-top: 80px;
        }
        
        @media print {
            .no-print {
                display: none;
            }
            
            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PENGADUAN SISWA</h2>
        <p>Periode: <?php echo date('d/m/Y'); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Isi Laporan</th>
                <th>Status</th>
                <th>Tanggapan</th>
                <th>Petugas</th>
                <th>Tgl Tanggapan</th>
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
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td><?php echo htmlspecialchars($row['isi_laporan']); ?></td>
                <td><?php 
                    switch($row['status']) {
                        case '0': echo 'Menunggu'; break;
                        case 'proses': echo 'Diproses'; break;
                        case 'selesai': echo 'Selesai'; break;
                    }
                ?></td>
                <td><?php echo htmlspecialchars($row['tanggapan'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($row['nama_petugas'] ?? '-'); ?></td>
                <td><?php echo $row['tgl_tanggapan'] ? date('d/m/Y', strtotime($row['tgl_tanggapan'])) : '-'; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Jakarta, <?php echo date('d F Y'); ?></p>
        <div class="ttd">
            <p>Petugas</p>
            <br>
            <p><?php echo $_SESSION['nama_petugas']; ?></p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">
            Print Laporan
        </button>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            if (!window.location.search.includes('noprint')) {
                window.print();
            }
        }
    </script>
</body>
</html> 