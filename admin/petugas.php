<?php
require_once('cek_akses.php');
require_once('../config/database.php');

// Query untuk mengambil data petugas
$query = "SELECT * FROM petugas ORDER BY id_petugas DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Petugas</title>
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
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .btn-add {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            margin-right: 5px;
        }

        .btn-edit {
            background-color: #ffc107;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
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
        <a href="dashboard.php" style="text-decoration: none; color: #666; margin-bottom: 20px; display: block;">
            ← Kembali ke Dashboard
        </a>
        
        <h2>Data Petugas</h2>
        
        <button onclick="showAddModal()" class="btn-add">Tambah Petugas</button>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Petugas</th>
                    <th>Username</th>
                    <th>Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while($row = $result->fetch_assoc()): 
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['nama_petugas']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['level']); ?></td>
                    <td>
                        <button onclick="showEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)" 
                                class="btn btn-edit">Edit</button>
                        <button onclick="konfirmasiHapus(<?php echo $row['id_petugas']; ?>)" 
                                class="btn btn-delete">Hapus</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah/Edit Petugas -->
    <div id="petugasModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle">Tambah Petugas</h3>
            <form id="petugasForm" action="proses_petugas.php" method="POST">
                <input type="hidden" name="id_petugas" id="id_petugas">
                <input type="hidden" name="action" id="action" value="add">
                
                <div class="form-group">
                    <label>Nama Petugas:</label>
                    <input type="text" name="nama_petugas" id="nama_petugas" required>
                </div>
                
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" id="password">
                    <small style="color: #666;">Kosongkan jika tidak ingin mengubah password</small>
                </div>
                
                <div class="form-group">
                    <label>Level:</label>
                    <select name="level" id="level" required>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-add" style="width: 100%;">Simpan</button>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="modalKonfirmasi" class="modal">
        <div class="modal-content">
            <h4>Konfirmasi Hapus</h4>
            <p>Apakah Anda yakin ingin menghapus petugas ini?</p>
            <div style="text-align: right; margin-top: 20px;">
                <button onclick="hapusPetugas()" class="btn btn-delete">Hapus</button>
                <button onclick="closeKonfirmasi()" class="btn" style="background-color: #6c757d;">Batal</button>
            </div>
        </div>
    </div>

    <script>
        let petugasIdToDelete = null;

        function showAddModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Petugas';
            document.getElementById('action').value = 'add';
            document.getElementById('petugasForm').reset();
            document.getElementById('password').required = true;
            document.getElementById('petugasModal').style.display = 'block';
        }

        function showEditModal(data) {
            document.getElementById('modalTitle').textContent = 'Edit Petugas';
            document.getElementById('action').value = 'edit';
            document.getElementById('id_petugas').value = data.id_petugas;
            document.getElementById('nama_petugas').value = data.nama_petugas;
            document.getElementById('username').value = data.username;
            document.getElementById('level').value = data.level;
            document.getElementById('password').required = false;
            document.getElementById('petugasModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('petugasModal').style.display = 'none';
        }

        function konfirmasiHapus(id) {
            petugasIdToDelete = id;
            document.getElementById('modalKonfirmasi').style.display = 'block';
        }

        function closeKonfirmasi() {
            document.getElementById('modalKonfirmasi').style.display = 'none';
        }

        function hapusPetugas() {
            if (petugasIdToDelete) {
                window.location.href = 'proses_petugas.php?action=delete&id=' + petugasIdToDelete;
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html> 