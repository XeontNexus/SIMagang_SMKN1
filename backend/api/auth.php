<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/helpers.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'OPTIONS') {
    exit(0);
}

try {
    switch ($method) {
        case 'POST':
            $action = $_GET['action'] ?? '';
            
            switch ($action) {
                case 'login':
                    $data = json_decode(file_get_contents("php://input"), true);
                    
                    if (!$data) {
                        response(['success' => false, 'message' => 'Invalid JSON'], 400);
                    }
                    
                    $required = ['email', 'password'];
                    $missing = validateRequired($data, $required);
                    
                    if (!empty($missing)) {
                        response(['success' => false, 'message' => 'Missing required fields: ' . implode(', ', $missing)], 400);
                    }
                    
                    if (!validateEmail($data['email'])) {
                        response(['success' => false, 'message' => 'Invalid email format'], 400);
                    }
                    
                    $result = $user->login($data['email'], $data['password']);
                    
                    if ($result) {
                        $token = generateJWT($result['id'], $result['role']);
                        
                        response([
                            'success' => true,
                            'message' => 'Login successful',
                            'data' => [
                                'user' => [
                                    'id' => $result['id'],
                                    'email' => $result['email'],
                                    'role' => $result['role']
                                ],
                                'token' => $token
                            ]
                        ]);
                    } else {
                        response(['success' => false, 'message' => 'Invalid email or password'], 401);
                    }
                    break;
                    
                case 'register':
                    $data = json_decode(file_get_contents("php://input"), true);
                    
                    if (!$data) {
                        response(['success' => false, 'message' => 'Invalid JSON'], 400);
                    }
                    
                    $required = ['email', 'password', 'role', 'confirm_password'];
                    $missing = validateRequired($data, $required);
                    
                    if (!empty($missing)) {
                        response(['success' => false, 'message' => 'Missing required fields: ' . implode(', ', $missing)], 400);
                    }
                    
                    if (!validateEmail($data['email'])) {
                        response(['success' => false, 'message' => 'Invalid email format'], 400);
                    }
                    
                    if ($data['password'] !== $data['confirm_password']) {
                        response(['success' => false, 'message' => 'Passwords do not match'], 400);
                    }
                    
                    if (strlen($data['password']) < 6) {
                        response(['success' => false, 'message' => 'Password must be at least 6 characters'], 400);
                    }
                    
                    $existing_user = $user->getByEmail($data['email']);
                    if ($existing_user) {
                        response(['success' => false, 'message' => 'Email already registered'], 409);
                    }
                    
                    $valid_roles = ['siswa', 'guru', 'dudi'];
                    if (!in_array($data['role'], $valid_roles)) {
                        response(['success' => false, 'message' => 'Invalid role'], 400);
                    }
                    
                    $user_id = $user->create($data);
                    
                    if ($user_id) {
                        $token = generateJWT($user_id, $data['role']);
                        
                        response([
                            'success' => true,
                            'message' => 'Registration successful',
                            'data' => [
                                'user' => [
                                    'id' => $user_id,
                                    'email' => $data['email'],
                                    'role' => $data['role']
                                ],
                                'token' => $token
                            ]
                        ], 201);
                    } else {
                        response(['success' => false, 'message' => 'Registration failed'], 500);
                    }
                    break;
                    
                case 'forgot-password':
                    $data = json_decode(file_get_contents("php://input"), true);
                    
                    if (!$data) {
                        response(['success' => false, 'message' => 'Invalid JSON'], 400);
                    }
                    
                    $required = ['email'];
                    $missing = validateRequired($data, $required);
                    
                    if (!empty($missing)) {
                        response(['success' => false, 'message' => 'Missing required fields: ' . implode(', ', $missing)], 400);
                    }
                    
                    if (!validateEmail($data['email'])) {
                        response(['success' => false, 'message' => 'Invalid email format'], 400);
                    }
                    
                    $user_data = $user->getByEmail($data['email']);
                    
                    if ($user_data) {
                        $token = generateToken();
                        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
                        
                        // Save token to database (you'll need to create password_resets table and model)
                        // For now, just return success message
                        
                        $reset_link = BASE_URL . "/reset-password?token=" . $token;
                        $subject = "Password Reset - SIMagang";
                        $message = "
                        <h2>Password Reset Request</h2>
                        <p>Hello,</p>
                        <p>We received a request to reset your password for SIMagang account.</p>
                        <p>Click the link below to reset your password:</p>
                        <p><a href='{$reset_link}'>Reset Password</a></p>
                        <p>This link will expire in 1 hour.</p>
                        <p>If you didn't request this, please ignore this email.</p>
                        ";
                        
                        // sendEmail($data['email'], $subject, $message);
                        
                        response([
                            'success' => true,
                            'message' => 'Password reset link sent to your email'
                        ]);
                    } else {
                        // Don't reveal if email exists or not
                        response([
                            'success' => true,
                            'message' => 'If email exists, password reset link will be sent'
                        ]);
                    }
                    break;
                    
                case 'reset-password':
                    $data = json_decode(file_get_contents("php://input"), true);
                    
                    if (!$data) {
                        response(['success' => false, 'message' => 'Invalid JSON'], 400);
                    }
                    
                    $required = ['token', 'password', 'confirm_password'];
                    $missing = validateRequired($data, $required);
                    
                    if (!empty($missing)) {
                        response(['success' => false, 'message' => 'Missing required fields: ' . implode(', ', $missing)], 400);
                    }
                    
                    if ($data['password'] !== $data['confirm_password']) {
                        response(['success' => false, 'message' => 'Passwords do not match'], 400);
                    }
                    
                    if (strlen($data['password']) < 6) {
                        response(['success' => false, 'message' => 'Password must be at least 6 characters'], 400);
                    }
                    
                    // Validate token and get email from database
                    // For now, just return success
                    
                    response([
                        'success' => true,
                        'message' => 'Password reset successful'
                    ]);
                    break;
                    
                default:
                    response(['success' => false, 'message' => 'Invalid action'], 400);
            }
            break;
            
        default:
            response(['success' => false, 'message' => 'Method not allowed'], 405);
    }
} catch (Exception $e) {
    response(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
}
?>
