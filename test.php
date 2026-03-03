<?php
// Test configuration
require_once __DIR__ . '/backend/config/database.php';

echo "=== SIMagang System Test ===<br><br>";

// Test 1: PHP Version
echo "✓ PHP Version: " . phpversion() . "<br><br>";

// Test 2: Database Connection
echo "Testing Database Connection...<br>";
try {
    $database = new Database();
    $conn = $database->getConnection();
    if ($conn) {
        echo "✓ Database Connected Successfully<br><br>";
    } else {
        echo "✗ Database Connection Failed<br><br>";
    }
} catch (Exception $e) {
    echo "✗ Database Error: " . $e->getMessage() . "<br><br>";
}

// Test 3: Required Functions
echo "Testing Required Functions...<br>";
require_once __DIR__ . '/backend/utils/helpers.php';

$functions = ['sanitizeInput', 'validateEmail', 'generateToken', 'hashPassword', 'getRoleDisplayName'];
foreach ($functions as $func) {
    if (function_exists($func)) {
        echo "✓ Function $func exists<br>";
    } else {
        echo "✗ Function $func not found<br>";
    }
}

echo "<br>";

// Test 4: File Structure
echo "Testing File Structure...<br>";
$required_files = [
    'backend/config/config.php',
    'backend/config/database.php',
    'backend/models/User.php',
    'backend/utils/helpers.php',
    'assets/css/style.css',
    'assets/js/script.js'
];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "✓ File $file exists<br>";
    } else {
        echo "✗ File $file not found<br>";
    }
}

echo "<br><hr>";
echo "<p><a href='views/auth/login.php'>Proceed to Login</a></p>";
?>
