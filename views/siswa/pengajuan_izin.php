<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login_simple.php');
    exit;
}

$page_title = 'Ajukan Izin / Surat';
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
                    <a href="pengajuan_izin.php" class="sidebar-menu-link active" onclick="closeSidebar()">
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
                    <h1>Ajukan Izin / Surat</h1>
                    <p>Pengajuan surat keterangan selama magang</p>
                </div>
                <div class="navbar-actions">
                    <span style="color: var(--dark); font-size: 0.9rem;">
                        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($user_email); ?>
                    </span>
                </div>
            </div>

            <!-- Letter Type Selection -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <!-- Surat Pengantar -->
                <div class="card" style="cursor: pointer; transition: all 0.3s;" onclick="selectLetter('pengantar')">
                    <div class="card-body" style="text-align: center;">
                        <div style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </div>
                        <h6 style="margin: 0 0 0.5rem; color: var(--dark);">Surat Pengantar</h6>
                        <p style="font-size: 0.85rem; color: var(--gray); margin: 0;">Surat keterangan siswa aktif dari SMKN1 Perhentian Raja</p>
                    </div>
                </div>

                <!-- Surat Nilai -->
                <div class="card" style="cursor: pointer; transition: all 0.3s;" onclick="selectLetter('nilai')">
                    <div class="card-body" style="text-align: center;">
                        <div style="font-size: 3rem; color: var(--success); margin-bottom: 1rem;">
                            <i class="bi bi-file-earmark-spreadsheet"></i>
                        </div>
                        <h6 style="margin: 0 0 0.5rem; color: var(--dark);">Surat Nilai</h6>
                        <p style="font-size: 0.85rem; color: var(--gray); margin: 0;">Meminta nilai/rapor dari Admin</p>
                    </div>
                </div>

                <!-- Surat Bukti Selesai -->
                <div class="card" style="cursor: pointer; transition: all 0.3s;" onclick="selectLetter('selesai')">
                    <div class="card-body" style="text-align: center;">
                        <div style="font-size: 3rem; color: var(--info); margin-bottom: 1rem;">
                            <i class="bi bi-file-earmark-check"></i>
                        </div>
                        <h6 style="margin: 0 0 0.5rem; color: var(--dark);">Bukti Selesai Magang</h6>
                        <p style="font-size: 0.85rem; color: var(--gray); margin: 0;">Surat keterangan selesai melakukan magang</p>
                    </div>
                </div>
            </div>

            <!-- Letter Form -->
            <div id="letter_form" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-pencil-square"></i> <span id="form_title">Form Pengajuan Surat</span></h6>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" id="letter_type" name="letter_type" value="">

                            <!-- Personal Information -->
                            <div style="background: var(--light); padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                                <h6 style="color: var(--dark); font-weight: 600; margin-bottom: 1rem;">
                                    <i class="bi bi-person"></i> Data Pribadi
                                </h6>
                                
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                                    <div>
                                        <label class="form-label" style="font-weight: 600; color: var(--dark);">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="nama" value="Ahmad Rizki Pratama" required>
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-weight: 600; color: var(--dark);">Kelas</label>
                                        <input type="text" class="form-control" name="kelas" value="XII RPL 1" required>
                                    </div>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <label class="form-label" style="font-weight: 600; color: var(--dark);">Jurusan</label>
                                        <input type="text" class="form-control" name="jurusan" value="Rekayasa Perangkat Lunak" required>
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-weight: 600; color: var(--dark);">No. Induk Siswa</label>
                                        <input type="text" class="form-control" name="nis" value="20240001" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Letter Specific Fields -->
                            <div id="pengantar_fields" style="display: none;">
                                <div style="margin-bottom: 1.5rem;">
                                    <label class="form-label" style="font-weight: 600; color: var(--dark);">Tujuan Surat</label>
                                    <input type="text" class="form-control" name="tujuan" placeholder="Contoh: Orang Tua / Instansi Lain" required>
                                </div>
                            </div>

                            <div id="nilai_fields" style="display: none;">
                                <div style="margin-bottom: 1.5rem;">
                                    <label class="form-label" style="font-weight: 600; color: var(--dark);">Keterangan Tambahan</label>
                                    <textarea class="form-control" name="keterangan" placeholder="Jelaskan kebutuhan surat nilai..." rows="3" required></textarea>
                                </div>
                            </div>

                            <div id="selesai_fields" style="display: none;">
                                <div style="margin-bottom: 1.5rem;">
                                    <label class="form-label" style="font-weight: 600; color: var(--dark);">Alamat Tempat Magang</label>
                                    <input type="text" class="form-control" name="alamat_magang" value="Jl. Sisingamangaraja No. 123, Jakarta" required>
                                </div>
                            </div>

                            <!-- Alasan Umum -->
                            <div style="margin-bottom: 1.5rem;">
                                <label class="form-label" style="font-weight: 600; color: var(--dark);">Alasan Pengajuan</label>
                                <textarea class="form-control" name="alasan" placeholder="Jelaskan alasan pengajuan surat ini..." rows="3" required></textarea>
                            </div>

                            <!-- Submit -->
                            <div style="display: flex; gap: 1rem;">
                                <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="bi bi-send"></i> Kirim Pengajuan
                                </button>
                                <button type="button" onclick="resetLetterForm()" class="btn-secondary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="bi bi-x-circle"></i> Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Previous Requests -->
            <div class="card" style="margin-top: 2rem;">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-history"></i> Riwayat Pengajuan</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" style="margin: 0;">
                            <thead>
                                <tr>
                                    <th>Jenis Surat</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Surat Pengantar</strong></td>
                                    <td>28 Feb 2024</td>
                                    <td><span class="badge" style="background: var(--success); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">✓ Disetujui</span></td>
                                    <td>
                                        <button style="background: none; border: none; color: var(--primary); cursor: pointer;">
                                            <i class="bi bi-download"></i> Download
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Surat Nilai</strong></td>
                                    <td>22 Feb 2024</td>
                                    <td><span class="badge" style="background: var(--warning); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">⏳ Menunggu</span></td>
                                    <td>
                                        <button style="background: none; border: none; color: var(--gray); cursor: pointer;">
                                            <i class="bi bi-lock"></i> Belum Selesai
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Bukti Selesai Magang</strong></td>
                                    <td>15 Feb 2024</td>
                                    <td><span class="badge" style="background: var(--success); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">✓ Disetujui</span></td>
                                    <td>
                                        <button style="background: none; border: none; color: var(--primary); cursor: pointer;">
                                            <i class="bi bi-download"></i> Download
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

        .card {
            cursor: auto;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
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

        function selectLetter(type) {
            document.getElementById('letter_type').value = type;
            
            // Hide all fields
            document.getElementById('pengantar_fields').style.display = 'none';
            document.getElementById('nilai_fields').style.display = 'none';
            document.getElementById('selesai_fields').style.display = 'none';

            // Show appropriate fields
            if(type === 'pengantar') {
                document.getElementById('form_title').textContent = 'Surat Pengantar Siswa Aktif';
                document.getElementById('pengantar_fields').style.display = 'block';
            } else if(type === 'nilai') {
                document.getElementById('form_title').textContent = 'Permintaan Surat Nilai';
                document.getElementById('nilai_fields').style.display = 'block';
            } else if(type === 'selesai') {
                document.getElementById('form_title').textContent = 'Bukti Selesai Magang';
                document.getElementById('selesai_fields').style.display = 'block';
            }

            document.getElementById('letter_form').style.display = 'block';
            document.getElementById('letter_form').scrollIntoView({ behavior: 'smooth' });
        }

        function resetLetterForm() {
            document.getElementById('letter_form').style.display = 'none';
            document.getElementById('letter_type').value = '';
        }
    </script>
</body>
</html>
