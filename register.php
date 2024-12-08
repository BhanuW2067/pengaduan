<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }
        .register-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        .register-container h2 {
            text-align: center;
        }
        .register-container input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .register-container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .register-container button:hover {
            background-color: #45a049;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background-color: #e8f5e9;
            border-radius: 5px;
        }
        .login-link p {
            margin: 5px 0;
            color: #333;
        }
        .login-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .divider {
            margin: 20px 0;
            text-align: center;
            position: relative;
        }
        .divider:before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            border-top: 1px solid #ddd;
            z-index: 1;
        }
        .divider span {
            background-color: #fff;
            padding: 0 15px;
            color: #666;
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        
        <!-- Login Link Section -->
        <div class="login-link">
            <p><strong>Sudah memiliki akun?</strong></p>
            <p>Silakan login untuk mengakses layanan pengaduan</p>
            <a href="login.php">Login Sekarang</a>
        </div>

        <div class="divider">
            <span>atau</span>
        </div>

        <!-- Form Register -->
        <form action="proses_register.php" method="POST">
            <input type="text" name="nik" placeholder="NIK" required>
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="telp" placeholder="No. Telepon" required>
            <button type="submit">Register</button>
        </form>

        <?php if(isset($_GET['error'])): ?>
            <div class="error-message">
                <?php echo $_GET['error']; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Menghilangkan pesan error setelah 3 detik
        setTimeout(function() {
            var errorMessage = document.querySelector('.error-message');
            if(errorMessage) {
                errorMessage.style.display = 'none';
            }
        }, 3000);
    </script>
</body>
</html>
