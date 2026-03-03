<?php
session_start();

// Simple session check
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: ../../views/auth/login_simple.php');
    exit;
}

$user_role = $_SESSION['user_role'] ?? 'siswa';
$user_email = $_SESSION['user_email'] ?? 'Unknown';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - SIMagang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8fafc;
        }
        .sidebar {
            background: linear-gradient(180deg, #1e40af 0%, #1e3a8a 100%);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            padding: 1.5rem 0;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        .sidebar h3 {
            color: white;
            padding: 0 1.5rem 1.5rem;
            margin: 0;
        }
        .nav-item {
            list-style: none;
            margin: 0.5rem 0;
        }
        .nav-link {
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            display: block;
            transition: all 0.3s;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            padding-left: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        .stat-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .stat-card h5 {
            color: #64748b;
            font-size: 0.9rem;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #1e293b;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <h3><i class="bi bi-mortarboard-fill"></i> SIMagang</h3>
            <ul style="padding: 0; margin: 0;">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link active">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-journal-text"></i> Logbook
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-calendar-check"></i> Presensi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-file-earmark-text"></i> Pengajuan Izin
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-award"></i> Penilaian
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-person"></i> Profil
                    </a>
                </li>
                <li class="nav-item" style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 2rem; padding-top: 1rem;">
                    <a href="../../auth/logout.php" class="nav-link">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content w-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Dashboard Siswa</h1>
                    <p class="text-muted mb-0">Selamat datang, <?php echo htmlspecialchars($user_email); ?></p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="color: #3b82f6;">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <div class="stat-value">12</div>
                        <h5>Total Logbook</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="color: #10b981;">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stat-value">18</div>
                        <h5>Presensi Hadir</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="color: #f59e0b;">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="stat-value">2</div>
                        <h5>Pengajuan Izin</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="color: #06b6d4;">
                            <i class="bi bi-award"></i>
                        </div>
                        <div class="stat-value">85</div>
                        <h5>Nilai Rata-rata</h5>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Magang</h5>
                </div>
                <div class="card-body">
                    <p><strong>Status:</strong> Aktif</p>
                    <p><strong>Tempat Magang:</strong> PT. Teknologi Indonesia</p>
                    <p><strong>Periode:</strong> 10 Januari - 10 Maret 2024</p>
                    <p><strong>Guru Pembimbing:</strong> Budi Santoso, S.Pd</p>
                </div>
            </div>

            <hr>
            <p class="text-muted text-center mt-4">
                System Status: ✓ OK | Sessions: Enabled | Database: Check install.php
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
