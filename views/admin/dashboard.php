<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login_simple.php');
    exit;
}

$page_title = 'Dashboard Admin';

// Get user info from session
$user_email = $_SESSION['user_email'] ?? 'Admin';
$user_role = $_SESSION['user_role'] ?? 'admin';
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
                    <a href="users.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-people"></i>
                        <span>Manajemen User</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="kelas_jurusan.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-diagram-3"></i>
                        <span>Kelas & Jurusan</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="penempatan.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-diagram-3"></i>
                        <span>Penempatan Siswa</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="laporan.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span>Laporan</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="pengaturan.php" class="sidebar-menu-link" onclick="closeSidebar()">
                        <i class="bi bi-gear"></i>
                        <span>Pengaturan</span>
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
                    <h1>Dashboard Admin</h1>
                    <p>Kelola seluruh sistem SIMagang</p>
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
                    <div class="stat-value">150</div>
                    <div class="stat-label">Total Users</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <div class="stat-value">85</div>
                    <div class="stat-label">Siswa Aktif</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="stat-value">12</div>
                    <div class="stat-label">Guru</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="stat-value">25</div>
                    <div class="stat-label">DUDI</div>
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
                            <a href="users.php?action=add" class="btn-primary" style="padding: 0.75rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 8px; color: white; text-decoration: none;">
                                <i class="bi bi-person-plus"></i> Tambah User
                            </a>
                            <a href="penempatan.php" class="btn-secondary" style="padding: 0.75rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 8px; color: white; text-decoration: none;">
                                <i class="bi bi-diagram-3"></i> Penempatan Siswa
                            </a>
                            <a href="laporan.php" class="btn-info" style="padding: 0.75rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 8px; color: white; text-decoration: none;">
                                <i class="bi bi-file-earmark-bar-graph"></i> Laporan
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
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="bi bi-person-plus"></i> User</td>
                                    <td>User baru: Ahmad Rizki</td>
                                    <td>2 jam</td>
                                </tr>
                                <tr>
                                    <td><i class="bi bi-diagram-3"></i> Penempatan</td>
                                    <td>Penempatan ke PT. Indonesia</td>
                                    <td>3 jam</td>
                                </tr>
                                <tr>
                                    <td><i class="bi bi-megaphone"></i> Pengumuman</td>
                                    <td>Pengumuman baru dibuat</td>
                                    <td>5 jam</td>
                                </tr>
                                <tr>
                                    <td><i class="bi bi-file-earmark-bar-graph"></i> Laporan</td>
                                    <td>Laporan bulanan Jan 2024</td>
                                    <td>1 hari</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- System Overview & Quick Stats -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
                <!-- System Overview -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Overview Sistem</h6>
                    </div>
                    <div class="card-body">
                        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--success); margin-bottom: 0.25rem;">Database Status</div>
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--success);">
                                    <i class="bi bi-check-circle-fill"></i> Online
                                </div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--info); margin-bottom: 0.25rem;">Server Uptime</div>
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">99.9%</div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--warning); margin-bottom: 0.25rem;">Storage Used</div>
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">2.3 GB / 10 GB</div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--primary); margin-bottom: 0.25rem;">Last Backup</div>
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">2 jam yang lalu</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-graph-up"></i> Quick Stats</h6>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; color: var(--primary); margin-bottom: 0.5rem;">
                                    <i class="bi bi-journal-text"></i>
                                </div>
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">245</div>
                                <div style="font-size: 0.85rem; color: var(--gray);">Total Logbook</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; color: var(--success); margin-bottom: 0.5rem;">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--success);">1.25K</div>
                                <div style="font-size: 0.85rem; color: var(--gray);">Total Presensi</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; color: var(--warning); margin-bottom: 0.5rem;">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--warning);">45</div>
                                <div style="font-size: 0.85rem; color: var(--gray);">Pengajuan Izin</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; color: var(--info); margin-bottom: 0.5rem;">
                                    <i class="bi bi-award"></i>
                                </div>
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--info);">180</div>
                                <div style="font-size: 0.85rem; color: var(--gray);">Penilaian</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notices -->
            <div class="card" style="margin-top: 2rem;">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-exclamation-diamond"></i> Penting</h6>
                </div>
                <div class="card-body">
                    <div style="border-left: 4px solid var(--info); background: #E8F6FF; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                        <strong style="color: var(--dark);">ℹ️ Maintenance System</strong>
                        <p style="margin: 0.5rem 0 0; font-size: 0.9rem; color: var(--gray);">Sistem akan melakukan maintenance pada hari Sabtu, 27 Januari 2024 pukul 02:00 - 04:00 WIB. Mohon maaf atas ketidaknyamanannya.</p>
                    </div>
                    <div style="border-left: 4px solid var(--warning); background: #FFF3E0; padding: 1rem; border-radius: 4px;">
                        <strong style="color: var(--dark);">⚠️ Backup Data</strong>
                        <p style="margin: 0.5rem 0 0; font-size: 0.9rem; color: var(--gray);">Jangan lupa untuk melakukan backup data secara berkala untuk menjaga keamanan informasi.</p>
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
        
        .btn-info {
            background: var(--info);
            transition: background 0.3s;
        }
        
        .btn-info:hover {
            background: var(--primary-dark);
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
            const isActive = sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // Toggle body scroll lock
            if (isActive) {
                document.body.classList.add('sidebar-open');
            } else {
                document.body.classList.remove('sidebar-open');
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar_overlay');
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            
            // Remove scroll lock from body
            document.body.classList.remove('sidebar-open');
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
