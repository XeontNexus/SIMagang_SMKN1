<?php require_once '../config/database.php'; ?>
<?php requireRole('siswa'); ?>
<?php
$db = getDB();
$siswa_id = $_SESSION['siswa_id'] ?? 0;
$user_id = $_SESSION['user_id'] ?? 0;

// Check if profile is complete
$profile_check = $db->prepare("SELECT * FROM siswa WHERE user_id = ? AND id = ?");
$profile_check->execute([$user_id, $siswa_id]);
$profile = $profile_check->fetch();

// Redirect to profile if data incomplete
if (!$profile || empty($profile['tempat_lahir']) || empty($profile['tanggal_lahir'])) {
    header('Location: profile.php?wajib=1');
    exit;
}

// Get current internship
$magang = $db->prepare("
    SELECT pm.*, pp.judul, pp.deskripsi, u.nama as nama_dudi, d.nama_perusahaan, d.alamat_perusahaan
    FROM pendaftaran_magang pm
    JOIN penawaran_magang pp ON pm.penawaran_id = pp.id
    JOIN dudi d ON pp.dudi_id = d.id
    JOIN users u ON d.user_id = u.id
    WHERE pm.siswa_id = ? AND pm.status IN ('diterima_dudi', 'selesai')
    ORDER BY pm.tanggal_daftar DESC LIMIT 1
");
$magang->execute([$siswa_id]);
$active_magang = $magang->fetch();

if ($active_magang) {
    // Get stats for today
    $today = date('Y-m-d');
    $presensi_today = $db->prepare("SELECT * FROM presensi WHERE pendaftaran_id = ? AND tanggal = ?");
    $presensi_today->execute([$active_magang['id'], $today]);
    $presensi_hari_ini = $presensi_today->fetch();

    $logbook_today = $db->prepare("SELECT * FROM logbook WHERE pendaftaran_id = ? AND tanggal = ?");
    $logbook_today->execute([$active_magang['id'], $today]);
    $logbook_hari_ini = $logbook_today->fetch();

    // Get stats
    $total_presensi = $db->prepare("SELECT COUNT(*) FROM presensi WHERE pendaftaran_id = ? AND status = 'hadir'");
    $total_presensi->execute([$active_magang['id']]);
    $total_hadir = $total_presensi->fetchColumn();

    $total_logbook = $db->prepare("SELECT COUNT(*) FROM logbook WHERE pendaftaran_id = ? AND status = 'diverifikasi'");
    $total_logbook->execute([$active_magang['id']]);
    $total_logbook_verified = $total_logbook->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - SIMagang SMK N1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 lg:translate-x-0"
           :class="{ '-translate-x-full': !sidebarOpen }">
        <div class="flex items-center gap-3 px-6 py-4 border-b">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="font-bold text-gray-800">SIMagang</span>
        </div>
        
        <nav class="p-4 space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 bg-blue-50 text-blue-600 rounded-lg font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Dashboard
            </a>
            <a href="promosi.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Data Magang
            </a>
            <a href="presensi.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Presensi
            </a>
            <a href="logbook.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Logbook
            </a>
            <a href="ajukan_magang.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                Ajukan Magang
            </a>
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profile
            </a>
        </nav>
        
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t">
            <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors w-full text-left">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-64">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-900">Dashboard Siswa</h1>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600"><?= $_SESSION['nama'] ?? 'User' ?></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-4 sm:p-6">
            <?php if ($active_magang): ?>
                <!-- Hero Section -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-4 sm:p-6 text-white mb-6">
                    <h2 class="text-2xl sm:text-3xl font-bold"><?= htmlspecialchars($active_magang['judul']) ?></h2>
                    <p class="text-blue-100 mt-2"><?= htmlspecialchars($active_magang['nama_perusahaan']) ?></p>
                    <p class="text-sm text-blue-200 mt-1">📍 <?= htmlspecialchars($active_magang['alamat_perusahaan']) ?></p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Status Magang</p>
                                <p class="text-lg font-bold text-gray-900 capitalize"><?= str_replace('_', ' ', $active_magang['status']) ?></p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Presensi Hari Ini</p>
                                <p class="text-lg font-bold" style="color: <?= $presensi_hari_ini ? '#16a34a' : '#ef4444' ?>">
                                    <?= $presensi_hari_ini ? '✓ Sudah' : '✗ Belum' ?>
                                </p>
                            </div>
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: <?= $presensi_hari_ini ? '#dcfce7' : '#fee2e2' ?>">
                                <svg class="w-6 h-6" style="color: <?= $presensi_hari_ini ? '#16a34a' : '#ef4444' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Kehadiran</p>
                                <p class="text-lg font-bold text-gray-900"><?= $total_hadir ?> hari</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Logbook Diverifikasi</p>
                                <p class="text-lg font-bold text-gray-900"><?= $total_logbook_verified ?? 0 ?></p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="presensi.php" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900">Input Presensi</h3>
                                <p class="text-sm text-gray-600 mt-1">Catat kehadiran hari ini</p>
                            </div>
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                    
                    <a href="logbook.php" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900">Input Logbook</h3>
                                <p class="text-sm text-gray-600 mt-1">Tulis laporan harian Anda</p>
                            </div>
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                </div>

            <?php else: ?>
                <!-- No Internship Message -->
                <div class="bg-white rounded-lg shadow p-8 sm:p-12 text-center">
                    <div class="mb-6">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Belum Memiliki Magang</h2>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">Anda belum terdaftar di mana pun. Mulai petualangan magang Anda dengan melihat penawaran yang tersedia dari mitra industri kami.</p>
                    <a href="promosi.php" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        Lihat Penawaran Magang →
                    </a>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
