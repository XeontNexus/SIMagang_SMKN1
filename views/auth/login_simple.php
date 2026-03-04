<?php
session_start();

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

// Test credentials for all roles
$credentials = [
    'siswa' => ['email' => 'test@test.com', 'password' => 'test123'],
    'guru' => ['email' => 'guru@test.com', 'password' => 'guru123'],
    'dudi' => ['email' => 'dudi@test.com', 'password' => 'dudi123'],
    'admin' => ['email' => 'admin@test.com', 'password' => 'admin123']
];

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Check credentials against all roles
    foreach ($credentials as $role => $creds) {
        if ($email === $creds['email'] && $password === $creds['password']) {
            $_SESSION['user_id'] = 1;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $role;
            
            // Redirect to appropriate dashboard
            header('Location: ../' . $role . '/dashboard.php');
            exit;
        }
    }
    
    $error = 'Email atau password salah';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMagang</title>
    <link rel="stylesheet" href="../../../assets/css/theme.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>
                    <i class="bi bi-mortarboard-fill"></i>
                    SIMagang
                </h1>
                <p>Sistem Informasi Magang</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="login-body">
                <div class="mb-3">
                    <label for="email" class="form-label" style="font-weight: 600; color: var(--dark);">Email</label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        placeholder="Masukkan email Anda"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label" style="font-weight: 600; color: var(--dark);">Password</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password" 
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button type="submit" class="btn-login" style="width: 100%;">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </form>

            <div class="text-center mt-4">
                <small style="color: var(--gray);">
                    <strong>Test Credentials:</strong><br>
                    Siswa: test@test.com / test123<br>
                    Guru: guru@test.com / guru123<br>
                    DUDI: dudi@test.com / dudi123<br>
                    Admin: admin@test.com / admin123
                </small>
            </div>
        </div>
    </div>

    <script>
        // Simple login - roles managed by admin in user settings
    </script>
</body>
</html>
