<?php
session_start();
include 'koneksi.php';

// Proteksi Halaman: Jika belum login, tendang ke login.php
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// --- LOGIKA LOGOUT ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Informasi Monokrom</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <style>
        body { color: #212529; font-family: 'Segoe UI', sans-serif; min-height: 100vh; margin: 0; background-color: transparent; }
        
        /* ANIMASI BACKGROUND CSS */
        .bg-animation {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1; 
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%); overflow: hidden;
        }
        .shapes-container { position: absolute; top: 0; left: 0; width: 100%; height: 100%; margin: 0; padding: 0; }
        .shape {
            position: absolute; display: block; list-style: none; width: 20px; height: 20px;
            background: rgba(255, 255, 255, 0.4); animation: floatUp 25s linear infinite; bottom: -150px;
            border-radius: 12px; backdrop-filter: blur(5px); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .shape:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .shape:nth-child(2) { left: 10%; width: 30px; height: 30px; animation-delay: 2s; animation-duration: 12s; }
        .shape:nth-child(3) { left: 70%; width: 40px; height: 40px; animation-delay: 4s; }
        .shape:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .shape:nth-child(5) { left: 65%; width: 35px; height: 35px; animation-delay: 0s; }
        
        @keyframes floatUp {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 12px; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }
        
        .card { border: none; border-radius: 12px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        .card-header { background: linear-gradient(90deg, #1a1a1a 0%, #333333 100%) !important; color: #fff !important; border-bottom: none; padding: 1rem 1.5rem; }
        .btn-custom-dark { background-color: #212529; color: #fff; border: 1px solid #212529; border-radius: 6px; transition: all 0.2s; }
        .btn-custom-dark:hover { background-color: #495057; border-color: #495057; transform: translateY(-2px); color: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
        .btn-custom-outline { background-color: #fff; color: #212529; border: 1px solid #ced4da; border-radius: 6px; transition: all 0.2s; }
        .btn-custom-outline:hover { background-color: #f8f9fa; border-color: #212529; }
        .form-control { border-radius: 6px; border: 1px solid #ced4da; }
        .modal-content { border-radius: 12px; border: none; box-shadow: 0 15px 40px rgba(0,0,0,0.2); }
        .modal-header { background-color: #212529; color: #fff; border-bottom: none; }
        .btn-close { filter: invert(1); opacity: 0.8; }
        .signature-wrapper { position: relative; width: 100%; height: 160px; user-select: none; border: 2px dashed #adb5bd; border-radius: 8px; background-color: #f8f9fa; }
        .signature-pad { position: absolute; left: 0; top: 0; width: 100%; height: 100%; cursor: crosshair; }
        .top-navbar { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); box-shadow: 0 2px 15px rgba(0,0,0,0.05); padding: 15px 0; margin-bottom: 30px; }
        audio.audio-compact { height: 35px; max-width: 220px; outline: none; border-radius: 20px; background: #f1f3f5; }
        
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animasi-masuk { animation: fadeSlideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes pulseBtn { 0% { box-shadow: 0 0 0 0 rgba(33, 37, 41, 0.4); } 70% { box-shadow: 0 0 0 8px rgba(33, 37, 41, 0); } 100% { box-shadow: 0 0 0 0 rgba(33, 37, 41, 0); } }
        .animasi-denyut { animation: pulseBtn 2s infinite; }
    </style>
</head>
<body>

    <div class="bg-animation">
        <ul class="shapes-container">
            <li class="shape"></li><li class="shape"></li><li class="shape"></li>
            <li class="shape"></li><li class="shape"></li>
        </ul>
    </div>

    <nav class="top-navbar animasi-masuk">
        <div class="container d-flex justify-content-between align-items-center">
            <h4 class="fw-bold m-0 text-dark"><i class="bi bi-journal-check me-2"></i>Sistem Dokumen</h4>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1 border shadow-sm">
                    <i class="bi bi-music-note-list text-muted me-2 ms-2"></i>
                    <audio id="bg-audio" controls autoplay loop class="audio-compact" title="Latar Audio">
                        <source src="musik.mp3" type="audio/mpeg">
                    </audio>
                </div>
                <a href="?logout=true" class="btn btn-custom-outline btn-sm fw-bold px-3 py-2"><i class="bi bi-power text-danger me-1"></i> LOGOUT</a>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="card animasi-masuk" style="animation-delay: 0.1s;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold"><i class="bi bi-table me-2"></i>Tabel Arsip Dokumen</h6>
            </div>
            <div class="card-body p-4"> 
                <div class="mb-4">
                    <button type="button" class="btn btn-custom-dark fw-bold animasi-denyut px-4" data-bs-toggle="modal" data-bs-target="#crudModal">
                        <i class="bi bi-plus-circle me-2"></i>TAMBAH DATA BARU
                    </button>
                </div>

                <div class="table-responsive mt-2">
                    <table id="tabel-dokumen" class="table table-hover table-bordered align-middle" style="width:100%; font-size: 0.95rem;">
                        <thead class="text-center">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th><i class="bi bi-file-earmark-text me-1"></i> Nama Dokumen</th>
                                <th style="width: 20%;"><i class="bi bi-paperclip me-1"></i> Lampiran & Pratinjau</th>
                                <th style="width: 15%;"><i class="bi bi-pen me-1"></i> Status TTD</th>
                                <th style="width: 15%;"><i class="bi bi-gear me-1"></i> Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query diupdate untuk fitur Pratinjau
                            $query = "SELECT d.id, d.nama_dokumen, d.tanda_tangan, 
                                      COUNT(l.id) as jml_lampiran,
                                      GROUP_CONCAT(l.nama_file SEPARATOR '||') as daftar_file
                                      FROM dokumen_farel_2430511047 d 
                                      LEFT JOIN lampiran_farel_2430511047 l ON d.id = l.dokumen_id 
                                      GROUP BY d.id ORDER BY d.id DESC";
                            $result = mysqli_query($conn, $query);
                            $no = 1;
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                $status_ttd = (!empty($row['tanda_tangan'])) 
                                    ? '<span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 w-100"><i class="bi bi-check-circle me-1"></i> Selesai</span>' 
                                    : '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 w-100"><i class="bi bi-clock me-1"></i> Menunggu</span>';
                                
                                // LOGIKA PRATINJAU FILE
                                $badge_lampiran = "<span class='badge bg-light text-dark border shadow-sm mb-1 d-block'><i class='bi bi-files'></i> {$row['jml_lampiran']} File</span>";
                                $link_file = "";
                                
                                if (!empty($row['daftar_file'])) {
                                    $files = explode('||', $row['daftar_file']);
                                    foreach ($files as $index => $file) {
                                        $urutan = $index + 1;
                                        $link_file .= "<a href='uploads/{$file}' target='_blank' class='btn btn-sm btn-outline-dark py-0 px-1 mb-1 me-1' style='font-size: 0.75rem;' title='Lihat File {$urutan}'><i class='bi bi-eye'></i> Buka {$urutan}</a>";
                                    }
                                }
                                
                                echo "<tr>
                                        <td class='text-center fw-bold text-muted'>{$no}</td>
                                        <td class='fw-medium'>{$row['nama_dokumen']}</td>
                                        <td class='text-center'>
                                            {$badge_lampiran}
                                            <div class='mt-1'>{$link_file}</div>
                                        </td>
                                        <td class='text-center'>{$status_ttd}</td>
                                        <td class='text-center'>
                                            <div class='btn-group shadow-sm'>
                                                <button type='button' class='btn btn-sm btn-custom-outline btn-edit px-2' 
                                                        data-id='{$row['id']}' data-nama='{$row['nama_dokumen']}' 
                                                        data-bs-toggle='modal' data-bs-target='#editModal'>
                                                    <i class='bi bi-pencil-square'></i>
                                                </button>
                                                <a href='hapus.php?id={$row['id']}' class='btn btn-sm btn-danger px-2' 
                                                   onclick=\"return confirm('Yakin ingin menghapus dokumen ini beserta semua file lampirannya?');\">
                                                    <i class='bi bi-trash3'></i>
                                                </a>
                                            </div>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-cloud-arrow-up me-2"></i>UPLOAD DOKUMEN BARU</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="simpan.php" method="POST" enctype="multipart/form-data" id="form-dokumen">
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Nama Dokumen</label>
                            <input type="text" name="nama_dokumen" class="form-control form-control-lg" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Upload Lampiran</label>
                            <input class="form-control" type="file" name="lampiran[]" multiple required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold text-muted">Tanda Tangan Digital</label>
                            <div class="signature-wrapper shadow-sm"><canvas id="signature-pad" class="signature-pad"></canvas></div>
                            <button type="button" class="btn btn-sm mt-2 btn-warning fw-bold text-dark shadow-sm" id="clear-signature"><i class="bi bi-eraser me-1"></i>Bersihkan Coretan</button>
                            <input type="hidden" name="signature_base64" id="signature_base64">
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-custom-outline fw-bold" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-custom-dark fw-bold"><i class="bi bi-save me-1"></i> SIMPAN DATA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>EDIT NAMA DOKUMEN</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="edit.php" method="POST">
                    <div class="modal-body p-4">
                        <input type="hidden" name="id_dokumen" id="edit_id">
                        <div class="mb-2">
                            <label class="form-label fw-bold text-muted">Nama Dokumen Baru</label>
                            <input type="text" name="nama_dokumen_edit" id="edit_nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-custom-outline fw-bold" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-custom-dark fw-bold"><i class="bi bi-check2-circle me-1"></i> UPDATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rtip',
                buttons: [
                    { extend: 'excelHtml5', text: '<i class="bi bi-file-earmark-excel"></i> Excel', className: 'btn btn-success btn-sm mb-3 shadow-sm' },
                    { extend: 'pdfHtml5', text: '<i class="bi bi-file-earmark-pdf"></i> PDF', className: 'btn btn-danger btn-sm mb-3 shadow-sm ms-1' },
                    { extend: 'print', text: '<i class="bi bi-printer"></i> Print', className: 'btn btn-secondary btn-sm mb-3 shadow-sm ms-1' }
                ]
            });
        }
        $('.btn-edit').on('click', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_nama').val($(this).data('nama'));
        });
        if (document.getElementById('signature-pad')) {
            var canvas = document.getElementById('signature-pad');
            var signaturePad;
            function resizeCanvas() {
                var ratio =  Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio; canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }
            $('#crudModal').on('shown.bs.modal', function () {
                resizeCanvas();
                if (!signaturePad) { signaturePad = new SignaturePad(canvas, { backgroundColor: 'rgb(248, 249, 250)', penColor: 'rgb(33, 37, 41)' }); } 
                else { signaturePad.clear(); }
            });
            $('#clear-signature').on('click', function() { if(signaturePad) signaturePad.clear(); });
            $('#form-dokumen').on('submit', function(e) {
                if (signaturePad.isEmpty()) { e.preventDefault(); alert("Tanda tangan wajib diisi!"); } 
                else { $('#signature_base64').val(signaturePad.toDataURL()); }
            });
        }
    });
</script>
</body>
</html>