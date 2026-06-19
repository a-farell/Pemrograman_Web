<?php
session_start();
include 'koneksi.php';

// Jika sudah login, langsung alihkan ke dashboard index.php
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

// --- LOGIKA LOGIN ---
if (isset($_POST['btn_login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = md5($_POST['password']); 
    
    $cek = mysqli_query($conn, "SELECT * FROM users_farel_2430511047 WHERE username='$user' AND password='$pass'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['login'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login - Sistem Informasi Monokrom</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

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
        .btn-custom-dark:hover { background-color: #495057; border-color: #495057; transform: translateY(-2px); color: #fff; }
        .form-control { border-radius: 6px; border: 1px solid #ced4da; }
        
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animasi-masuk { animation: fadeSlideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body>

    <div class="bg-animation">
        <ul class="shapes-container">
            <li class="shape"></li><li class="shape"></li><li class="shape"></li>
            <li class="shape"></li><li class="shape"></li>
        </ul>
    </div>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card animasi-masuk" style="width: 100%; max-width: 380px;">
            <div class="card-header text-center py-4">
                <i class="bi bi-shield-lock fs-1 d-block mb-2"></i>
                <h5 class="mb-0 fw-bold tracking-wide">SECURE LOGIN</h5>
            </div>
            <div class="card-body p-4">
                <?php if(isset($error)) echo "<div class='alert alert-danger py-2 px-3 mb-4 rounded-2' style='font-size:0.9rem;'><i class='bi bi-exclamation-triangle-fill me-2'></i>$error</div>"; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted" style="font-size:0.85rem;"><i class="bi bi-person me-1"></i> Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted" style="font-size:0.85rem;"><i class="bi bi-key me-1"></i> Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" name="btn_login" class="btn btn-custom-dark w-100 py-2 fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i>MASUK SISTEM</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>