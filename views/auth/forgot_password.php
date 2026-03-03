<?php
session_start();
require_once '../../backend/config/database.php';
require_once '../../backend/models/User.php';
require_once '../../backend/utils/helpers.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../siswa/dashboard.php');
    exit;
}

$error = '';
$success = '';

// Handle forgot password form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');

    if (empty($email)) {
        $error = 'Email harus diisi';
    } elseif (!validateEmail($email)) {
        $error = 'Format email tidak valid';
    } else {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $user_data = $user->getByEmail($email);
        
        if ($user_data) {
            // Generate reset token
            $token = generateToken();
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Save token to database (you'll need to create password_resets table)
            // For now, just show success message
            
            $reset_link = "http://localhost/SIMagang2/views/auth/reset_password.php?token=" . $token;
            
            // In production, send email here
            // sendEmail($email, 'Password Reset - SIMagang', $reset_link);
            
            $success = 'Link reset password telah dikirim ke email Anda. Silakan periksa inbox Anda.';
        } else {
            // Don't reveal if email exists or not for security
            $success = 'Jika email terdaftar, link reset password akan dikirim ke email Anda.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SIMagang</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2><i class="bi bi-mortarboard-fill"></i> SIMagang</h2>
                <p>Reset Password</p>
            </div>

            <div class="text-center mb-4">
                <i class="bi bi-lock-reset text-primary" style="font-size: 48px;"></i>
                <p class="mt-2 text-muted">
                    Masukkan email Anda untuk menerima link reset password
                </p>
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
                <?php if ($success): ?>
                    <script>
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 3000);
                    </script>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (!$success): ?>
                <form method="POST" action="forgot_password.php" onsubmit="return SIMagang.validateForm('forgotForm')">
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
                                placeholder="Masukkan email terdaftar"
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send"></i> Kirim Link Reset
                    </button>
                </form>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="login.php" class="text-primary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Login
                </a>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted">© 2024 SIMagang. All rights reserved.</small>
            </div>
        </div>
    </div>

    <script src="../../assets/js/script.js"></script>
</body>
</html>
