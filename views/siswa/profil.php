<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login_simple.php');
    exit;
}

$page_title = 'Profil Siswa';
$user_email = $_SESSION['user_email'] ?? 'Siswa';
$user_role = $_SESSION['user_role'] ?? 'siswa';

// Check if profile is complete (for first-time login warning)
$profile_complete = true; // This should be checked from database
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
                    <a href="profil.php" class="sidebar-menu-link active" onclick="closeSidebar()">
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
                    <h1>Profil Saya</h1>
                    <p>Kelola data profil dan informasi magang</p>
                </div>
                <div class="navbar-actions">
                    <span style="color: var(--dark); font-size: 0.9rem;">
                        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($user_email); ?>
                    </span>
                </div>
            </div>

            <!-- Warning for incomplete profile -->
            <?php if(!$profile_complete): ?>
            <div style="background: #FFF3E0; border-left: 4px solid var(--warning); padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 1.5rem; color: var(--warning);">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <strong style="color: var(--dark);">⚠️ Data Profil Belum Lengkap</strong>
                    <p style="margin: 0.25rem 0 0; font-size: 0.9rem; color: var(--gray);">Silakan lengkapi data profil Anda terlebih dahulu sebelum menggunakan sistem sepenuhnya.</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Profile Form -->
            <form method="POST" enctype="multipart/form-data">
                <!-- Personal Information -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-person"></i> Data Pribadi</h6>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                                <input type="text" class="form-control" name="nama" value="Ahmad Rizki Pratama" required>
                            </div>
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Email <span style="color: var(--danger);">*</span></label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user_email); ?>" readonly style="background: var(--light);">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Kelas <span style="color: var(--danger);">*</span></label>
                                <select class="form-control" name="kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <option value="X-1" selected>X-1</option>
                                    <option value="X-2">X-2</option>
                                    <option value="X-3">X-3</option>
                                    <option value="XI-1">XI-1</option>
                                    <option value="XI-2">XI-2</option>
                                    <option value="XI-3">XI-3</option>
                                    <option value="XII-1">XII-1</option>
                                    <option value="XII-2">XII-2</option>
                                    <option value="XII-3">XII-3</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Jurusan <span style="color: var(--danger);">*</span></label>
                                <select class="form-control" name="jurusan" required>
                                    <option value="">-- Pilih Jurusan --</option>
                                    <option value="Rekayasa Perangkat Lunak" selected>Rekayasa Perangkat Lunak</option>
                                    <option value="Teknik Komputer dan Jaringan">Teknik Komputer dan Jaringan</option>
                                    <option value="Multimedia">Multimedia</option>
                                    <option value="Sistem Informasi">Sistem Informasi</option>
                                    <option value="Perbankan dan Keuangan Digital">Perbankan dan Keuangan Digital</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">No. Induk Siswa <span style="color: var(--danger);">*</span></label>
                                <input type="text" class="form-control" name="nis" value="20240001" required>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Nomor Telepon <span style="color: var(--danger);">*</span></label>
                                <input type="tel" class="form-control" name="no_telp" placeholder="Contoh: 081234567890" value="081234567890" required>
                            </div>
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Alamat Rumah <span style="color: var(--danger);">*</span></label>
                                <input type="text" class="form-control" name="alamat" placeholder="Jalan, No., Kota, Provinsi" value="Jl. Merdeka No. 123, Jakarta" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Internship Information -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-briefcase"></i> Informasi Magang</h6>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Nama Tempat PKL <span style="color: var(--danger);">*</span></label>
                                <input type="text" class="form-control" name="nama_tempat_pkl" value="PT. Indonesia" required>
                            </div>
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Alamat Tempat PKL <span style="color: var(--danger);">*</span></label>
                                <input type="text" class="form-control" name="alamat_tempat_pkl" value="Jl. Sisingamangaraja No. 123, Jakarta" required>
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label" style="font-weight: 600; color: var(--dark);">Link Google Maps Tempat PKL</label>
                            <input type="url" class="form-control" name="link_gmap" placeholder="https://maps.google.com/..." value="https://maps.google.com/?q=PT+Indonesia+Jakarta">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Nama Pembimbing Lapangan <span style="color: var(--danger);">*</span></label>
                                <input type="text" class="form-control" name="nama_pembimbing_lapangan" value="Ibu Siti Nurhaliza" required>
                            </div>
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">No. Telepon Pembimbing <span style="color: var(--danger);">*</span></label>
                                <input type="tel" class="form-control" name="no_pembimbing" value="081987654321" required>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Guru Pembimbing <span style="color: var(--danger);">*</span></label>
                                <input type="text" class="form-control" name="guru_pembimbing" value="Bapak Ahmad Rizki" required>
                            </div>
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">No. Telepon Guru Pembimbing <span style="color: var(--danger);">*</span></label>
                                <input type="tel" class="form-control" name="no_guru" value="081111222333" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Picture -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-image"></i> Foto Profil</h6>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 2rem;">
                            <div style="text-align: center;">
                                <img id="preview_foto" src="https://via.placeholder.com/200x200?text=Foto+Profil" style="width: 200px; height: 200px; border-radius: 8px; object-fit: cover; border: 2px solid var(--light);">
                            </div>
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Unggah Foto Profil</label>
                                <div style="border: 2px dashed var(--primary); border-radius: 8px; padding: 2rem; text-align: center; cursor: pointer;" onclick="document.getElementById('file_foto').click();">
                                    <div style="color: var(--primary); font-size: 2rem; margin-bottom: 0.5rem;">
                                        <i class="bi bi-cloud-upload"></i>
                                    </div>
                                    <p style="margin: 0; color: var(--dark); font-weight: 600;">Klik untuk upload foto</p>
                                    <small style="color: var(--gray);">Format: JPG, PNG (Max 2MB)</small>
                                </div>
                                <input type="file" id="file_foto" name="foto" accept=".jpg,.jpeg,.png" style="display: none;">
                                <div id="file_foto_name" style="margin-top: 0.5rem; color: var(--success); font-weight: 600;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                    <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-check-circle"></i> Simpan Profil
                    </button>
                    <a href="dashboard.php" class="btn-secondary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                </div>
            </form>

            <!-- Account Information -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-shield-lock"></i> Informasi Akun</h6>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 0.5rem;">Email Terdaftar</label>
                            <div style="background: var(--light); padding: 0.75rem; border-radius: 6px; color: var(--dark);">
                                <?php echo htmlspecialchars($user_email); ?>
                            </div>
                        </div>
                        <div>
                            <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 0.5rem;">Status Akun</label>
                            <div style="background: var(--light); padding: 0.75rem; border-radius: 6px;">
                                <span class="badge" style="background: var(--success); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">✓ Aktif</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 1rem;">
                        <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 0.5rem;">Ubah Password</label>
                        <button type="button" class="btn-primary" style="padding: 0.5rem 1.5rem; border: none; border-radius: 6px; color: white; cursor: pointer; font-weight: 600;">
                            <i class="bi bi-lock"></i> Ubah Password
                        </button>
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

        .form-control {
            border: 1px solid var(--gray);
            border-radius: 6px;
            padding: 0.75rem;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
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

        // Photo preview
        document.getElementById('file_foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if(file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('preview_foto').src = event.target.result;
                };
                reader.readAsDataURL(file);
                document.getElementById('file_foto_name').textContent = '✓ ' + file.name;
            }
        });
    </script>
</body>
</html>
