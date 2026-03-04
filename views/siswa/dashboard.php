<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login_simple.php');
    exit;
}

$page_title = 'Dashboard Siswa';

// Get user info from session
$user_email = $_SESSION['user_email'] ?? 'Siswa';
$user_role = $_SESSION['user_role'] ?? 'siswa';
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
                    <a href="logbook.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-journal-text"></i>
                        <span>Logbook</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="presensi.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-calendar-check"></i>
                        <span>Presensi</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="pengajuan_izin.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-file-earmark-plus"></i>
                        <span>Ajukan Izin</span>
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
            <!-- Top Navbar (Sticky) -->
            <div class="top-navbar">
                <button class="hamburger-btn" id="hamburger_btn" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div class="navbar-title">
                    <h1>Dashboard Siswa</h1>
                    <p>Kelola aktivitas magang Anda</p>
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
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div class="stat-value">12</div>
                    <div class="stat-label">Total Logbook</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stat-value">18</div>
                    <div class="stat-label">Presensi Hadir</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="stat-value">2</div>
                    <div class="stat-label">Pengajuan Izin</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="bi bi-award"></i>
                    </div>
                    <div class="stat-value">85</div>
                    <div class="stat-label">Nilai Rata-rata</div>
                </div>
            </div>

            <!-- Quick Actions & Recent Logbook -->
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; margin-top: 2rem;">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-lightning"></i> Aksi Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <a href="logbook.php?action=add" class="btn-primary" style="padding: 0.75rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 8px; color: white; text-decoration: none;">
                                <i class="bi bi-journal-plus"></i> Tambah Logbook
                            </a>
                            <a href="presensi.php" class="btn-secondary" style="padding: 0.75rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 8px; color: white; text-decoration: none;">
                                <i class="bi bi-calendar-plus"></i> Presensi Hari Ini
                            </a>
                            <a href="pengajuan_izin.php?action=add" class="btn-warning" style="padding: 0.75rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 8px; color: white; text-decoration: none;">
                                <i class="bi bi-file-earmark-plus"></i> Ajukan Izin
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Logbook -->
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h6 class="mb-0"><i class="bi bi-clock-history"></i> Logbook Terbaru</h6>
                        <a href="logbook.php" style="font-size: 0.85rem; color: var(--primary); text-decoration: none;">Lihat Semua →</a>
                    </div>
                    <div class="card-body">
                        <table class="table" style="margin: 0;">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kegiatan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>15 Jan 2024</td>
                                    <td>Mempelajari struktur database</td>
                                    <td><span class="badge" style="background: var(--success); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">✓ Disetujui</span></td>
                                </tr>
                                <tr>
                                    <td>14 Jan 2024</td>
                                    <td>Install development environment</td>
                                    <td><span class="badge" style="background: var(--success); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">✓ Disetujui</span></td>
                                </tr>
                                <tr>
                                    <td>13 Jan 2024</td>
                                    <td>Orientasi tempat magang</td>
                                    <td><span class="badge" style="background: var(--warning); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">⏳ Menunggu</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Announcements -->
            <div class="card" style="margin-top: 2rem;">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-megaphone"></i> Pengumuman Terbaru</h6>
                </div>
                <div class="card-body">
                    <div style="background: #E8F6FF; border-left: 4px solid var(--info); padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                        <strong>📢 Pengumuman:</strong> Jadwal monitoring guru pembimbing akan dilaksanakan pada hari Senin, 22 Januari 2024. Pastikan logbook Anda sudah lengkap.
                    </div>
                    <div style="background: #FFF3E0; border-left: 4px solid var(--warning); padding: 1rem; border-radius: 4px;">
                        <strong>⚠️ Reminder:</strong> Jangan lupa melakukan presensi setiap hari kerja dan upload laporan mingguan.
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer style="background: var(--primary); color: white; text-align: center; padding: 1rem; font-size: 0.85rem;">
        <p style="margin: 0;">
            <strong>SMKN 1 Perhentian Raja</strong> - Sistem Informasi Magang © 2024
        </p>
    </footer>

    <style>
        body {
            overflow-x: hidden;
        }

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

        /* Responsive Grid for Quick Actions & Logbook */
        @media (max-width: 768px) {
            div[style*="grid-template-columns: 1fr 2fr"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

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
