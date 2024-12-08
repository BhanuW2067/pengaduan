<?php
session_start();
require_once('config/database.php');

// Cek login
if(!isset($_SESSION['username'])) {
    header("Location: login.php?message=Silakan login terlebih dahulu");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengaduan</title>
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
            margin: 30px auto;
            padding: 20px;
        }

        .pengaduan-form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }

        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            height: 150px;
            resize: vertical;
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
        <div class="pengaduan-form">
            <h2 style="margin-bottom: 20px;">Form Pengaduan</h2>

            <?php
            if(isset($_GET['status'])) {
                if($_GET['status'] == 'success') {
                    echo '<div class="alert alert-success">Pengaduan berhasil dikirim!</div>';
                } else if($_GET['status'] == 'error') {
                    $message = isset($_GET['message']) ? $_GET['message'] : 'Gagal mengirim pengaduan!';
                    echo '<div class="alert alert-error">' . htmlspecialchars($message) . '</div>';
                }
            }
            ?>

            <form action="proses_pengaduan.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Isi Laporan Pengaduan</label>
                    <textarea name="isi_laporan" required placeholder="Tuliskan detail pengaduan Anda..."></textarea>
                </div>

                <div class="form-group">
                    <label>Foto Bukti</label>
                    <input type="file" name="foto" accept="image/jpeg,image/png">
                    <div class="form-text">Format yang diizinkan: JPG, JPEG, PNG</div>
                </div>

                <button type="submit" class="submit-btn">Kirim Pengaduan</button>
            </form>
        </div>
    </div>
</body>
</html>
