<?php
// Konfigurasi database
$host = 'localhost';      // Host database
$username = 'root';       // Username database
$password = '';          // Password database (kosong untuk XAMPP default)
$database = 'db_pengaduan'; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set karakter encoding
$conn->set_charset("utf8");

// Aktifkan error reporting untuk debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Fungsi untuk mencegah SQL injection (opsional, karena sudah menggunakan prepared statement)
function escape($string) {
    global $conn;
    return mysqli_real_escape_string($conn, $string);
}

// Set timezone (sesuaikan dengan timezone yang diinginkan)
date_default_timezone_set('Asia/Jakarta');
?>
