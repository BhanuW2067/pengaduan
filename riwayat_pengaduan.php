<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['id_user'];
$query = "SELECT p.*, t.tanggapan, t.tgl_tanggapan, t.foto_tanggapan, a.nama_admin 
          FROM pengaduan p 
          LEFT JOIN tanggapan t ON p.id_pengaduan = t.id_pengaduan
          LEFT JOIN admin a ON t.id_admin = a.id_admin 
          WHERE p.id_user = '$user' 
          ORDER BY p.tgl_pengaduan DESC";
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
        }

        .navbar {
            background-color: #1230AE;
            padding: 10px 30px;
            margin-bottom: 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .navbar h2 {
            margin: 0;
            font-size: 24px;
            color: #fff;
        }

        .auth-buttons {
            display: flex;
            gap: 20px;
        }

        .auth-btn, .logout-btn {
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s, transform 0.2s ease;
        }

        .auth-btn {
            background-color: #fff;
            color: #1230AE;
        }

        .auth-btn:hover {
            background-color: #f0f0f0;
        }

        .logout-btn {
            background-color: #fd0000;
            color: white;
        }

        .logout-btn:hover {
            background-color: #ec512b;
            transform: scale(1.05);
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            font-size: 20px;
        }

        .export-buttons {
            text-align: right;
            margin-bottom: 20px;
        }

        .btn-export {
            padding: 10px 20px;
            background-color: #0630f4;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-export:hover {
            background-color: #1861cd;
        }

        .pengaduan-item {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s;
        }

        .pengaduan-item:hover {
            transform: translateY(-5px);
        }

        .pengaduan-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .tanggal {
            color: #666;
            font-size: 14px;
        }

        .status {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
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
            margin: 15px 0;
            color: #333;
        }

        .foto-bukti {
            max-width: 300px; /* Mengatur ukuran gambar */
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 15px;
            transition: transform 0.2s ease-in-out;
        }

        .foto-bukti:hover {
            transform: scale(1.05);
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
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .tanggapan h4 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .tanggapan-content {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
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
            margin: 15px 0;
            text-align: center;
        }

        .foto-tanggapan {
            max-width: 200px;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }

        .foto-tanggapan:hover {
            transform: scale(1.1);
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

    </style>
</head>
<body>
<nav class="navbar">
        <h2>Sistem Pengaduan Sekolah</h2>
        <div class="auth-buttons">
            <a href="dashboard.php" class="auth-btn">Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>
    
    <div class="container">
        <h2>Riwayat Pengaduan</h2>
        <div class="export-buttons">
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
                                case 'diterima':
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
                        <?php echo $row['isi_laporan']; ?>
                    </div>
                    <?php if($row['foto']): ?>
                        <img src="../assets/fotolaporan/<?php echo $row['foto']; ?>" alt="Foto Bukti" class="foto-bukti">
                    <?php endif; ?>
                    <?php if($row['tanggapan']): ?>
                        <div class="tanggapan">
                            <h4>Tanggapan Admin:</h4>
                            <div class="tanggapan-content">
                                <p><?php echo $row['tanggapan']; ?></p>
                                <?php if($row['foto_tanggapan']): ?>
                                    <div class="foto-tanggapan-container">
                                        <img src="assets/fototanggapan/<?php echo $row['foto_tanggapan']; ?>" alt="Foto Tanggapan" class="foto-tanggapan">
                                        <a href="assets/fototanggapan/<?php echo $row['foto_tanggapan']; ?>" target="_blank" class="lihat-foto">Lihat Foto</a>
                                    </div>
                                <?php endif; ?>
                                <div class="tanggapan-info">
                                    <span class="petugas">Oleh: <?php echo $row['nama_admin']; ?></span>
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
