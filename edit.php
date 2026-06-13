<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_dokumen'];
    $nama_dokumen = mysqli_real_escape_string($conn, $_POST['nama_dokumen_edit']);
    
    // Update nama dokumen di database
    $query = "UPDATE dokumen SET nama_dokumen='$nama_dokumen' WHERE id='$id'";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Nama Dokumen berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!'); window.location='index.php';</script>";
    }
}
?>