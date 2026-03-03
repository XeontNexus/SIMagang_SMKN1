<?php
/**
 * SIMagang Database Installer
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'simagang';

echo "<h1>SIMagang Database Installation</h1>";
echo "<hr>";

// Step 1: Create connection without database
try {
    $conn = new PDO("mysql:host=$host", $username, $password);
    echo "✓ Connected to MySQL Server<br>";
} catch (PDOException $e) {
    die("✗ Connection failed: " . $e->getMessage() . "<br>");
}

// Step 2: Create database
try {
    $conn->exec("CREATE DATABASE IF NOT EXISTS $db_name");
    echo "✓ Database '$db_name' created/exists<br>";
} catch (PDOException $e) {
    die("✗ Create database failed: " . $e->getMessage() . "<br>");
}

// Step 3: Select database
try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    echo "✓ Database selected<br>";
} catch (PDOException $e) {
    die("✗ Select database failed: " . $e->getMessage() . "<br>");
}

// Step 4: Read and execute SQL file
$sql_file = __DIR__ . '/database/simagang.sql';
if (!file_exists($sql_file)) {
    die("✗ SQL file not found: $sql_file<br>");
}

$sql_content = file_get_contents($sql_file);

// Split SQL statements
$statements = array_filter(
    array_map('trim', preg_split('/;/', $sql_content)),
    function($stmt) { return !empty($stmt) && !preg_match('/^--/', $stmt); }
);

$executed = 0;
foreach ($statements as $statement) {
    // Skip comments and empty statements
    if (empty($statement) || preg_match('/^--/', $statement)) {
        continue;
    }
    
    try {
        $conn->exec($statement);
        $executed++;
    } catch (PDOException $e) {
        echo "⚠ Warning: " . $e->getMessage() . "<br>";
    }
}

echo "✓ Executed $executed SQL statements<br>";

// Step 5: Create sample users
echo "<h3>Creating Sample Users</h3>";

// Sample data
$sample_users = [
    ['email' => 'siswa@test.com', 'password' => 'siswa123', 'role' => 'siswa'],
    ['email' => 'guru@test.com', 'password' => 'guru123', 'role' => 'guru'],
    ['email' => 'dudi@test.com', 'password' => 'dudi123', 'role' => 'dudi'],
    ['email' => 'admin@test.com', 'password' => 'admin123', 'role' => 'admin'],
];

try {
    $stmt = $conn->prepare("INSERT INTO users (email, password, role, status) VALUES (?, ?, ?, 'active')");
    
    foreach ($sample_users as $user) {
        // Check if user already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$user['email']]);
        
        if ($check->rowCount() == 0) {
            $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
            $stmt->execute([$user['email'], $hashed_password, $user['role']]);
            echo "✓ Created user: {$user['email']} ({$user['role']})<br>";
        } else {
            echo "⚠ User {$user['email']} already exists<br>";
        }
    }
} catch (PDOException $e) {
    echo "⚠ Create users error: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h2 style='color: green;'>✓ Installation Complete!</h2>";
echo "<p>Sample Users:</p>";
echo "<ul>";
foreach ($sample_users as $user) {
    echo "<li>Email: {$user['email']} | Password: {$user['password']} | Role: {$user['role']}</li>";
}
echo "</ul>";
echo "<p><a href='views/auth/login.php'>Proceed to Login</a></p>";
?>
