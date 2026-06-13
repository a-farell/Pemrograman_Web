<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 1. Cari file lampiran yang terkait untuk dihapus dari folder
    $query_file = mysqli_query($conn, "SELECT nama_file FROM lampiran WHERE dokumen_id='$id'");
    while ($row = mysqli_fetch_assoc($query_file)) {
        $path_file = "uploads/" . $row['nama_file'];
        if (file_exists($path_file)) {
            unlink($path_file); // Perintah untuk menghapus file fisik
        }
    }
    
    // 2. Hapus data dari database (lampiran akan terhapus otomatis jika pakai CASCADE, tapi kita pastikan saja)
    mysqli_query($conn, "DELETE FROM lampiran WHERE dokumen_id='$id'");
    mysqli_query($conn, "DELETE FROM dokumen WHERE id='$id'");
    
    echo "<script>alert('Data dan file lampiran berhasil dihapus!'); window.location='index.php';</script>";
}
?>