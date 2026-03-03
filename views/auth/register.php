<?php
session_start();
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/models/User.php';
require_once __DIR__ . '/../../backend/utils/helpers.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../siswa/dashboard.php');
    exit;
}

$error = '';
$success = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = sanitizeInput($_POST['role'] ?? '');

    // Validation
    if (empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $error = 'Semua field harus diisi';
    } elseif (!validateEmail($email)) {
        $error = 'Format email tidak valid';
    } elseif ($password !== $confirm_password) {
        $error = 'Password tidak cocok';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    } else {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        // Check if email already exists
        $existing_user = $user->getByEmail($email);
        if ($existing_user) {
            $error = 'Email sudah terdaftar';
        } else {
            // Create new user
            $user_data = [
                'email' => $email,
                'password' => $password,
                'role' => $role
            ];

            $user_id = $user->create($user_data);
            
            if ($user_id) {
                $success = 'Registrasi berhasil! Silakan login.';
                
                // Redirect to login after 2 seconds
                header('refresh:2;url=login.php');
            } else {
                $error = 'Registrasi gagal. Silakan coba lagi.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIMagang</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2><i class="bi bi-mortarboard-fill"></i> SIMagang</h2>
                <p>Buat Akun Baru</p>
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

            <form method="POST" action="register.php" onsubmit="return SIMagang.validateForm('registerForm')">
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
                    <label for="role" class="form-label">Daftar Sebagai</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="siswa" <?php echo ($_POST['role'] ?? '') === 'siswa' ? 'selected' : ''; ?>>Siswa</option>
                            <option value="guru" <?php echo ($_POST['role'] ?? '') === 'guru' ? 'selected' : ''; ?>>Guru Pembimbing</option>
                            <option value="dudi" <?php echo ($_POST['role'] ?? '') === 'dudi' ? 'selected' : ''; ?>>Dudi Mitra</option>
                        </select>
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
                            placeholder="Masukkan password (minimal 6 karakter)"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="confirm_password" 
                            name="confirm_password" 
                            placeholder="Konfirmasi password"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">Saya setuju dengan <a href="#" class="text-primary">syarat dan ketentuan</a></label>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-person-plus"></i> Daftar
                </button>
            </form>

            <div class="text-center mt-4">
                <p>Sudah punya akun? <a href="login.php" class="text-primary">Login di sini</a></p>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted">© 2024 SIMagang. All rights reserved.</small>
            </div>
        </div>
    </div>

    <script src="../../assets/js/script.js"></script>
</body>
</html>
