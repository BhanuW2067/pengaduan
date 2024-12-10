<?php
session_start();
require_once('../config/database.php');

if(!isset($_SESSION['id_admin'])) {
    header("Location: ../login_admin.php");
    exit();
}

$id_admin = $_SESSION['id_admin'];
$query = "SELECT * FROM admin WHERE id_admin = $id_admin";
$result = $conn->query($query);
$admin = $result->fetch_assoc();

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
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 30px;
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
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .btn-update,
        .btn-secondary {
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

        .btn-update:hover,
        .btn-secondary:hover {
            background-color: #1861cd;  
        }

        .btn-secondary {
            background-color: #f44336; 
            margin-top: 10px;
        }

        .btn-secondary:hover {
            background-color: #d32f2f;  
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

        .form-actions {
            text-align: center;
            margin-top: 20px;
        }

        .edit-form {
            display: none;
            margin-top: 20px;
        }

        .edit-form h3 {
            padding-bottom: 5px;
        }

    </style>
</head>
<body>
    <nav class="navbar">
        <h2>Sistem Pengaduan Sekolah</h2>
        <div class="auth-buttons">
            <a href="dashboard.php" class="auth-btn">Dashboard</a>
            <a href="../logout_admin.php" class="auth-btn">Logout</a>
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
                <span><?php echo $admin['nama_admin']; ?></span>

                <label>Email:</label>
                <span><?php echo $admin['email']; ?></span>
            </div>

            <button onclick="showEditForm()" class="btn-update">
                <i class="fas fa-edit"></i> Edit Profil
            </button>
        </div>

        <div id="editForm" class="edit-form">
            <h3>Edit Profil</h3>
            <form action="update_profil.php" method="POST">
                <div class="form-group">
                    <label>Nama Lengkap:</label>
                    <input type="text" name="nama_admin" value="<?php echo $admin['nama_admin']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="text" name="email" value="<?php echo $admin['email']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Password Baru (Opsional):</label>
                    <input type="password" name="password">
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru:</label>
                    <input type="password" name="konfirmasi_password">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-update">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <button type="button" onclick="hideEditForm()" class="btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
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
