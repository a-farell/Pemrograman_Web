<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_dokumen = mysqli_real_escape_string($conn, $_POST['nama_dokumen']);
    $signature_base64 = $_POST['signature_base64'];

    // NAMA TABEL SUDAH DIUPDATE
    $query_dokumen = "INSERT INTO dokumen_farel_2430511047 (nama_dokumen, tanda_tangan) VALUES ('$nama_dokumen', '$signature_base64')";
    mysqli_query($conn, $query_dokumen);
    
    // Mendapatkan ID dokumen yang baru saja dibuat
    $dokumen_id = mysqli_insert_id($conn);

    // Proses Upload File Lampiran
    $target_dir = "uploads/";
    // Buat folder jika belum ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!empty($_FILES['lampiran']['name'][0])) {
        $total_files = count($_FILES['lampiran']['name']);
        
        for ($i = 0; $i < $total_files; $i++) {
            $nama_file_asli = $_FILES['lampiran']['name'][$i];
            $tmp_name = $_FILES['lampiran']['tmp_name'][$i];
            
            // Generate nama file unik agar tidak tertimpa
            $nama_file_baru = time() . "_" . rand(1000, 9999) . "_" . str_replace(" ", "_", $nama_file_asli);
            $target_file = $target_dir . $nama_file_baru;

            if (move_uploaded_file($tmp_name, $target_file)) {
                // NAMA TABEL SUDAH DIUPDATE
                $query_file = "INSERT INTO lampiran_farel_2430511047 (dokumen_id, nama_file) VALUES ('$dokumen_id', '$nama_file_baru')";
                mysqli_query($conn, $query_file);
            }
        }
    }

    header("Location: index.php");
    exit;
}
?>