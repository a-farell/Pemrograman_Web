<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id_dokumen']);
    $nama_dokumen = mysqli_real_escape_string($conn, $_POST['nama_dokumen_edit']);

    // NAMA TABEL SUDAH DIUPDATE
    $query = "UPDATE dokumen_farel_2430511047 SET nama_dokumen = '$nama_dokumen' WHERE id = '$id'";
    mysqli_query($conn, $query);

    header("Location: index.php");
    exit;
}
?>