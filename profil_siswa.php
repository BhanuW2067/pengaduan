<?php
session_start();
require_once('config/database.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil data siswa dari database
$nik = $_SESSION['nik'];
$query = "SELECT * FROM pengaduan_siswa WHERE nik = '$nik'";
$result = $conn->query($query);
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION['error'] = "Data tidak ditemukan!";
    header("Location: profil_siswa.php");
    exit();
}

// Proses update profil
if (isset($_POST['update_profil'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $telp = $_POST['telp'];
    $password = trim($_POST['password']);
    
    // Siapkan query untuk update
    if (!empty($password)) {
        $password_hash = md5($password);
        $query = "UPDATE pengaduan_siswa SET nama = '$nama', username = '$username', telp = '$telp', password = '$password_hash' WHERE nik = '$nik'";
    } else {
        $query = "UPDATE pengaduan_siswa SET nama = '$nama', username = '$username', telp = '$telp' WHERE nik = '$nik'";
    }

    if ($conn->query($query)) {
        $_SESSION['success'] = "Profil berhasil diperbarui!";
        header("Location: profil_siswa.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui profil!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Siswa</title>
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
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .nik-display {
            background-color: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            color: #666;
        }

        .btn-update {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        .btn-update:hover {
            background-color: #45a049;
        }

        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
        <h2>Profil Siswa</h2>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>NIK</label>
                <div class="nik-display"><?php echo htmlspecialchars($user['nik']); ?></div>
            </div>

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="form-group">
                <label>No. Telepon</label>
                <input type="tel" name="telp" value="<?php echo htmlspecialchars($user['telp']); ?>" required>
            </div>

            <div class="form-group">
                <label>Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password">
            </div>

            <button type="submit" name="update_profil" class="btn-update">Update Profil</button>
        </form>
    </div>
</body>
</html> 