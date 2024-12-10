<?php
session_start();
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../login_admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .navbar {
            background-color: #1230AE;
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar h2 {
            margin: 0;
            font-size: 24px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .menu-item {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .menu-item i {
            font-size: 40px;
            margin-bottom: 15px;
            color: #4CAF50;
            transition: color 0.3s ease;
        }

        .menu-item:hover i {
            color: #45a049;
        }

        .menu-item h3 {
            margin: 10px 0;
            color: #333;
            font-size: 18px;
        }

        .menu-item p {
            color: #666;
            margin: 0;
            font-size: 14px;
            line-height: 1.4;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .welcome-message {
            background: #1230AE;
            color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .welcome-message h2 {
            color: white;
            margin: 0 0 10px 0;
        }

        .welcome-message p {
            color: rgba(255,255,255,0.9);
            margin: 0;
            font-size: 16px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Pengaduan Sekolah</h2>
        <div class="user-info">
            <span>Selamat datang, <?php echo $_SESSION['nama_admin']; ?></span>
            <a href="../logout_admin.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="welcome-message">
            <p>Selamat Datang Di Panel Admin Pengaduan Sekolah.</p>
        </div>

        <div class="menu-grid">
            <a href="pengaduan.php" class="menu-item">
                <h3>Kelola Pengaduan</h3>
                <p>Lihat dan tanggapi pengaduan</p>
            </a>

            <a href="laporan.php" class="menu-item">
                <h3>Laporan</h3>
                <p>Lihat & Cetak laporan pengaduan</p>
            </a>

            <a href="profil.php" class="menu-item">
                <h3>Profil</h3>
                <p>Kelola Akun</p>
            </a>

            <a href="admin.php" class="menu-item">
                <h3>Kelola Admin</h3>
                <p>Tambah & kelola akun Admin</p>
            </a>
        </div>
    </div>
</body>
</html>
