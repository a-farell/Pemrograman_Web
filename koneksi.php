<?php
$host = "localhost";
$user = "root";       // Sesuaikan dengan user XAMPP/Laragon kamu
$pass = "";           // Sesuaikan password database kamu
$db   = "db_dokumen_monokrom";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>