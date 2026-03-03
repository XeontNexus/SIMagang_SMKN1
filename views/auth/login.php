<?php
session_start();
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/models/User.php';
require_once __DIR__ . '/../../backend/utils/helpers.php';

// Check if user is already logged in
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    $role = $_SESSION['user_role'] ?? '';
    if (!empty($role)) {
        switch ($role) {
            case 'siswa':
                header('Location: ../siswa/dashboard.php');
                exit;
            case 'guru':
                header('Location: ../guru/dashboard.php');
                exit;
            case 'dudi':
                header('Location: ../dudi/dashboard.php');
                exit;
            case 'admin':
                header('Location: ../admin/dashboard.php');
                exit;
        }
    }
}

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi';
    } elseif (!validateEmail($email)) {
        $error = 'Format email tidak valid';
    } else {
        try {
            $database = new Database();
            $db = $database->getConnection();
            
            if (!$db) {
                $error = 'Koneksi database gagal. Silakan kontak administrator.';
            } else {
                $user = new User($db);
                $result = $user->login($email, $password);
                
                if ($result) {
                    // Set session variables
                    $_SESSION['user_id'] = $result['id'];
                    $_SESSION['user_email'] = $result['email'];
                    $_SESSION['user_role'] = $result['role'];
                    $_SESSION['login_time'] = time();

                    // Redirect to appropriate dashboard
                    switch ($result['role']) {
                        case 'siswa':
                            header('Location: ../siswa/dashboard.php');
                            exit;
                        case 'guru':
                            header('Location: ../guru/dashboard.php');
                            exit;
                        case 'dudi':
                            header('Location: ../dudi/dashboard.php');
                            exit;
                        case 'admin':
                            header('Location: ../admin/dashboard.php');
                            exit;
                    }
                } else {
                    $error = 'Email atau password salah';
                }
            }
        } catch (Exception $e) {
            $error = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMagang</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2><i class="bi bi-mortarboard-fill"></i> SIMagang</h2>
                <p>Sistem Informasi Magang SMK</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" onsubmit="return SIMagang.validateForm('loginForm')">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input 
                            type="email" 
                            class="form-control" 
                            id="email" 
                            name="email" 
                            placeholder="Masukkan email"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan password"
                            required
                        >
                    </div>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center">
                    <div>
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat saya</label>
                    </div>
                    <a href="forgot_password.php" class="text-primary">Lupa password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </form>

            <div class="text-center mt-4">
                <p>Belum punya akun? <a href="register.php" class="text-primary">Daftar sekarang</a></p>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted">© 2024 SIMagang. All rights reserved.</small>
            </div>
        </div>
    </div>

    <script src="../../assets/js/script.js"></script>
</body>
</html>
