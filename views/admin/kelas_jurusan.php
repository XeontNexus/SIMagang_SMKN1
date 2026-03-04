<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../auth/login_simple.php');
    exit;
}

$page_title = 'Manajemen Kelas & Jurusan';
$user_email = $_SESSION['user_email'] ?? 'Admin';

// Simple data storage (in production, this would be in a database)
$kelas_list = [
    ['id' => 1, 'nama' => 'X-1'],
    ['id' => 2, 'nama' => 'X-2'],
    ['id' => 3, 'nama' => 'X-3'],
    ['id' => 4, 'nama' => 'XI-1'],
    ['id' => 5, 'nama' => 'XI-2'],
    ['id' => 6, 'nama' => 'XI-3'],
    ['id' => 7, 'nama' => 'XII-1'],
    ['id' => 8, 'nama' => 'XII-2'],
    ['id' => 9, 'nama' => 'XII-3'],
];

$jurusan_list = [
    ['id' => 1, 'nama' => 'Rekayasa Perangkat Lunak', 'kode' => 'RPL'],
    ['id' => 2, 'nama' => 'Teknik Komputer dan Jaringan', 'kode' => 'TKJ'],
    ['id' => 3, 'nama' => 'Multimedia', 'kode' => 'MM'],
    ['id' => 4, 'nama' => 'Sistem Informasi', 'kode' => 'SI'],
    ['id' => 5, 'nama' => 'Perbankan dan Keuangan Digital', 'kode' => 'PKD'],
];
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
                    <a href="dashboard.php" class="sidebar-menu-link" onclick="closeSidebar()">
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
                    <a href="kelas_jurusan.php" class="sidebar-menu-link active" onclick="closeSidebar()">
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
                    <h1>Manajemen Kelas & Jurusan</h1>
                    <p>Kelola data kelas dan jurusan</p>
                </div>
                <div class="navbar-actions">
                    <span style="color: var(--dark); font-size: 0.9rem;">
                        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($user_email); ?>
                    </span>
                </div>
            </div>

            <!-- Tabs -->
            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--light);">
                <button style="background: none; border: none; padding: 1rem 0; color: var(--primary); font-weight: 600; border-bottom: 3px solid var(--primary); cursor: pointer;" onclick="switchTab('kelas')">
                    <i class="bi bi-collection"></i> Data Kelas
                </button>
                <button style="background: none; border: none; padding: 1rem 0; color: var(--gray); font-weight: 600; cursor: pointer;" onclick="switchTab('jurusan')">
                    <i class="bi bi-list-check"></i> Data Jurusan
                </button>
            </div>

            <!-- Kelas Tab -->
            <div id="tab_kelas" style="display: block;">
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h6 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Kelas Baru</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="add_kelas">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                                <div>
                                    <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 0.5rem;">Nama Kelas <span style="color: var(--danger);">*</span></label>
                                    <input type="text" class="form-control" name="nama_kelas" placeholder="Contoh: XII-1" required>
                                </div>
                                <div style="display: flex; align-items: flex-end;">
                                    <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer; width: 100%;">
                                        <i class="bi bi-plus-circle"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-list"></i> Daftar Kelas</h6>
                    </div>
                    <div class="card-body">
                        <table class="table" style="margin: 0;">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No.</th>
                                    <th>Nama Kelas</th>
                                    <th style="width: 150px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($kelas_list as $index => $kelas): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($kelas['nama']); ?>" style="border: 1px solid var(--gray); padding: 0.5rem; border-radius: 6px;">
                                    </td>
                                    <td style="text-align: center;">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="update_kelas">
                                            <input type="hidden" name="id" value="<?php echo $kelas['id']; ?>">
                                            <button type="submit" style="background: none; border: none; color: var(--success); cursor: pointer; margin-right: 0.5rem;">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_kelas">
                                            <input type="hidden" name="id" value="<?php echo $kelas['id']; ?>">
                                            <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer;" onclick="return confirm('Hapus kelas ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Jurusan Tab -->
            <div id="tab_jurusan" style="display: none;">
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h6 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Jurusan Baru</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="add_jurusan">
                            <div style="display: grid; grid-template-columns: 1fr 0.5fr 0.5fr; gap: 1rem; margin-bottom: 1rem;">
                                <div>
                                    <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 0.5rem;">Nama Jurusan <span style="color: var(--danger);">*</span></label>
                                    <input type="text" class="form-control" name="nama_jurusan" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                                </div>
                                <div>
                                    <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 0.5rem;">Kode <span style="color: var(--danger);">*</span></label>
                                    <input type="text" class="form-control" name="kode_jurusan" placeholder="RPL" required>
                                </div>
                                <div style="display: flex; align-items: flex-end;">
                                    <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer; width: 100%;">
                                        <i class="bi bi-plus-circle"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-list"></i> Daftar Jurusan</h6>
                    </div>
                    <div class="card-body">
                        <table class="table" style="margin: 0;">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No.</th>
                                    <th>Nama Jurusan</th>
                                    <th style="width: 100px;">Kode</th>
                                    <th style="width: 150px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($jurusan_list as $index => $jurusan): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($jurusan['nama']); ?>" style="border: 1px solid var(--gray); padding: 0.5rem; border-radius: 6px;">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($jurusan['kode']); ?>" style="border: 1px solid var(--gray); padding: 0.5rem; border-radius: 6px;">
                                    </td>
                                    <td style="text-align: center;">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="update_jurusan">
                                            <input type="hidden" name="id" value="<?php echo $jurusan['id']; ?>">
                                            <button type="submit" style="background: none; border: none; color: var(--success); cursor: pointer; margin-right: 0.5rem;">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_jurusan">
                                            <input type="hidden" name="id" value="<?php echo $jurusan['id']; ?>">
                                            <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer;" onclick="return confirm('Hapus jurusan ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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

        .form-control {
            border: 1px solid var(--gray);
            border-radius: 6px;
            padding: 0.75rem;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            transition: border 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .btn-primary {
            background: var(--primary);
            transition: background 0.3s;
        }
        
        .btn-primary:hover {
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

        function switchTab(tab) {
            // Hide all tabs
            document.getElementById('tab_kelas').style.display = 'none';
            document.getElementById('tab_jurusan').style.display = 'none';
            
            // Show selected tab
            document.getElementById('tab_' + tab).style.display = 'block';
            
            // Update button styles
            const buttons = document.querySelectorAll('button[onclick*="switchTab"]');
            buttons.forEach(btn => {
                btn.style.color = 'var(--gray)';
                btn.style.borderBottom = 'none';
            });
            event.target.closest('button').style.color = 'var(--primary)';
            event.target.closest('button').style.borderBottom = '3px solid var(--primary)';
        }
    </script>
</body>
</html>
