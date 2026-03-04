<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login_simple.php');
    exit;
}

$page_title = 'Dashboard DUDI';

// Get user info from session
$user_email = $_SESSION['user_email'] ?? 'DUDI';
$user_role = $_SESSION['user_role'] ?? 'dudi';
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
                    <a href="pengumuman.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-megaphone"></i>
                        <span>Pengumuman</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="tempat_magang.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-geo-alt"></i>
                        <span>Tempat Magang</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="monitoring.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-eye"></i>
                        <span>Monitoring</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="laporan.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-file-earmark"></i>
                        <span>Laporan</span>
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
                    <h1>Dashboard DUDI</h1>
                    <p>Kelola siswa magang dan pengumuman</p>
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
                    <div class="stat-value">20</div>
                    <div class="stat-label">Siswa Magang</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div class="stat-value">5</div>
                    <div class="stat-label">Tempat Magang</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <div class="stat-value">8</div>
                    <div class="stat-label">Pengumuman</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="bi bi-eye"></i>
                    </div>
                    <div class="stat-value">15</div>
                    <div class="stat-label">Monitoring</div>
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
                            <a href="pengumuman.php?action=add" class="btn-primary" style="padding: 0.75rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 8px; color: white; text-decoration: none;">
                                <i class="bi bi-megaphone"></i> Buat Pengumuman
                            </a>
                            <a href="tempat_magang.php" class="btn-secondary" style="padding: 0.75rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 8px; color: white; text-decoration: none;">
                                <i class="bi bi-geo-alt"></i> Kelola Tempat
                            </a>
                            <a href="monitoring.php" class="btn-warning" style="padding: 0.75rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 8px; color: white; text-decoration: none;">
                                <i class="bi bi-eye"></i> Monitoring
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
                                    <th>Detail</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="bi bi-calendar-check"></i> Presensi</td>
                                    <td>Ahmad Rizki</td>
                                    <td>2 jam</td>
                                    <td><span class="badge" style="background: var(--success); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">✓ Hadir</span></td>
                                </tr>
                                <tr>
                                    <td><i class="bi bi-journal-text"></i> Logbook</td>
                                    <td>Siti Nurhaliza</td>
                                    <td>3 jam</td>
                                    <td><span class="badge" style="background: var(--warning); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">📤 Dikirim</span></td>
                                </tr>
                                <tr>
                                    <td><i class="bi bi-eye"></i> Monitoring</td>
                                    <td>Budi Santoso</td>
                                    <td>5 jam</td>
                                    <td><span class="badge" style="background: var(--success); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">✓ Selesai</span></td>
                                </tr>
                                <tr>
                                    <td><i class="bi bi-megaphone"></i> Pengumuman</td>
                                    <td>Jadwal Monitoring</td>
                                    <td>1 hari</td>
                                    <td><span class="badge" style="background: var(--info); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">📢 Diterbitkan</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Siswa Performance -->
            <div class="card" style="margin-top: 2rem;">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-chart-bar"></i> Performa Siswa Magang</h6>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; text-align: center;">
                        <div>
                            <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 0.5rem;">
                                <i class="bi bi-trophy-fill"></i>
                            </div>
                            <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary);">85%</div>
                            <div style="color: var(--gray); font-size: 0.9rem;">Rata-rata Kehadiran</div>
                        </div>
                        <div>
                            <div style="font-size: 2.5rem; color: var(--success); margin-bottom: 0.5rem;">
                                <i class="bi bi-journal-check"></i>
                            </div>
                            <div style="font-size: 1.75rem; font-weight: 700; color: var(--success);">78%</div>
                            <div style="color: var(--gray); font-size: 0.9rem;">Logbook Selesai</div>
                        </div>
                        <div>
                            <div style="font-size: 2.5rem; color: var(--info); margin-bottom: 0.5rem;">
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <div style="font-size: 1.75rem; font-weight: 700; color: var(--info);">4.2</div>
                            <div style="color: var(--gray); font-size: 0.9rem;">Nilai Rata-rata</div>
                        </div>
                        <div>
                            <div style="font-size: 2.5rem; color: var(--warning); margin-bottom: 0.5rem;">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <div style="font-size: 1.75rem; font-weight: 700; color: var(--warning);">92%</div>
                            <div style="color: var(--gray); font-size: 0.9rem;">Ketepatan Waktu</div>
                        </div>
                    </div>
                    <hr style="margin: 1.5rem 0;">
                    <div style="text-align: center;">
                        <a href="laporan.php" class="btn-primary" style="display: inline-block; padding: 0.75rem 1.5rem; border-radius: 8px; color: white; text-decoration: none;">
                            <i class="bi bi-file-earmark-bar-graph"></i> Lihat Laporan Lengkap
                        </a>
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
