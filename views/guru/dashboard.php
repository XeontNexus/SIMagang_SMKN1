<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login_simple.php');
    exit;
}

$page_title = 'Dashboard Guru';

// Get user info from session
$user_email = $_SESSION['user_email'] ?? 'Guru';
$user_role = $_SESSION['user_role'] ?? 'guru';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - SIMagang</title>
    <link rel="stylesheet" href="../../../assets/css/theme.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebar_overlay"></div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-mortarboard"></i> SIMagang</h3>
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="dashboard.php" class="sidebar-menu-link active" onclick="closeSidebar()">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="siswa.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-people"></i>
                        <span>Data Siswa</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="logbook.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-journal-text"></i>
                        <span>Review Logbook</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="penilaian.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-award"></i>
                        <span>Penilaian</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="presensi.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-calendar-check"></i>
                        <span>Presensi</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="profil.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-person-circle"></i>
                        <span>Profil</span>
                    </a>
                </li>
                <li class="sidebar-menu-item" style="margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                    <a href="../auth/logout.php" class="sidebar-menu-link" style="color: rgba(255,255,255,0.8);">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Navbar -->
            <div class="top-navbar">
                <button class="hamburger-btn" id="hamburger_btn" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div class="navbar-title">
                    <h1>Dashboard Guru</h1>
                    <p>Monitor dan bimbing siswa magang</p>
                </div>
                <div class="navbar-actions">
                    <span style="color: var(--dark); font-size: 0.9rem;">
                        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($user_email); ?>
                    </span>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-value">15</div>
                    <div class="stat-label">Siswa Bimbingan</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div class="stat-value">8</div>
                    <div class="stat-label">Logbook Review</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon danger">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="stat-value">3</div>
                    <div class="stat-label">Izin Pending</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div class="stat-value">12</div>
                    <div class="stat-label">Siswa Aktif</div>
                </div>
            </div>

            <!-- Quick Actions & Activities -->
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; margin-top: 2rem;">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-lightning"></i> Aksi Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <a href="siswa.php" class="btn-primary" style="padding: 0.75rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 8px; color: white; text-decoration: none;">
                                <i class="bi bi-people"></i> Lihat Siswa
                            </a>
                            <a href="logbook.php" class="btn-warning" style="padding: 0.75rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 8px; color: white; text-decoration: none;">
                                <i class="bi bi-journal-text"></i> Review Logbook
                            </a>
                            <a href="penilaian.php" class="btn-secondary" style="padding: 0.75rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 8px; color: white; text-decoration: none;">
                                <i class="bi bi-award"></i> Penilaian
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h6 class="mb-0"><i class="bi bi-clock-history"></i> Aktivitas Terbaru</h6>
                        <a href="aktivitas.php" style="font-size: 0.85rem; color: var(--primary); text-decoration: none;">Lihat Semua →</a>
                    </div>
                    <div class="card-body">
                        <table class="table" style="margin: 0;">
                            <thead>
                                <tr>
                                    <th>Tipe</th>
                                    <th>Siswa</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="bi bi-journal-text"></i> Logbook</td>
                                    <td>Ahmad Rizki</td>
                                    <td>2 jam</td>
                                    <td><span class="badge" style="background: var(--warning); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">⏳ Menunggu</span></td>
                                </tr>
                                <tr>
                                    <td><i class="bi bi-file-earmark-text"></i> Izin</td>
                                    <td>Siti Nurhaliza</td>
                                    <td>3 jam</td>
                                    <td><span class="badge" style="background: var(--warning); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">⏳ Menunggu</span></td>
                                </tr>
                                <tr>
                                    <td><i class="bi bi-journal-text"></i> Logbook</td>
                                    <td>Budi Santoso</td>
                                    <td>5 jam</td>
                                    <td><span class="badge" style="background: var(--success); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">✓ Disetujui</span></td>
                                </tr>
                                <tr>
                                    <td><i class="bi bi-calendar-check"></i> Presensi</td>
                                    <td>Diana Putri</td>
                                    <td>1 hari</td>
                                    <td><span class="badge" style="background: var(--success); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">✓ Hadir</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pending Tasks -->
            <div class="card" style="margin-top: 2rem;">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-exclamation-circle"></i> Tugas Menunggu Persetujuan</h6>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div style="border-left: 4px solid var(--warning); background: #FFF3E0; padding: 1rem; border-radius: 4px;">
                            <h6 style="margin: 0 0 0.5rem; color: var(--dark);">
                                <i class="bi bi-journal-text"></i> Logbook Menunggu Review
                            </h6>
                            <p style="margin: 0 0 1rem; font-size: 0.9rem; color: var(--gray);">Ada 8 logbook dari siswa yang perlu direview dan disetujui.</p>
                            <a href="logbook.php" class="btn-warning" style="display: inline-block; padding: 0.5rem 1rem; text-align: center; border-radius: 6px; color: white; text-decoration: none; font-size: 0.85rem;">Review Sekarang</a>
                        </div>
                        <div style="border-left: 4px solid var(--danger); background: #FFEBEE; padding: 1rem; border-radius: 4px;">
                            <h6 style="margin: 0 0 0.5rem; color: var(--dark);">
                                <i class="bi bi-file-earmark-text"></i> Pengajuan Izin Menunggu
                            </h6>
                            <p style="margin: 0 0 1rem; font-size: 0.9rem; color: var(--gray);">Ada 3 pengajuan izin yang perlu diproses.</p>
                            <a href="pengajuan_izin.php" class="btn-danger" style="display: inline-block; padding: 0.5rem 1rem; text-align: center; border-radius: 6px; color: white; text-decoration: none; font-size: 0.85rem;">Proses Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <style>
        .btn-primary {
            background: var(--primary);
            transition: background 0.3s;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        
        .btn-secondary {
            background: var(--secondary);
            transition: background 0.3s;
        }
        
        .btn-secondary:hover {
            background: #1A6BA5;
        }
        
        .btn-warning {
            background: var(--warning);
            transition: background 0.3s;
        }
        
        .btn-warning:hover {
            background: #D68E0C;
        }

        .btn-danger {
            background: var(--danger);
            transition: background 0.3s;
        }
        
        .btn-danger:hover {
            background: #C23030;
        }

        table {
            font-size: 0.9rem;
        }

        table thead th {
            background: var(--light);
            color: var(--dark);
            font-weight: 600;
            border: none;
        }

        table tbody tr {
            border-bottom: 1px solid var(--light);
        }

        table tbody tr:hover {
            background: #F9F9F9;
        }

        body {
            overflow-x: hidden;
        }
    </style>

    <!-- Footer -->
    <footer style="background: var(--primary); color: white; text-align: center; padding: 1rem; font-size: 0.85rem;">
        <p style="margin: 0;">
            <strong>SMKN 1 Perhentian Raja</strong> - Sistem Informasi Magang © 2024
        </p>
    </footer>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar_overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar_overlay');
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }

        // Close sidebar when clicking overlay
        document.getElementById('sidebar_overlay').addEventListener('click', closeSidebar);

        // Close sidebar when pressing Escape
        document.addEventListener('keydown', function(e) {
            if(e.key === 'Escape') {
                closeSidebar();
            }
        });
    </script>
</body>
</html>
