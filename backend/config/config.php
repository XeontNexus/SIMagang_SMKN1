<?php
// Configuration settings
define('BASE_URL', 'http://localhost:8000');
define('API_BASE_URL', BASE_URL . '/backend/api');
define('UPLOAD_PATH', '../uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// JWT Secret Key
define('JWT_SECRET', 'your-secret-key-here');

// Email configuration (for password reset)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('FROM_EMAIL', 'noreply@simagang.com');
define('FROM_NAME', 'SIMagang System');

// Session configuration (must be before session_start)
// These should be set in php.ini or called before session_start()
// ini_set('session.cookie_httponly', 1);
// ini_set('session.use_only_cookies', 1);
// ini_set('session.cookie_secure', 0);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>


