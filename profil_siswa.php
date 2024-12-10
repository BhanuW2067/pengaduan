<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$query = "SELECT * FROM user WHERE id_user = '$id_user'";
$result = $conn->query($query);
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION['error'] = "Data tidak ditemukan!";
    header("Location: profil_siswa.php");
    exit();
}

if (isset($_POST['update_profil'])) {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $email = $_POST['email'];
    $password = trim($_POST['password']);

    if (!empty($password)) {
        $password_hash = md5($password);
        $query = "UPDATE user SET nama = '$nama', kelas = '$kelas', email = '$email', password = '$password_hash' WHERE id_user = '$id_user'";
    } else {
        $query = "UPDATE user SET nama = '$nama', kelas = '$kelas', email = '$email' WHERE id_user = '$id_user'";
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
    <title>Profil User</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #FFF7F7;
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
            font-size: 24px;
            margin: 0;
            color: #ffffff ;
        }

        .logout-btn {
            background-color: #fd0000;
            color: #ffffff;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #ec512b;
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
        }

        .auth-btn {
            background-color: #fff;
            color: #1230AE;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            padding-right: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="tel"]:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .btn-update {
            width: 100%;
            padding: 10px;
            background-color: #0630f4;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-update:hover {
            background-color: #1861cd;
        }

        .alert {
            padding: 10px;
            border-radius: 5px;
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

        .form-actions {
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h2>Sistem Pengaduan Sekolah</h2>
        <div class="user-info">
            <a href="dashboard.php" class="auth-btn">Dashboard</a>
            <a href="../logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2>Profil User</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?php echo $user['nama']; ?>" required>
            </div>

            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" value="<?php echo $user['kelas']; ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>

            <div class="form-group">
                <label>Password Baru (Opsional)</label>
                <input type="password" name="password">
            </div>

            <div class="form-actions">
                <button type="submit" name="update_profil" class="btn-update">Update Profil</button>
            </div>
        </form>
    </div>
</body>
</html>
