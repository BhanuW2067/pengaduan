<?php
require_once('cek_akses.php');
require_once('../config/database.php');

// Ambil data petugas
$id_petugas = $_SESSION['id_petugas'];
$query = "SELECT * FROM petugas WHERE id_petugas = $id_petugas";
$result = $conn->query($query);
$petugas = $result->fetch_assoc();

// Ambil pesan error/success jika ada
$error = $_GET['error'] ?? null;
$success = $_GET['success'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            font-size: 24px;
            color: #fff;
            margin: 0;
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
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .profile-section {
            margin-bottom: 30px;
        }

        .profile-info {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .profile-info label {
            font-weight: bold;
            color: #555;
        }

        .profile-info span {
            color: #333;
        }

        .edit-form {
            display: none;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
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
    </style>
</head>
<body>
<nav class="navbar">
        <h2>Sistem Pengaduan Sekolah</h2>
        <div class="auth-buttons">
            <a href="dashboard.php" class="auth-btn">Dashboard</a>
            <a href="../logoutadmin.php" class="auth-btn">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2><i class="fas fa-user-circle"></i> Profil Admin</h2>

        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="profile-section">
            <div class="profile-info">
                <label>Nama Lengkap:</label>
                <span><?php echo $petugas['nama_petugas']; ?></span>

                <label>Username:</label>
                <span><?php echo $petugas['username']; ?></span>

                <label>Level:</label>
                <span><?php echo $petugas['level']; ?></span>
            </div>

            <button onclick="showEditForm()" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Profil
            </button>
        </div>

        <div id="editForm" class="edit-form">
            <h3>Edit Profil</h3>
            <form action="update_profil.php" method="POST">
                <div class="form-group">
                    <label>Nama Lengkap:</label>
                    <input type="text" name="nama_petugas" value="<?php echo $petugas['nama_petugas']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" value="<?php echo $petugas['username']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Password Baru: (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" name="password">
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru:</label>
                    <input type="password" name="konfirmasi_password">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <button type="button" onclick="hideEditForm()" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </button>
            </form>
        </div>
    </div>

    <script>
        function showEditForm() {
            document.getElementById('editForm').style.display = 'block';
        }

        function hideEditForm() {
            document.getElementById('editForm').style.display = 'none';
        }
    </script>
</body>
</html> 