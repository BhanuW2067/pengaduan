<?php
session_start();
require_once('config/database.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil data pengaduan berdasarkan NIK user yang login
$nik = $_SESSION['nik'];
$query = "SELECT p.*, t.tanggapan, t.tgl_tanggapan, t.foto_tanggapan, pet.nama_petugas 
          FROM pengaduan p 
          LEFT JOIN tanggapan t ON p.id_pengaduan = t.id_pengaduan
          LEFT JOIN petugas pet ON t.id_petugas = pet.id_petugas 
          WHERE p.nik = '$nik' 
          ORDER BY p.tgl_pengaduan DESC"; // Menghapus prepared statement
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pengaduan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f0f0f0;
        }

        .navbar {
            background-color: #4CAF50;
            padding: 15px 30px;
            margin-bottom: 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar h2 {
            margin: 0;
            font-size: 24px;
            color: #fff;
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
        }

        .auth-btn {
            background-color: #fff;
            color: #4CAF50;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .pengaduan-item {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .pengaduan-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .tanggal {
            color: #666;
            font-size: 14px;
        }

        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-0 {
            background-color: #ffeeba;
            color: #856404;
        }

        .status-proses {
            background-color: #b8daff;
            color: #004085;
        }

        .status-selesai {
            background-color: #c3e6cb;
            color: #155724;
        }

        .isi-laporan {
            margin: 10px 0;
            color: #333;
        }

        .foto-bukti {
            max-width: 200px;
            margin-top: 10px;
            border-radius: 4px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #4CAF50;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .no-data {
            text-align: center;
            color: #666;
            padding: 20px;
        }

        .tanggapan {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .tanggapan h4 {
            color: #333;
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .tanggapan-content {
            background-color: #f8f9fa;
            padding: 12px;
            border-radius: 4px;
        }

        .tanggapan-content p {
            margin: 0 0 10px 0;
            color: #444;
        }

        .tanggapan-info {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #666;
        }

        .petugas {
            font-weight: bold;
        }

        .foto-tanggapan-container {
            margin: 10px 0;
            text-align: center;
        }

        .foto-tanggapan {
            max-width: 200px;
            border-radius: 4px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .foto-tanggapan:hover {
            transform: scale(1.05);
        }

        .lihat-foto {
            display: block;
            color: #4CAF50;
            text-decoration: none;
            font-size: 14px;
            margin-top: 5px;
        }

        .lihat-foto:hover {
            text-decoration: underline;
        }

        .export-buttons {
            text-align: right;
            margin-bottom: 20px;
        }

        .btn-export {
            display: inline-block;
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-left: 10px;
            font-size: 14px;
        }

        .btn-export:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<nav class="navbar">
        <h2>Sistem Pengaduan Sekolah</h2>
        <div class="auth-buttons">
            <a href="dashboard.php" class="auth-btn">Dashboard</a>
            <a href="logout.php" class="auth-btn">Logout</a>
        </div>
    </nav>
    
    <div class="container">
        <h2>Riwayat Pengaduan</h2>
        <div class="export-buttons">
            <a href="export_excel.php" class="btn-export">Export Excel</a>
            <a href="print_pengaduan.php" class="btn-export" target="_blank">Print</a>
        </div>

        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="pengaduan-item">
                    <div class="pengaduan-header">
                        <span class="tanggal"><?php echo date('d F Y', strtotime($row['tgl_pengaduan'])); ?></span>
                        <span class="status status-<?php echo $row['status']; ?>">
                            <?php
                            switch($row['status']) {
                                case '0':
                                    echo 'Menunggu';
                                    break;
                                case 'proses':
                                    echo 'Diproses';
                                    break;
                                case 'selesai':
                                    echo 'Selesai';
                                    break;
                            }
                            ?>
                        </span>
                    </div>
                    <div class="isi-laporan">
                        <?php echo nl2br(htmlspecialchars($row['isi_laporan'])); ?>
                    </div>
                    <?php if($row['foto']): ?>
                        <img src="assets/fotolaporan/<?php echo $row['foto']; ?>" alt="Foto Bukti" class="foto-bukti">
                    <?php endif; ?>
                    <?php if($row['tanggapan']): ?>
                        <div class="tanggapan">
                            <h4>Tanggapan Admin:</h4>
                            <div class="tanggapan-content">
                                <p><?php echo htmlspecialchars($row['tanggapan']); ?></p>
                                <?php if($row['foto_tanggapan']): ?>
                                    <div class="foto-tanggapan-container">
                                        <img src="assets/fototanggapan/<?php echo $row['foto_tanggapan']; ?>" alt="Foto Tanggapan" class="foto-tanggapan">
                                        <a href="assets/fototanggapan/<?php echo $row['foto_tanggapan']; ?>" target="_blank" class="lihat-foto">Lihat Foto</a>
                                    </div>
                                <?php endif; ?>
                                <div class="tanggapan-info">
                                    <span class="petugas">Oleh: <?php echo htmlspecialchars($row['nama_petugas']); ?></span>
                                    <span class="tanggal-tanggapan"><?php echo date('d F Y', strtotime($row['tgl_tanggapan'])); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-data">
                Belum ada pengaduan yang disubmit.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
