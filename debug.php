<?php
// Simple debug page - shows if there are connection issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug - Session & Connection Test</h1>";
echo "<hr>";

// Test 1: Session
echo "<h2>Session Settings</h2>";
echo "Session Status: " . session_status() . (session_status() === PHP_SESSION_NONE ? " (Not started)" : " (Active)") . "<br>";
session_start();
echo "Session ID: " . session_id() . "<br>";
echo "Session Data: <pre>";
print_r($_SESSION);
echo "</pre>";

// Test 2: Database Connection
echo "<h2>Database Connection</h2>";
try {
    require_once __DIR__ . '/backend/config/database.php';
    $database = new Database();
    $conn = $database->getConnection();
    if ($conn) {
        echo "✓ Database Connected<br>";
        
        // Try to count users
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Total Users in DB: " . $result['total'] . "<br>";
    } else {
        echo "✗ Database Connection Failed<br>";
    }
} catch (Exception $e) {
    echo "✗ Database Error: " . $e->getMessage() . "<br>";
}

// Test 3: Cookie
echo "<h2>Cookie Settings</h2>";
echo "Cookies Enabled: " . (ini_get('session.use_cookies') ? "Yes" : "No") . "<br>";
echo "HttpOnly: " . (ini_get('session.cookie_httponly') ? "Yes" : "No") . "<br>";
echo "Secure: " . (ini_get('session.cookie_secure') ? "Yes" : "No") . "<br>";

// Test 4: File includes
echo "<h2>File Includes Check</h2>";
$files = [
    'backend/config/config.php',
    'backend/config/database.php',
    'backend/models/User.php',
    'backend/utils/helpers.php',
    'views/auth/login.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✓ " . $file . "<br>";
    } else {
        echo "✗ " . $file . " NOT FOUND<br>";
    }
}

echo "<hr>";
echo "<p><a href='views/auth/login.php'>Go to Login</a></p>";
?>
