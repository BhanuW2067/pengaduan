
<?php
session_start();
require_once('../config/database.php');

if(!isset($_SESSION['id_admin'])) {
    header("Location: ../login_admin.php");
    exit();
}

$tgl_awal = $_GET['tgl_awal'] ?? '';
$tgl_akhir = $_GET['tgl_akhir'] ?? '';
$status = $_GET['status'] ?? '';

$query = "SELECT p.*, u.nama as nama_siswa, t.tanggapan, t.tgl_tanggapan, a.nama_admin
          FROM pengaduan p 
          LEFT JOIN user u ON p.id_user = u.id_user
          LEFT JOIN tanggapan t ON p.id_pengaduan = t.id_pengaduan
          LEFT JOIN admin a ON t.id_admin = a.id_admin
          WHERE 1=1";

// Tambahkan filter jika ada
if ($tgl_awal && $tgl_akhir) {
    $query .= " AND p.tgl_pengaduan BETWEEN '$tgl_awal' AND '$tgl_akhir'";
}
if ($status !== '') {
    $query .= " AND p.status = '$status'";
}
$query .= " ORDER BY p.tgl_pengaduan DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengaduan</title>
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
            background-color: #1230AE;
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
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .filter-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-group {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="date"], select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 200px;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
        }

        .btn-filter {
            background-color: #4CAF50;
            color: white;
        }

        .btn-reset {
            background-color: #6c757d;
            color: white;
        }

        .btn-print {
            background-color: #28a745;
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-menunggu { background-color: #ffc107; color: #000; }
        .status-proses { background-color: #17a2b8; color: #fff; }
        .status-selesai { background-color: #28a745; color: #fff; }

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

        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
                background-color: white;
            }
            .container {
                box-shadow: none;
                padding: 0;
            }
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
        <h2>Laporan Pengaduan</h2>

        <div class="filter-form no-print">
            <form method="GET" action="">
                <div class="form-group">
                    <label>Tanggal Awal</label>
                    <input type="date" name="tgl_awal" value="<?php echo $tgl_awal; ?>">
                </div>
                <div class="form-group">
                    <label>Tanggal Akhir</label>
                    <input type="date" name="tgl_akhir" value="<?php echo $tgl_akhir; ?>">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Semua Status</option>
                        <option value="diterima" <?php echo $status === 'diterima' ? 'selected' : ''; ?>>Menunggu</option>
                        <option value="proses" <?php echo $status === 'proses' ? 'selected' : ''; ?>>Diproses</option>
                        <option value="selesai" <?php echo $status === 'selesai' ? 'selected' : ''; ?>>Ditanggapi</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-filter">Filter</button>
                <a href="laporan.php" class="btn btn-reset">Reset</a>
                <a href="print_laporan.php" class="btn btn-print" target="_blank">Print</a>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Siswa</th>
                    <th>Isi Pengaduan</th>
                    <th>Status</th>
                    <th>Tanggapan</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while($row = $result->fetch_assoc()): 
                    $status_class = '';
                    $status_text = '';
                    switch($row['status']) {
                        case 'diterima':
                            $status_class = 'status-menunggu';
                            $status_text = 'Menunggu';
                            break;
                        case 'proses':
                            $status_class = 'status-proses';
                            $status_text = 'Diproses';
                            break;
                        case 'selesai':
                            $status_class = 'status-selesai';
                            $status_text = 'Ditanggapi';
                            break;
                    }
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['tgl_pengaduan'])); ?></td>
                        <td><?php echo $row['nama_siswa']; ?></td>
                        <td><?php echo $row['isi_laporan']; ?></td>
                        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                        <td><?php echo $row['tanggapan'] ? $row['tanggapan'] : '-'; ?></td>
                        <td><?php echo $row['nama_admin'] ?? '-'; ?></td>
                    </tr>
                <?php endwhile; ?>
                <?php if($result->num_rows == 0): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html> 
