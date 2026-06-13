<?php
session_start();
include 'koneksi.php';

// --- LOGIKA LOGIN ---
if (isset($_POST['btn_login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = md5($_POST['password']); 
    
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$user' AND password='$pass'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['login'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}

// --- LOGIKA LOGOUT ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Monokrom</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <style>
        body { background-color: #f4f4f4; color: #000; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .card { border: 1px solid #000; border-radius: 0; }
        .card-header { background-color: #000 !important; color: #fff !important; border-bottom: 1px solid #000; border-radius: 0; }
        
        /* Modifikasi Ukuran Font & Tombol agar Compact */
        .btn-custom-dark { background-color: #000; color: #fff; border: 1px solid #000; border-radius: 0; transition: all 0.3s; }
        .btn-custom-dark:hover { background-color: #fff; color: #000; }
        .btn-custom-outline { background-color: #fff; color: #000; border: 1px solid #000; border-radius: 0; transition: all 0.3s; }
        .btn-custom-outline:hover { background-color: #e2e2e2; }
        
        .modal-content { border-radius: 0; border: 2px solid #000; }
        .modal-header { background-color: #000; color: #fff; border-radius: 0; padding: 0.75rem 1rem; }
        .btn-close { filter: invert(1); }
        .signature-wrapper { position: relative; width: 100%; height: 150px; user-select: none; border: 2px dashed #000; background-color: #fff; }
        .signature-pad { position: absolute; left: 0; top: 0; width: 100%; height: 100%; }

        /* Custom Audio Style untuk memperkecil ukurannya */
        audio.audio-compact { height: 30px; max-width: 200px; outline: none; }

        /* Animasi */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animasi-masuk { animation: fadeSlideUp 0.6s ease-out forwards; }

        @keyframes pulseBtn {
            0% { box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.2); }
            70% { box-shadow: 0 0 0 6px rgba(0, 0, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 0, 0, 0); }
        }
        .animasi-denyut { animation: pulseBtn 2s infinite; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['login'])) : ?>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow animasi-masuk" style="width: 100%; max-width: 350px;">
            <div class="card-header text-center py-2">
                <h5 class="mb-0 fw-bold">LOGIN SISTEM</h5>
            </div>
            <div class="card-body p-3">
                <?php if(isset($error)) echo "<div class='alert alert-danger p-2 mb-3 rounded-0' style='font-size:0.9rem;'>$error</div>"; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1" style="font-size:0.9rem;">Username</label>
                        <input type="text" name="username" class="form-control form-control-sm rounded-0 border-dark" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1" style="font-size:0.9rem;">Password</label>
                        <input type="password" name="password" class="form-control form-control-sm rounded-0 border-dark" required>
                    </div>
                    <button type="submit" name="btn_login" class="btn btn-custom-dark btn-sm w-100 py-1 fw-bold">MASUK</button>
                </form>
            </div>
        </div>
    </div>

<?php else : ?>
    <div class="container mt-4 mb-4">
        
        <div class="d-flex justify-content-between align-items-center border-bottom border-dark pb-2 mb-3 animasi-masuk">
            <h4 class="fw-bold m-0">DATA DOKUMEN</h4>
            
            <div class="d-flex align-items-center gap-3">
                <audio id="bg-audio" controls autoplay loop class="audio-compact" title="Latar Audio">
                    <source src="musik.mp3" type="audio/mpeg">
                </audio>
                <a href="?logout=true" class="btn btn-custom-outline btn-sm fw-bold px-3">LOGOUT</a>
            </div>
        </div>

        <div class="card shadow-sm animasi-masuk">
            <div class="card-body p-3"> <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-custom-dark fw-bold animasi-denyut" data-bs-toggle="modal" data-bs-target="#crudModal">
                        + TAMBAH DATA
                    </button>
                </div>

                <div class="table-responsive">
                    <table id="tabel-dokumen" class="table table-sm table-bordered border-dark align-middle" style="width:100%; font-size: 0.9rem;">
                        <thead class="table-dark text-center">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Nama Dokumen</th>
                                <th style="width: 15%;">Lampiran</th>
                                <th style="width: 15%;">Status TTD</th>
                                <th style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT d.id, d.nama_dokumen, d.tanda_tangan, COUNT(l.id) as jml_lampiran 
                                      FROM dokumen d 
                                      LEFT JOIN lampiran l ON d.id = l.dokumen_id 
                                      GROUP BY d.id ORDER BY d.id DESC";
                            $result = mysqli_query($conn, $query);
                            $no = 1;
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                $status_ttd = (!empty($row['tanda_tangan'])) 
                                    ? '<span class="badge bg-dark border border-light w-100">Ditandatangani</span>' 
                                    : '<span class="badge bg-light text-dark border border-dark w-100">Belum TTD</span>';
                                
                                echo "<tr>
                                        <td class='text-center'>{$no}</td>
                                        <td>{$row['nama_dokumen']}</td>
                                        <td class='text-center'>{$row['jml_lampiran']} File</td>
                                        <td class='text-center'>{$status_ttd}</td>
                                        <td class='text-center'>
                                            <button type='button' class='btn btn-sm py-0 px-2 btn-custom-outline btn-edit' 
                                                    data-id='{$row['id']}' 
                                                    data-nama='{$row['nama_dokumen']}' 
                                                    data-bs-toggle='modal' 
                                                    data-bs-target='#editModal'>Edit</button>
                                            
                                            <a href='hapus.php?id={$row['id']}' 
                                               class='btn btn-sm py-0 px-2 btn-custom-dark' 
                                               onclick=\"return confirm('Yakin ingin menghapus dokumen ini beserta semua file lampirannya?');\">Hapus</a>
                                        </td>
                                      </tr>";
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="crudModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">FORM DOKUMEN BARU</h6>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="simpan.php" method="POST" enctype="multipart/form-data" id="form-dokumen">
                    <div class="modal-body p-3">
                        <div class="mb-2">
                            <label class="form-label fw-bold mb-1" style="font-size: 0.9rem;">Nama Dokumen</label>
                            <input type="text" name="nama_dokumen" class="form-control form-control-sm border-dark rounded-0" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold mb-1" style="font-size: 0.9rem;">Upload Lampiran</label>
                            <input class="form-control form-control-sm border-dark rounded-0" type="file" name="lampiran[]" multiple required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold mb-1" style="font-size: 0.9rem;">Tanda Tangan</label>
                            <div class="signature-wrapper">
                                <canvas id="signature-pad" class="signature-pad"></canvas>
                            </div>
                            <button type="button" class="btn btn-sm py-0 mt-1 btn-custom-outline fw-bold" style="font-size: 0.8rem;" id="clear-signature">Bersihkan Coretan</button>
                            <input type="hidden" name="signature_base64" id="signature_base64">
                        </div>
                    </div>
                    <div class="modal-footer p-2 border-top border-dark">
                        <button type="button" class="btn btn-sm btn-custom-outline fw-bold" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-sm btn-custom-dark fw-bold">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm"> <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">EDIT NAMA DOKUMEN</h6>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="edit.php" method="POST">
                    <div class="modal-body p-3">
                        <input type="hidden" name="id_dokumen" id="edit_id">
                        <div class="mb-2">
                            <label class="form-label fw-bold mb-1" style="font-size: 0.9rem;">Nama Dokumen</label>
                            <input type="text" name="nama_dokumen_edit" id="edit_nama" class="form-control form-control-sm border-dark rounded-0" required>
                        </div>
                    </div>
                    <div class="modal-footer p-2 border-top border-dark">
                        <button type="button" class="btn btn-sm btn-custom-outline fw-bold" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-sm btn-custom-dark fw-bold">UPDATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>
    $(document).ready(function() {
        if ($('#tabel-dokumen').length) {
            $('#tabel-dokumen').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'excelHtml5', className: 'btn btn-custom-outline btn-sm py-1 px-2 mb-2 fw-bold' },
                    { extend: 'pdfHtml5', className: 'btn btn-custom-dark btn-sm py-1 px-2 mb-2 fw-bold' },
                    { extend: 'print', className: 'btn btn-custom-outline btn-sm py-1 px-2 mb-2 fw-bold' }
                ]
            });
        }

        $('.btn-edit').on('click', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            $('#edit_id').val(id);
            $('#edit_nama').val(nama);
        });

        if (document.getElementById('signature-pad')) {
            var canvas = document.getElementById('signature-pad');
            var signaturePad;
            
            function resizeCanvas() {
                var ratio =  Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }

            $('#crudModal').on('shown.bs.modal', function () {
                resizeCanvas();
                if (!signaturePad) {
                    signaturePad = new SignaturePad(canvas, {
                        backgroundColor: 'rgb(255, 255, 255)',
                        penColor: 'rgb(0, 0, 0)'
                    });
                } else {
                    signaturePad.clear();
                }
            });

            $('#clear-signature').on('click', function() {
                if(signaturePad) signaturePad.clear();
            });

            $('#form-dokumen').on('submit', function(e) {
                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    alert("Tanda tangan wajib diisi!");
                } else {
                    $('#signature_base64').val(signaturePad.toDataURL());
                }
            });
        }
    });
</script>
</body>
</html>