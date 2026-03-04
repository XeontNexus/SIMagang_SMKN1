<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login_simple.php');
    exit;
}

$page_title = 'Presensi';
$user_email = $_SESSION['user_email'] ?? 'Siswa';
$user_role = $_SESSION['user_role'] ?? 'siswa';

$today = date('Y-m-d');
$time_now = date('H:i');
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
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-mortarboard"></i> SIMagang</h3>
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="dashboard.php" class="sidebar-menu-link">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="logbook.php" class="sidebar-menu-link">
                        <i class="bi bi-journal-text"></i>
                        <span>Logbook</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="presensi.php" class="sidebar-menu-link active">
                        <i class="bi bi-calendar-check"></i>
                        <span>Presensi</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="pengajuan_izin.php" class="sidebar-menu-link">
                        <i class="bi bi-file-earmark-plus"></i>
                        <span>Ajukan Izin</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="profil.php" class="sidebar-menu-link">
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
                <div class="navbar-title">
                    <h1>Presensi Realtime</h1>
                    <p>Absen kehadiran harian magang</p>
                </div>
                <div class="navbar-actions">
                    <span style="color: var(--dark); font-size: 0.9rem;">
                        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($user_email); ?>
                    </span>
                </div>
            </div>

            <!-- Today's Attendance -->
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-calendar-event"></i> Presensi Hari Ini (<?php echo date('d F Y', strtotime($today)); ?>)</h6>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 0.5rem;">Jam Masuk Sekarang</label>
                            <div style="background: var(--light); padding: 1rem; border-radius: 8px; text-align: center;">
                                <div style="font-size: 2rem; font-weight: 700; color: var(--primary);" id="current_time">--:--</div>
                                <small style="color: var(--gray);">Waktu Real Time</small>
                            </div>
                        </div>
                        <div>
                            <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 0.5rem;">Status</label>
                            <div style="background: var(--light); padding: 1rem; border-radius: 8px; text-align: center;">
                                <div id="status_badge" style="font-size: 1.2rem; font-weight: 700; color: var(--warning);">
                                    <i class="bi bi-hourglass-split"></i> Belum Absen
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Type Selection -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 0.75rem;">Pilih Jenis Kehadiran</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                            <!-- Absen Biasa -->
                            <div style="border: 2px solid var(--gray); border-radius: 8px; padding: 1.5rem; text-align: center; cursor: pointer;" id="option_absen">
                                <div style="font-size: 2rem; color: var(--success); margin-bottom: 0.5rem;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <strong>Absen Biasa</strong>
                                <p style="font-size: 0.85rem; color: var(--gray); margin: 0.5rem 0 0;">Tanpa foto</p>
                            </div>

                            <!-- Absen Foto -->
                            <div style="border: 2px solid var(--gray); border-radius: 8px; padding: 1.5rem; text-align: center; cursor: pointer;" id="option_foto">
                                <div style="font-size: 2rem; color: var(--info); margin-bottom: 0.5rem;">
                                    <i class="bi bi-camera"></i>
                                </div>
                                <strong>Absen Foto</strong>
                                <p style="font-size: 0.85rem; color: var(--gray); margin: 0.5rem 0 0;">Dengan foto selfie</p>
                            </div>

                            <!-- Izin -->
                            <div style="border: 2px solid var(--gray); border-radius: 8px; padding: 1.5rem; text-align: center; cursor: pointer;" id="option_izin">
                                <div style="font-size: 2rem; color: var(--warning); margin-bottom: 0.5rem;">
                                    <i class="bi bi-exclamation-circle"></i>
                                </div>
                                <strong>Izin / Sakit</strong>
                                <p style="font-size: 0.85rem; color: var(--gray); margin: 0.5rem 0 0;">Dengan bukti</p>
                            </div>
                        </div>
                    </div>

                    <!-- Absen Biasa Form -->
                    <div id="form_absen" style="display: none;">
                        <form method="POST">
                            <input type="hidden" name="type" value="absen">
                            <div style="margin-bottom: 1rem;">
                                <label style="font-weight: 600; color: var(--dark);">Tempat Presensi</label>
                                <input type="text" class="form-control" value="PT. Indonesia" readonly style="background: var(--light);">
                            </div>
                            <div style="display: flex; gap: 1rem;">
                                <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer;">
                                    <i class="bi bi-check-circle"></i> Absen Sekarang
                                </button>
                                <button type="button" onclick="resetForm()" class="btn-secondary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer;">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Absen Foto Form -->
                    <div id="form_foto" style="display: none;">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="type" value="foto">
                            <div style="margin-bottom: 1rem;">
                                <label style="font-weight: 600; color: var(--dark);">Ambil Foto Selfie</label>
                                <div style="border: 2px dashed var(--primary); border-radius: 8px; padding: 2rem; text-align: center;">
                                    <video id="video" width="100%" height="300" style="border-radius: 8px; background: black; display: none;"></video>
                                    <canvas id="canvas" width="400" height="300" style="display: none;"></canvas>
                                    <img id="preview" src="" style="max-width: 100%; border-radius: 8px; display: none;">
                                    <div id="camera_placeholder" style="color: var(--primary); font-size: 3rem;">
                                        <i class="bi bi-camera"></i>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                                    <button type="button" onclick="startCamera()" class="btn-info" style="padding: 0.5rem 1rem; border: none; border-radius: 6px; color: white; cursor: pointer; flex: 1;">
                                        <i class="bi bi-camera-video"></i> Buka Kamera
                                    </button>
                                    <button type="button" id="btn_capture" onclick="capturePhoto()" class="btn-success" style="padding: 0.5rem 1rem; border: none; border-radius: 6px; color: white; cursor: pointer; flex: 1; display: none;">
                                        <i class="bi bi-camera-fill"></i> Ambil Foto
                                    </button>
                                </div>
                            </div>
                            <div style="display: flex; gap: 1rem;">
                                <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer;">
                                    <i class="bi bi-check-circle"></i> Absen Dengan Foto
                                </button>
                                <button type="button" onclick="resetForm()" class="btn-secondary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer;">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Izin Form -->
                    <div id="form_izin" style="display: none;">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="type" value="izin">
                            <div style="margin-bottom: 1rem;">
                                <label style="font-weight: 600; color: var(--dark);">Alasan Izin</label>
                                <select class="form-control" name="alasan_izin" required>
                                    <option value="">-- Pilih Alasan --</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="keperluan">Keperluan Mendesak</option>
                                    <option value="izin_orang_tua">Izin Orang Tua</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="font-weight: 600; color: var(--dark);">Keterangan</label>
                                <textarea class="form-control" name="keterangan_izin" placeholder="Jelaskan alasan izin..." rows="3" required></textarea>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="font-weight: 600; color: var(--dark);">Unggah Bukti (Foto/Dokumen)</label>
                                <div style="border: 2px dashed var(--primary); border-radius: 8px; padding: 2rem; text-align: center; cursor: pointer;" onclick="document.getElementById('file_izin').click();">
                                    <div style="color: var(--primary); font-size: 2rem; margin-bottom: 0.5rem;">
                                        <i class="bi bi-cloud-upload"></i>
                                    </div>
                                    <p style="margin: 0; color: var(--dark); font-weight: 600;">Klik untuk upload bukti</p>
                                    <small style="color: var(--gray);">Format: JPG, PNG, PDF (Max 5MB)</small>
                                </div>
                                <input type="file" id="file_izin" name="bukti_izin" accept=".jpg,.jpeg,.png,.pdf" style="display: none;" required>
                                <div id="file_izin_name" style="margin-top: 0.5rem; color: var(--success); font-weight: 600;"></div>
                            </div>
                            <div style="display: flex; gap: 1rem;">
                                <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer;">
                                    <i class="bi bi-check-circle"></i> Kirim Izin
                                </button>
                                <button type="button" onclick="resetForm()" class="btn-secondary" style="padding: 0.75rem 2rem; border: none; border-radius: 8px; color: white; cursor: pointer;">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Attendance History -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Presensi</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" style="margin: 0;">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>03-03-2024</td>
                                    <td>08:15</td>
                                    <td><span class="badge" style="background: var(--success); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">✓ Hadir</span></td>
                                    <td>Absen biasa</td>
                                </tr>
                                <tr>
                                    <td>02-03-2024</td>
                                    <td>08:42</td>
                                    <td><span class="badge" style="background: var(--warning); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">⏳ Terlambat</span></td>
                                    <td>Absen dengan foto</td>
                                </tr>
                                <tr>
                                    <td>01-03-2024</td>
                                    <td>--:--</td>
                                    <td><span class="badge" style="background: var(--info); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">ℹ️ Izin Sakit</span></td>
                                    <td>Dengan surat keterangan</td>
                                </tr>
                                <tr>
                                    <td>29-02-2024</td>
                                    <td>--:--</td>
                                    <td><span class="badge" style="background: var(--danger); color: white; padding: 0.4rem 0.8rem; border-radius: 4px;">✕ Alpha</span></td>
                                    <td>Tidak ada bukti izin</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary Stats -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-top: 2rem;">
                <div class="card" style="text-align: center;">
                    <div class="card-body">
                        <div style="font-size: 2rem; color: var(--success); margin-bottom: 0.5rem;">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--success);">18</div>
                        <div style="color: var(--gray); font-size: 0.85rem;">Hadir</div>
                    </div>
                </div>
                <div class="card" style="text-align: center;">
                    <div class="card-body">
                        <div style="font-size: 2rem; color: var(--warning); margin-bottom: 0.5rem;">
                            <i class="bi bi-exclamation-circle-fill"></i>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--warning);">2</div>
                        <div style="color: var(--gray); font-size: 0.85rem;">Izin</div>
                    </div>
                </div>
                <div class="card" style="text-align: center;">
                    <div class="card-body">
                        <div style="font-size: 2rem; color: var(--info); margin-bottom: 0.5rem;">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--info);">0</div>
                        <div style="color: var(--gray); font-size: 0.85rem;">Terlambat</div>
                    </div>
                </div>
                <div class="card" style="text-align: center;">
                    <div class="card-body">
                        <div style="font-size: 2rem; color: var(--danger); margin-bottom: 0.5rem;">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger);">0</div>
                        <div style="color: var(--gray); font-size: 0.85rem;">Alpha</div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <style>
        .btn-primary {
            background: var(--primary);
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        
        .btn-secondary {
            background: var(--secondary);
        }
        
        .btn-secondary:hover {
            background: #1A6BA5;
        }

        .btn-info {
            background: var(--info);
        }
        
        .btn-info:hover {
            background: var(--primary-dark);
        }

        .btn-success {
            background: var(--success);
        }
        
        .btn-success:hover {
            background: #229954;
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
        // Update current time
        function updateTime() {
            const now = new Date();
            document.getElementById('current_time').textContent = 
                String(now.getHours()).padStart(2, '0') + ':' + 
                String(now.getMinutes()).padStart(2, '0');
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Form handling
        document.getElementById('option_absen').addEventListener('click', function() {
            resetForm();
            document.getElementById('form_absen').style.display = 'block';
        });

        document.getElementById('option_foto').addEventListener('click', function() {
            resetForm();
            document.getElementById('form_foto').style.display = 'block';
        });

        document.getElementById('option_izin').addEventListener('click', function() {
            resetForm();
            document.getElementById('form_izin').style.display = 'block';
        });

        function resetForm() {
            document.getElementById('form_absen').style.display = 'none';
            document.getElementById('form_foto').style.display = 'none';
            document.getElementById('form_izin').style.display = 'none';
        }

        // Camera handling
        let stream;
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                document.getElementById('video').srcObject = stream;
                document.getElementById('video').style.display = 'block';
                document.getElementById('camera_placeholder').style.display = 'none';
                document.getElementById('btn_capture').style.display = 'inline-block';
            } catch(err) {
                alert('Tidak dapat mengakses kamera: ' + err.message);
            }
        }

        function capturePhoto() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const preview = document.getElementById('preview');
            preview.src = canvas.toDataURL('image/jpeg');
            preview.style.display = 'block';
            
            video.style.display = 'none';
            document.getElementById('btn_capture').style.display = 'none';
            
            if(stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        }

        // File upload handler
        document.getElementById('file_izin').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            document.getElementById('file_izin_name').textContent = fileName ? '✓ ' + fileName : '';
        });
    </script>
</body>
</html>
