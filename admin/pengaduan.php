<?php
require_once('cek_akses.php');
require_once('../config/database.php');

// Query untuk mengambil data pengaduan dan tanggapan
$query = "SELECT p.*, ps.nama, t.tanggapan, t.tgl_tanggapan, t.foto_tanggapan, pt.nama_petugas 
          FROM pengaduan p 
          LEFT JOIN pengaduan_siswa ps ON p.nik = ps.nik
          LEFT JOIN tanggapan t ON p.id_pengaduan = t.id_pengaduan
          LEFT JOIN petugas pt ON t.id_petugas = pt.id_petugas
          ORDER BY p.tgl_pengaduan DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengaduan</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
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

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            color: white;
            display: inline-block;
        }

        .status-0 { background-color: #ffc107; }
        .status-proses { background-color: #17a2b8; }
        .status-selesai { background-color: #28a745; }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
        }

        .btn-primary { background-color: #007bff; }
        .btn-success { background-color: #28a745; }
        .btn-danger { background-color: #dc3545; }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .foto-pengaduan {
            max-width: 100px;
            height: auto;
            cursor: pointer;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
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

        .close {
            position: absolute;
            right: 25px;
            top: 10px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-height: 100px;
        }

        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-container {
            text-align: right;
            margin-top: 15px;
        }

        .badge-selesai {
            display: inline-block;
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border-radius: 4px;
            font-size: 12px;
        }

        .status-badge.status-selesai {
            background-color: #28a745;
        }

        .btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        /* Style untuk modal konfirmasi */
        .modal-confirm {
            text-align: center;
            padding: 20px;
        }

        .modal-confirm h4 {
            margin-bottom: 20px;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-container .btn {
            min-width: 100px;
        }

        .export-buttons {
            margin-bottom: 20px;
        }
        
        .export-buttons .btn {
            margin-right: 10px;
            text-decoration: none;
            padding: 8px 15px;
        }
        
        .btn-success {
            background-color: #28a745;
        }
        
        .btn-danger {
            background-color: #dc3545;
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
        <h2>Data Pengaduan</h2>

        <div class="export-buttons">
            <a href="export_excel.php" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="export_print.php" target="_blank" class="btn btn-danger">
                <i class="fas fa-print"></i> Print PDF
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Isi Laporan</th>
                    <th>Foto</th>
                    <th>Status</th>
                    <th>Tanggapan</th>
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
                    <td><?php echo date('d/m/Y', strtotime($row['tgl_pengaduan'])); ?></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['isi_laporan']); ?></td>
                    <td>
                        <?php if($row['foto']): ?>
                            <img src="../assets/fotolaporan/<?php echo $row['foto']; ?>" 
                                 class="foto-pengaduan" 
                                 onclick="showImage(this.src)" 
                                 alt="Foto Pengaduan">
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="status-badge status-<?php echo $row['status']; ?>">
                            <?php echo $row['status'] === '0' ? 'Menunggu' : ($row['status'] === 'proses' ? 'Diproses' : 'Selesai'); ?>
                        </span>
                    </td>
                    <td>
                        <?php if($row['tanggapan']): ?>
                            <div class="tanggapan-content">
                                <strong>Ditanggapi oleh: <?php echo htmlspecialchars($row['nama_petugas']); ?></strong><br>
                                <?php echo nl2br(htmlspecialchars($row['tanggapan'])); ?><br>
                                <small>Tanggal: <?php echo date('d/m/Y', strtotime($row['tgl_tanggapan'])); ?></small>
                            </div>
                        <?php else: ?>
                            <em>Belum ditanggapi</em>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button onclick="showTanggapanForm(<?php echo htmlspecialchars(json_encode(['id_pengaduan' => $row['id_pengaduan'], 'tanggapan' => $row['tanggapan'] ?? '', 'status' => $row['status']])); ?>)" class="btn btn-primary">
                            <?php echo $row['tanggapan'] ? 'Edit Tanggapan' : 'Beri Tanggapan'; ?>
                        </button>
                        <button onclick="konfirmasiHapus(<?php echo $row['id_pengaduan']; ?>)" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal untuk preview gambar -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <!-- Modal untuk form tanggapan -->
    <div id="tanggapanModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeTanggapanModal()">&times;</span>
            <h3>Form Tanggapan</h3>
            <form id="tanggapanForm" action="proses_tanggapan.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_pengaduan" id="id_pengaduan">
                <div class="form-group">
                    <label>Tanggapan:</label>
                    <textarea name="tanggapan" id="tanggapan" required></textarea>
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status" id="status" required>
                        <option value="proses">Proses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Upload Bukti:</label>
                    <input type="file" name="foto_tanggapan" accept="image/*">
                </div>
                
                <div class="btn-container">
                    <button type="submit" class="btn btn-success">Simpan Tanggapan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="modalKonfirmasi" class="modal">
        <div class="modal-content modal-confirm">
            <h4>Konfirmasi Hapus</h4>
            <p>Apakah Anda yakin ingin menghapus pengaduan ini?</p>
            <div class="btn-container">
                <button onclick="hapusPengaduan()" class="btn btn-danger">Hapus</button>
                <button onclick="tutupModalKonfirmasi()" class="btn btn-secondary">Batal</button>
            </div>
        </div>
    </div>

    <script>
        function showImage(src) {
            document.getElementById("modalImage").src = src;
            document.getElementById("imageModal").style.display = "block";
        }

        function showTanggapanForm(data) {
            document.getElementById('id_pengaduan').value = data.id_pengaduan;
            document.getElementById('tanggapan').value = data.tanggapan;
            document.getElementById('status').value = data.status === '0' ? 'proses' : data.status;
            document.getElementById('tanggapanModal').style.display = "block";
        }

        function closeTanggapanModal() {
            document.getElementById('tanggapanModal').style.display = "none";
        }

        let pengaduanIdToDelete = null;

        function konfirmasiHapus(id) {
            pengaduanIdToDelete = id;
            document.getElementById('modalKonfirmasi').style.display = 'block';
        }

        function tutupModalKonfirmasi() {
            document.getElementById('modalKonfirmasi').style.display = 'none';
        }

        function hapusPengaduan() {
            if (pengaduanIdToDelete) {
                window.location.href = 'hapus_pengaduan.php?id=' + pengaduanIdToDelete;
            }
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById("imageModal")) {
                document.getElementById("imageModal").style.display = "none";
            }
            if (event.target == document.getElementById("tanggapanModal")) {
                closeTanggapanModal();
            }
            if (event.target == document.getElementById("modalKonfirmasi")) {
                tutupModalKonfirmasi();
            }
        }
    </script>
</body>
</html>