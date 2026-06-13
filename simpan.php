<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_dokumen = mysqli_real_escape_string($conn, $_POST['nama_dokumen']);
    $signature    = $_POST['signature_base64'];

    // 1. Simpan ke tabel dokumen
    $query_dok = "INSERT INTO dokumen (nama_dokumen, tanda_tangan) VALUES ('$nama_dokumen', '$signature')";
    
    if (mysqli_query($conn, $query_dok)) {
        // Ambil ID dokumen yang baru saja disimpan
        $dokumen_id = mysqli_insert_id($conn);

        // 2. Proses Multiple File Upload
        $jumlah_file = count($_FILES['lampiran']['name']);
        for ($i = 0; $i < $jumlah_file; $i++) {
            $nama_file = $_FILES['lampiran']['name'][$i];
            $tmp_name  = $_FILES['lampiran']['tmp_name'][$i];
            
            if ($nama_file != "") {
                // Rename file agar unik
                $file_baru = time() . "_" . $nama_file;
                $folder_tujuan = "uploads/" . $file_baru;
                
                if (move_uploaded_file($tmp_name, $folder_tujuan)) {
                    // Simpan nama file ke tabel lampiran
                    mysqli_query($conn, "INSERT INTO lampiran (dokumen_id, nama_file) VALUES ('$dokumen_id', '$file_baru')");
                }
            }
        }
        
        echo "<script>alert('Data berhasil disimpan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data!'); window.location='index.php';</script>";
    }
}
?>