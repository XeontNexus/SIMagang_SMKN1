<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login_simple.php');
    exit;
}

$page_title = 'Logbook Mingguan';
$user_email = $_SESSION['user_email'] ?? 'Siswa';
$user_role = $_SESSION['user_role'] ?? 'siswa';

// Get current week info
$today = date('Y-m-d');
$year = date('Y');
$month = date('m');
$week = date('W');
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
                    <a href="logbook.php" class="sidebar-menu-link active" onclick="closeSidebar()">
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
            <!-- Top Navbar -->
            <div class="top-navbar">
                <button class="hamburger-btn" id="hamburger_btn" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div class="navbar-title">
                    <h1>Logbook Mingguan</h1>
                    <p>Laporan kegiatan magang setiap minggu</p>
                </div>
                <div class="navbar-actions">
                    <span style="color: var(--dark); font-size: 0.9rem;">
                        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($user_email); ?>
                    </span>
                </div>
            </div>

            <!-- Logbook Form -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-plus-circle"></i> Input Logbook Mingguan Baru</h6>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <!-- Week Selection -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Tahun</label>
                                <input type="number" class="form-control" name="year" value="<?php echo $year; ?>" min="2020" required>
                            </div>
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Bulan</label>
                                <select class="form-control" name="month" required>
                                    <?php for($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?php echo str_pad($m, 2, '0', STR_PAD_LEFT); ?>" <?php echo ($m == $month) ? 'selected' : ''; ?>>
                                            <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Minggu Ke</label>
                                <select class="form-control" name="minggu" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="1">Minggu 1</option>
                                    <option value="2">Minggu 2</option>
                                    <option value="3">Minggu 3</option>
                                    <option value="4">Minggu 4</option>
                                    <option value="5">Minggu 5</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Tempat PKL</label>
                                <input type="text" class="form-control" name="tempat_pkl" value="PT. Indonesia" readonly style="background: var(--light);">
                            </div>
                        </div>

                        <!-- Activity Details -->
                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label" style="font-weight: 600; color: var(--dark); display: flex; align-items: center; gap: 0.5rem;">
                                <i class="bi bi-clipboard-check"></i> Rencana Kegiatan
                            </label>
                            <textarea class="form-control" name="rencana_kegiatan" placeholder="Masukkan rencana kegiatan yang akan dilakukan minggu ini..." rows="4" required style="font-family: 'Poppins', sans-serif; padding: 0.75rem;"></textarea>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label" style="font-weight: 600; color: var(--dark); display: flex; align-items: center; gap: 0.5rem;">
                                <i class="bi bi-check-circle"></i> Hasil Kegiatan
                            </label>
                            <textarea class="form-control" name="hasil_kegiatan" placeholder="Masukkan hasil kegiatan yang sudah dijalankan..." rows="4" required style="font-family: 'Poppins', sans-serif; padding: 0.75rem;"></textarea>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label" style="font-weight: 600; color: var(--dark); display: flex; align-items: center; gap: 0.5rem;">
                                <i class="bi bi-exclamation-circle"></i> Hambatan
                            </label>
                            <textarea class="form-control" name="hambatan" placeholder="Masukkan kendala/hambatan yang dihadapi..." rows="3" required style="font-family: 'Poppins', sans-serif; padding: 0.75rem;"></textarea>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label" style="font-weight: 600; color: var(--dark); display: flex; align-items: center; gap: 0.5rem;">
                                <i class="bi bi-arrow-repeat"></i> Perbaikan
                            </label>
                            <textarea class="form-control" name="perbaikan" placeholder="Masukkan solusi/perbaikan untuk hambatan..." rows="3" required style="font-family: 'Poppins', sans-serif; padding: 0.75rem;"></textarea>
                        </div>

                        <!-- Evidence Upload -->
                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label" style="font-weight: 600; color: var(--dark); display: flex; align-items: center; gap: 0.5rem;">
                                <i class="bi bi-cloud-upload"></i> Unggah Evidence (Foto/Dokumen)
                            </label>
                            <div style="border: 2px dashed var(--primary); border-radius: 8px; padding: 2rem; text-align: center; cursor: pointer;" onclick="document.getElementById('file_evidence').click();">
                                <div style="color: var(--primary); font-size: 2rem; margin-bottom: 0.5rem;">
                                    <i class="bi bi-image"></i>
                                </div>
                                <p style="margin: 0; color: var(--dark); font-weight: 600;">Klik untuk upload atau drag & drop</p>
                                <small style="color: var(--gray);">Format: JPG, PNG, PDF, DOC, DOCX (Max 5MB)</small>
                            </div>
                            <input type="file" id="file_evidence" name="evidence" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" style="display: none;">
                            <div id="file_name" style="margin-top: 0.5rem; color: var(--success); font-weight: 600;"></div>
                        </div>

                        <!-- Submit Button -->
                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="bi bi-check-circle"></i> Simpan Logbook
                            </button>
                            <a href="dashboard.php" class="btn-secondary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Previous Logbooks -->
            <div class="card" style="margin-top: 2rem;">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> Logbook Sebelumnya</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" style="margin: 0;">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th>Rencana Kegiatan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Jan 2024 - Minggu 1</strong></td>
                                    <td>Mempelajari sistem database...</td>
                                    <td><span class="badge" style="background: var(--success); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">✓ Disetujui</span></td>
                                    <td>
                                        <button style="background: none; border: none; color: var(--primary); cursor: pointer;">
                                            <i class="bi bi-eye"></i> Lihat
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Jan 2024 - Minggu 2</strong></td>
                                    <td>Membuat ERD dan design database...</td>
                                    <td><span class="badge" style="background: var(--warning); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">⏳ Menunggu</span></td>
                                    <td>
                                        <button style="background: none; border: none; color: var(--primary); cursor: pointer;">
                                            <i class="bi bi-eye"></i> Lihat
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

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

        .form-label {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
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

        // File upload handler
        document.getElementById('file_evidence').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            document.getElementById('file_name').textContent = fileName ? '✓ ' + fileName : '';
        });
    </script>
</body>
</html>
