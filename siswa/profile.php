<?php require_once '../config/database.php'; ?>
<?php requireRole('siswa'); ?>
<?php
$db = getDB();
$siswa_id = $_SESSION['siswa_id'] ?? 0;
$user_id = $_SESSION['user_id'] ?? 0;

// Get siswa profile
$siswa_stmt = $db->prepare("
    SELECT s.*, k.nama as kelas, j.nama as jurusan, u.nama, u.email
    FROM siswa s
    LEFT JOIN kelas k ON s.kelas_id = k.id
    LEFT JOIN jurusan j ON k.jurusan_id = j.id
    JOIN users u ON s.user_id = u.id
    WHERE s.id = ?
");
$siswa_stmt->execute([$siswa_id]);
$siswa = $siswa_stmt->fetch();

// Handle profile update
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $tempat_lahir = $_POST['tempat_lahir'] ?? '';
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $no_hp = $_POST['no_hp'] ?? '';
    $nik = $_POST['nik'] ?? '';
    $nama_orang_tua = $_POST['nama_orang_tua'] ?? '';
    $nama_wali = $_POST['nama_wali'] ?? '';
    $no_telpon_wali = $_POST['no_telpon_wali'] ?? '';

    // Validate required fields
    if (empty($nama_lengkap) || empty($tempat_lahir) || empty($tanggal_lahir) || 
        empty($jenis_kelamin) || empty($alamat) || empty($no_hp)) {
        $error = "⚠️ Harap lengkapi semua data yang bertanda bintang (*)";
    } else {
        try {
            // Update users table
            $update_user = $db->prepare("UPDATE users SET nama = ? WHERE id = ?");
            $update_user->execute([$nama_lengkap, $user_id]);

            // Update siswa table
            $update_siswa = $db->prepare("
                UPDATE siswa SET 
                    nama_lengkap = ?, 
                    tempat_lahir = ?, 
                    tanggal_lahir = ?, 
                    jenis_kelamin = ?, 
                    alamat = ?, 
                    no_hp = ?, 
                    nik = ?, 
                    nama_orang_tua = ?, 
                    nama_wali = ?,
                    no_telpon_wali = ?
                WHERE id = ?
            ");
            $update_siswa->execute([
                $nama_lengkap, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, 
                $alamat, $no_hp, $nik, $nama_orang_tua, $nama_wali, $no_telpon_wali, $siswa_id
            ]);

            $success = "✓ Profil berhasil diperbarui!";
            
            // Refresh siswa data
            $siswa_stmt->execute([$siswa_id]);
            $siswa = $siswa_stmt->fetch();
            
            // If was redirected from dashboard, redirect back
            if (isset($_GET['wajib'])) {
                header('Location: dashboard.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = "✗ Terjadi kesalahan: " . $e->getMessage();
        }
    }
}

$wajib_isi = isset($_GET['wajib']) ? true : false;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Siswa - SIMagang SMK N1</title>
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
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 bg-blue-50 text-blue-600 rounded-lg font-medium">
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
                    <h1 class="text-xl font-semibold text-gray-900">Profil Siswa</h1>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600"><?= $_SESSION['nama'] ?? 'User' ?></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-4 sm:p-6">
            <!-- Warning Message -->
            <?php if ($wajib_isi): ?>
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                    <p class="text-yellow-800 font-semibold">⚠️ Wajib Isi Data Diri</p>
                    <p class="text-yellow-700 text-sm mt-1">Lengkapi semua data diri Anda sebelum melanjutkan. Data ini diperlukan untuk proses magang.</p>
                </div>
            <?php endif; ?>

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

            <!-- Basic Info Card -->
            <div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gray-50 p-3 rounded">
                        <p class="text-xs font-semibold text-gray-600 uppercase">NISN</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1"><?= htmlspecialchars($siswa['nisn'] ?? '-') ?></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <p class="text-xs font-semibold text-gray-600 uppercase">Jurusan</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1"><?= htmlspecialchars($siswa['jurusan'] ?? '-') ?></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <p class="text-xs font-semibold text-gray-600 uppercase">Kelas</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1"><?= htmlspecialchars($siswa['kelas'] ?? '-') ?></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <p class="text-xs font-semibold text-gray-600 uppercase">Email</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1"><?= htmlspecialchars($siswa['email'] ?? '-') ?></p>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <form method="POST" class="bg-white rounded-lg shadow p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Edit Data Pribadi</h2>

                <!-- Nama Lengkap -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($siswa['nama_lengkap'] ?? $siswa['nama'] ?? '') ?>" 
                           required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan nama lengkap Anda">
                </div>

                <!-- 2 Columns Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="tempat_lahir" value="<?= htmlspecialchars($siswa['tempat_lahir'] ?? '') ?>" 
                               required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Contoh: Jakarta">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($siswa['tanggal_lahir'] ?? '') ?>" 
                               required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kelamin" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" <?= ($siswa['jenis_kelamin'] === 'L') ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= ($siswa['jenis_kelamin'] === 'P') ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            No. HP/WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="no_hp" value="<?= htmlspecialchars($siswa['no_hp'] ?? '') ?>" 
                               required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Contoh: 08123456789">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            NIK <span class="text-gray-500 font-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="nik" value="<?= htmlspecialchars($siswa['nik'] ?? '') ?>" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="16 digit NIK">
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Masukkan alamat lengkap Anda"><?= htmlspecialchars($siswa['alamat'] ?? '') ?></textarea>
                </div>

                <!-- Parent/Guardian Section -->
                <div class="border-t pt-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Orang Tua/Wali</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Orang Tua <span class="text-gray-500 font-normal">(Opsional)</span>
                            </label>
                            <input type="text" name="nama_orang_tua" value="<?= htmlspecialchars($siswa['nama_orang_tua'] ?? '') ?>" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Wali <span class="text-gray-500 font-normal">(Opsional)</span>
                            </label>
                            <input type="text" name="nama_wali" value="<?= htmlspecialchars($siswa['nama_wali'] ?? '') ?>" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                No. Telpon Wali <span class="text-gray-500 font-normal">(Opsional)</span>
                            </label>
                            <input type="tel" name="no_telpon_wali" value="<?= htmlspecialchars($siswa['no_telpon_wali'] ?? '') ?>" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Contoh: 08123456789">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        💾 Simpan Perubahan
                    </button>
                    <?php if (!$wajib_isi): ?>
                        <a href="dashboard.php" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                            Batal
                        </a>
                    <?php endif; ?>
                </div>

                <p class="text-xs text-gray-500 mt-4">
                    <span class="text-red-500">*</span> = Wajib diisi
                </p>
            </form>
        </main>
    </div>
</body>
</html>