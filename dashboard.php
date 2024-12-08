<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar h2 {
            font-size: 24px;
        }

        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logout-btn {
            background-color: #fff;
            color: #4CAF50;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #f0f0f0;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }

        .welcome-card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .welcome-card h1 {
            color: #333;
            margin-bottom: 15px;
        }

        .welcome-card p {
            color: #666;
            font-size: 18px;
            line-height: 1.6;
        }

        .dashboard-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .menu-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .menu-card:hover {
            transform: translateY(-5px);
        }

        .menu-card h3 {
            color: #4CAF50;
            margin-bottom: 15px;
        }

        .menu-card p {
            color: #666;
            margin-bottom: 15px;
        }

        .menu-btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .menu-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h2>Sistem Pengaduan Sekolah</h2>
        <div class="user-info">
            <span>Selamat datang, <?php echo $_SESSION['username']; ?>!</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-card">
            <h1>Dashboard</h1>
            <p>Selamat datang di Sistem Pengaduan Sekolah. Silakan pilih menu di bawah ini untuk melanjutkan.</p>
        </div>

        <div class="dashboard-menu">
            <div class="menu-card">
                <h3>Buat Pengaduan</h3>
                <p>Buat pengaduan baru terkait masalah di sekolah</p>
                <a href="pengaduan.php" class="menu-btn">Buat Pengaduan</a>
            </div>

            <div class="menu-card">
                <h3>Riwayat Pengaduan</h3>
                <p>Lihat status dan riwayat pengaduan Anda</p>
                <a href="riwayat_pengaduan.php" class="menu-btn">Lihat Riwayat</a>
            </div>

            <div class="menu-card">
                <h3>Profil</h3>
                <p>Kelola informasi profil Anda</p>
                <a href="profil_siswa.php" class="menu-btn">Kelola Profil</a>
            </div>
        </div>
    </div>
</body>
</html> 