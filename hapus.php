<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 1. Ambil nama file lampiran untuk dihapus dari folder fisik (NAMA TABEL SUDAH DIUPDATE)
    $query_file = mysqli_query($conn, "SELECT nama_file FROM lampiran_farel_2430511047 WHERE dokumen_id = '$id'");
    
    while ($row = mysqli_fetch_assoc($query_file)) {
        $file_path = "uploads/" . $row['nama_file'];
        // Jika file ada di dalam folder, hapus filenya
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // 2. Hapus relasi data dari database (NAMA TABEL SUDAH DIUPDATE)
    mysqli_query($conn, "DELETE FROM lampiran_farel_2430511047 WHERE dokumen_id = '$id'");
    mysqli_query($conn, "DELETE FROM dokumen_farel_2430511047 WHERE id = '$id'");

    header("Location: index.php");
    exit;
}
?>