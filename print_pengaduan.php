<?php
session_start();
require_once('config/database.php');

if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil data pengaduan
$nik = $_SESSION['nik'];

// Ambil data masyarakat
$query_masyarakat = "SELECT nama FROM pengaduan_siswa WHERE nik = '$nik'";
$result_masyarakat = $conn->query($query_masyarakat);
$data_masyarakat = $result_masyarakat->fetch_assoc();
$nama_masyarakat = $data_masyarakat['nama'];

// Query untuk pengaduan
$query = "SELECT p.*, t.tanggapan, t.tgl_tanggapan, pet.nama_petugas 
          FROM pengaduan p 
          LEFT JOIN tanggapan t ON p.id_pengaduan = t.id_pengaduan
          LEFT JOIN petugas pet ON t.id_petugas = pet.id_petugas 
          WHERE p.nik = '$nik' 
          ORDER BY p.tgl_pengaduan DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Riwayat Pengaduan</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
                margin: 0;
            }
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            padding: 0;
        }

        .meta-info {
            margin-bottom: 20px;
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
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-print {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            padding-right: 100px;
        }

        .footer .tanggal {
            margin-bottom: 80px;
        }

        .footer .petugas {
            border-top: 1px solid #000;
            display: inline-block;
            padding-top: 5px;
            min-width: 200px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN RIWAYAT PENGADUAN</h2>
        <p>Sistem Pengaduan Masyarakat</p>
    </div>

    <div class="meta-info">
        <p>NIK: <?php echo $nik; ?></p>
        <p>Nama: <?php echo htmlspecialchars($nama_masyarakat); ?></p>
        <p>Tanggal Cetak: <?php echo date('d F Y'); ?></p>
    </div>

    <button onclick="window.print()" class="btn-print no-print">Cetak</button>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Isi Laporan</th>
                <th>Status</th>
                <th>Tanggapan</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($row = $result->fetch_assoc()): 
                $status = '';
                switch($row['status']) {
                    case '0': $status = 'Menunggu'; break;
                    case 'proses': $status = 'Diproses'; break;
                    case 'selesai': $status = 'Selesai'; break;
                }
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['tgl_pengaduan'])); ?></td>
                    <td><?php echo htmlspecialchars($row['isi_laporan']); ?></td>
                    <td><?php echo $status; ?></td>
                    <td><?php echo $row['tanggapan'] ? htmlspecialchars($row['tanggapan']) : '-'; ?></td>
                    <td><?php echo $row['nama_petugas'] ?? '-'; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="tanggal">
            <?php
            $tanggal = date('d F Y');
            echo "Jakarta, $tanggal";
            ?>
        </div>
        <div class="petugas">
            Petugas
        </div>
    </div>

    <script>
        // Otomatis memunculkan dialog print saat halaman dibuka
        window.onload = function() {
            // Uncomment baris di bawah jika ingin print otomatis
            // window.print();
        }
    </script>
</body>
</html> 