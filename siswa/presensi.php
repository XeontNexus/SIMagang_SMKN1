<?php require_once '../config/database.php'; ?>
<?php requireRole('siswa'); ?>
<?php
$db = getDB();
$siswa_id = $_SESSION['siswa_id'] ?? 0;
$user_id = $_SESSION['user_id'] ?? 0;

// Get active internship
$magang_stmt = $db->prepare("
    SELECT pm.*, pp.judul, u.nama as nama_dudi, d.nama_perusahaan
    FROM pendaftaran_magang pm
    JOIN penawaran_magang pp ON pm.penawaran_id = pp.id
    JOIN dudi d ON pp.dudi_id = d.id
    JOIN users u ON d.user_id = u.id
    WHERE pm.siswa_id = ? AND pm.status IN ('diterima_dudi', 'selesai')
    ORDER BY pm.tanggal_daftar DESC LIMIT 1
");
$magang_stmt->execute([$siswa_id]);
$active_magang = $magang_stmt->fetch();

if (!$active_magang) {
    header('Location: dashboard.php');
    exit;
}

$pendaftaran_id = $active_magang['id'];
$today = date('Y-m-d');
$error = '';
$success = '';

// Handle presensi submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'presensi') {
    // Check if already submitted today
    $check_stmt = $db->prepare("SELECT id FROM presensi WHERE pendaftaran_id = ? AND tanggal = ?");
    $check_stmt->execute([$pendaftaran_id, $today]);
    
    if (!$check_stmt->fetch()) {
        $status = $_POST['status'] ?? 'hadir';
        $jam_masuk = date('H:i:s');
        $jam_keluar = null;
        $keterangan = $_POST['keterangan'] ?? null;
        
        $insert_stmt = $db->prepare("
            INSERT INTO presensi (pendaftaran_id, tanggal, jam_masuk, jam_keluar, status, keterangan)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        if ($insert_stmt->execute([$pendaftaran_id, $today, $jam_masuk, $jam_keluar, $status, $keterangan])) {
            $success = '✓ Presensi berhasil dicatat!';
        } else {
            $error = '✗ Gagal mencatat presensi';
        }
    } else {
        $error = '✗ Anda sudah melakukan presensi hari ini';
    }
}

// Get today's presensi
$today_stmt = $db->prepare("SELECT * FROM presensi WHERE pendaftaran_id = ? AND tanggal = ?");
$today_stmt->execute([$pendaftaran_id, $today]);
$presensi_today = $today_stmt->fetch();

// Get statistics
$stats_stmt = $db->prepare("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
        SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin,
        SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as sakit,
        SUM(CASE WHEN status = 'alfa' THEN 1 ELSE 0 END) as alfa
    FROM presensi WHERE pendaftaran_id = ?
");
$stats_stmt->execute([$pendaftaran_id]);
$stats = $stats_stmt->fetch();

// Pagination
$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Count total pages
$count_stmt = $db->prepare("SELECT COUNT(*) as total FROM presensi WHERE pendaftaran_id = ?");
$count_stmt->execute([$pendaftaran_id]);
$count_result = $count_stmt->fetch();
$total_records = $count_result['total'];
$total_pages = ceil($total_records / $limit);

// Get presensi history
$history_stmt = $db->prepare("
    SELECT * FROM presensi
    WHERE pendaftaran_id = ?
    ORDER BY tanggal DESC
    LIMIT ? OFFSET ?
");
$history_stmt->execute([$pendaftaran_id, $limit, $offset]);
$presensi_list = $history_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi - SIMagang SMK N1</title>
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
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
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
            <a href="presensi.php" class="flex items-center gap-3 px-4 py-3 bg-blue-50 text-blue-600 rounded-lg font-medium">
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
                    <h1 class="text-xl font-semibold text-gray-900">Presensi</h1>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600"><?= $_SESSION['nama'] ?? 'User' ?></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-4 sm:p-6">
            <!-- Messages -->
            <?php if ($error): ?>
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <p class="text-red-700"><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                    <p class="text-green-700"><?= htmlspecialchars($success) ?></p>
                </div>
            <?php endif; ?>

            <!-- Presensi Form & Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                <!-- Input Form -->
                <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Presensi Hari Ini</h2>
                    <p class="text-sm text-gray-600 mb-4"><?= date('d F Y', strtotime($today)) ?></p>

                    <?php if ($presensi_today): ?>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-2">Status Presensi</p>
                            <p class="text-xl font-bold text-green-600 mb-3"><?= ucfirst($presensi_today['status']) ?></p>
                            <div class="space-y-2 text-sm text-gray-600">
                                <p><strong>Jam Masuk:</strong> <?= $presensi_today['jam_masuk'] ?></p>
                                <?php if ($presensi_today['jam_keluar']): ?>
                                    <p><strong>Jam Keluar:</strong> <?= $presensi_today['jam_keluar'] ?></p>
                                <?php endif; ?>
                                <?php if ($presensi_today['keterangan']): ?>
                                    <p><strong>Keterangan:</strong> <?= htmlspecialchars($presensi_today['keterangan']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="presensi">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="hadir">Hadir</option>
                                    <option value="izin">Izin</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="alfa">Alfa</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                                <textarea name="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Opsional"></textarea>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                Presensi Sekarang
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik Presensi</h2>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-blue-50 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-600 mb-1">Total Hari</p>
                            <p class="text-2xl font-bold text-blue-600"><?= $stats['total'] ?? 0 ?></p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-600 mb-1">Hadir</p>
                            <p class="text-2xl font-bold text-green-600"><?= $stats['hadir'] ?? 0 ?></p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-600 mb-1">Izin</p>
                            <p class="text-2xl font-bold text-yellow-600"><?= $stats['izin'] ?? 0 ?></p>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-600 mb-1">Sakit</p>
                            <p class="text-2xl font-bold text-orange-600"><?= $stats['sakit'] ?? 0 ?></p>
                        </div>
                        <div class="col-span-2 bg-red-50 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-600 mb-1">Alfa</p>
                            <p class="text-2xl font-bold text-red-600"><?= $stats['alfa'] ?? 0 ?></p>
                        </div>
                    </div>

                    <?php if ($stats['total'] > 0): ?>
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-xs text-gray-600 mb-2">Persentase Kehadiran</p>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: <?= round(($stats['hadir'] / $stats['total']) * 100) ?>%"></div>
                            </div>
                            <p class="text-sm font-semibold text-gray-700 mt-2"><?= round(($stats['hadir'] / $stats['total']) * 100) ?>%</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- History Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-4 sm:p-6 border-b">
                    <h2 class="text-lg font-semibold text-gray-900">Riwayat Presensi</h2>
                    <p class="text-sm text-gray-600 mt-1">Total: <?= $total_records ?> hari</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Jam Masuk</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Jam Keluar</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase hidden sm:table-cell">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php if (count($presensi_list) > 0): ?>
                                <?php foreach ($presensi_list as $p): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900"><?= date('d M Y', strtotime($p['tanggal'])) ?></td>
                                        <td class="px-4 sm:px-6 py-4">
                                            <span class="inline-block px-2.5 py-1.5 rounded-lg text-xs font-semibold <?php
                                                match($p['status']) {
                                                    'hadir' => print('bg-green-100 text-green-800'),
                                                    'izin' => print('bg-yellow-100 text-yellow-800'),
                                                    'sakit' => print('bg-orange-100 text-orange-800'),
                                                    'alfa' => print('bg-red-100 text-red-800'),
                                                    default => print('bg-gray-100 text-gray-800')
                                                }
                                            ?>">
                                                <?= ucfirst($p['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-600"><?= $p['jam_masuk'] ?? '-' ?></td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-600"><?= $p['jam_keluar'] ?? '-' ?></td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden sm:table-cell"><?= htmlspecialchars($p['keterangan'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-4 sm:px-6 py-8 text-center text-gray-500">
                                        Belum ada data presensi
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="px-4 sm:px-6 py-4 border-t flex items-center justify-between">
                        <span class="text-sm text-gray-600">Halaman <?= $page ?> dari <?= $total_pages ?></span>
                        <div class="flex gap-2">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?= $page - 1 ?>" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">← Prev</a>
                            <?php endif; ?>
                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?= $page + 1 ?>" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Next →</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>