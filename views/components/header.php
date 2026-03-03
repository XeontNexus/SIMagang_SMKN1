<?php
// Start session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Jangan redirect, biarkan page load dengan error atau buat login page
    // Untuk menghindari redirect loop
    die('Akses ditolak. Silakan <a href="/SIMagang2/views/auth/login.php">login</a>');
}

$user_role = $_SESSION['user_role'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';

// Get role display name
function getRoleDisplayName($role) {
    $roles = [
        'siswa' => 'Siswa',
        'guru' => 'Guru Pembimbing',
        'dudi' => 'Dudi Mitra',
        'admin' => 'Administrator'
    ];
    return $roles[$role] ?? $role;
}

// Get dashboard path
function getDashboardPath($role) {
    $paths = [
        'siswa' => '../siswa/dashboard.php',
        'guru' => '../guru/dashboard.php',
        'dudi' => '../dudi/dashboard.php',
        'admin' => '../admin/dashboard.php'
    ];
    return $paths[$role] ?? '../siswa/dashboard.php';
}
?>
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'SIMagang'; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="main-layout">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-mortarboard-fill"></i> SIMagang</h3>
            </div>
            
            <ul class="sidebar-nav">
                <?php if ($user_role === 'siswa'): ?>
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="logbook.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'logbook.php' ? 'active' : ''; ?>">
                            <i class="bi bi-journal-text"></i> Logbook
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="presensi.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'presensi.php' ? 'active' : ''; ?>">
                            <i class="bi bi-calendar-check"></i> Presensi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pengajuan_izin.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'pengajuan_izin.php' ? 'active' : ''; ?>">
                            <i class="bi bi-file-earmark-text"></i> Pengajuan Izin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="penilaian.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'penilaian.php' ? 'active' : ''; ?>">
                            <i class="bi bi-award"></i> Penilaian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="profil.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'profil.php' ? 'active' : ''; ?>">
                            <i class="bi bi-person"></i> Profil
                        </a>
                    </li>
                <?php elseif ($user_role === 'guru'): ?>
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="siswa.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'siswa.php' ? 'active' : ''; ?>">
                            <i class="bi bi-people"></i> Data Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="logbook.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'logbook.php' ? 'active' : ''; ?>">
                            <i class="bi bi-journal-text"></i> Logbook Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="presensi.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'presensi.php' ? 'active' : ''; ?>">
                            <i class="bi bi-calendar-check"></i> Presensi Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pengajuan_izin.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'pengajuan_izin.php' ? 'active' : ''; ?>">
                            <i class="bi bi-file-earmark-text"></i> Pengajuan Izin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="penilaian.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'penilaian.php' ? 'active' : ''; ?>">
                            <i class="bi bi-award"></i> Penilaian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="profil.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'profil.php' ? 'active' : ''; ?>">
                            <i class="bi bi-person"></i> Profil
                        </a>
                    </li>
                <?php elseif ($user_role === 'dudi'): ?>
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="tempat_magang.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'tempat_magang.php' ? 'active' : ''; ?>">
                            <i class="bi bi-geo-alt"></i> Tempat Magang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="siswa.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'siswa.php' ? 'active' : ''; ?>">
                            <i class="bi bi-people"></i> Siswa Magang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pengumuman.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'pengumuman.php' ? 'active' : ''; ?>">
                            <i class="bi bi-megaphone"></i> Pengumuman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="monitoring.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'monitoring.php' ? 'active' : ''; ?>">
                            <i class="bi bi-eye"></i> Monitoring
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="profil.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'profil.php' ? 'active' : ''; ?>">
                            <i class="bi bi-building"></i> Profil Perusahaan
                        </a>
                    </li>
                <?php elseif ($user_role === 'admin'): ?>
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="users.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : ''; ?>">
                            <i class="bi bi-people"></i> Manajemen User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="siswa.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'siswa.php' ? 'active' : ''; ?>">
                            <i class="bi bi-mortarboard"></i> Data Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="guru.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'guru.php' ? 'active' : ''; ?>">
                            <i class="bi bi-person-badge"></i> Data Guru
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="dudi.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dudi.php' ? 'active' : ''; ?>">
                            <i class="bi bi-building"></i> Data Dudi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="tempat_magang.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'tempat_magang.php' ? 'active' : ''; ?>">
                            <i class="bi bi-geo-alt"></i> Tempat Magang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="penempatan.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'penempatan.php' ? 'active' : ''; ?>">
                            <i class="bi bi-diagram-3"></i> Penempatan Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="laporan.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'laporan.php' ? 'active' : ''; ?>">
                            <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pengaturan.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'pengaturan.php' ? 'active' : ''; ?>">
                            <i class="bi bi-gear"></i> Pengaturan
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <hr class="text-white-50">

            <div class="px-3 text-white-50 small">
                <div class="mb-2">
                    <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($user_email); ?>
                </div>
                <div>
                    <i class="bi bi-briefcase"></i> <?php echo getRoleDisplayName($user_role); ?>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <nav class="top-navbar">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-link mobile-menu-toggle d-md-none">
                            <i class="bi bi-list fs-4"></i>
                        </button>
                        <a href="<?php echo getDashboardPath($user_role); ?>" class="navbar-brand">
                            <i class="bi bi-mortarboard-fill"></i> SIMagang
                        </a>
                    </div>

                    <div class="navbar-actions">
                        <!-- Notifications -->
                        <div class="dropdown">
                            <button class="btn btn-link position-relative" data-bs-toggle="dropdown">
                                <i class="bi bi-bell fs-5"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    3
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header">Notifikasi</h6></li>
                                <li><a class="dropdown-item" href="#">
                                    <i class="bi bi-info-circle text-info me-2"></i>
                                    Logbook Anda telah disetujui
                                </a></li>
                                <li><a class="dropdown-item" href="#">
                                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                    Pengajuan izin menunggu persetujuan
                                </a></li>
                                <li><a class="dropdown-item" href="#">
                                    <i class="bi bi-calendar-check text-success me-2"></i>
                                    Jadwal monitoring besok jam 10:00
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="#">Lihat semua notifikasi</a></li>
                            </ul>
                        </div>

                        <!-- User Menu -->
                        <div class="dropdown">
                            <button class="btn btn-link d-flex align-items-center" data-bs-toggle="dropdown">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="d-none d-md-block text-start">
                                    <div class="small fw-semibold"><?php echo htmlspecialchars($user_email); ?></div>
                                    <div class="small text-muted"><?php echo getRoleDisplayName($user_role); ?></div>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header">Akun Saya</h6></li>
                                <li><a class="dropdown-item" href="profil.php">
                                    <i class="bi bi-person me-2"></i> Profil
                                </a></li>
                                <li><a class="dropdown-item" href="pengaturan.php">
                                    <i class="bi bi-gear me-2"></i> Pengaturan
                                </a></li>
                                <li><a class="dropdown-item" href="ubah_password.php">
                                    <i class="bi bi-key me-2"></i> Ubah Password
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger logout-btn" href="../auth/logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
