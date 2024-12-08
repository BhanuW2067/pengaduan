<?php
// Konfigurasi database
$conn = mysqli_connect('localhost', 'root', '', 'db_pengaduan');

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $telp = $_POST['telp'];

    // Hash password
    $password = md5($password);

    // Query insert
    $query = "INSERT INTO pengaduan_siswa (nik, nama, username, password, telp) 
              VALUES ('$nik', '$nama', '$username', '$password', '$telp')";

    // Eksekusi query
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Registrasi berhasil!');
                window.location.href='login.php';
              </script>";
    } else {
        echo "<script>
                alert('Registrasi gagal! " . mysqli_error($conn) . "');
                window.location.href='register.php';
              </script>";
    }
}

mysqli_close($conn);
?>
