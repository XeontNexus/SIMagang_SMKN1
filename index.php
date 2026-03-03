<?php
session_start();

// Check if user is logged in
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    // Redirect to appropriate dashboard based on role
    $role = $_SESSION['user_role'] ?? '';
    
    switch ($role) {
        case 'siswa':
            header('Location: views/siswa/dashboard_simple.php');
            exit;
        case 'guru':
            header('Location: views/guru/dashboard.php');
            exit;
        case 'dudi':
            header('Location: views/dudi/dashboard.php');
            exit;
        case 'admin':
            header('Location: views/admin/dashboard.php');
            exit;
    }
}

// Redirect to login page if not logged in
header('Location: views/auth/login_simple.php');
exit;
?>

