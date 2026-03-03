-- Database SIMagang
CREATE DATABASE IF NOT EXISTS simagang;
USE simagang;

-- Tabel Users (untuk login)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('siswa', 'guru', 'dudi', 'admin') NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Siswa
CREATE TABLE siswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    nis VARCHAR(20) UNIQUE NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    kelas VARCHAR(20) NOT NULL,
    jurusan VARCHAR(50) NOT NULL,
    no_hp VARCHAR(15),
    alamat TEXT,
    foto_profil VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel Guru Pembimbing
CREATE TABLE guru (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    nip VARCHAR(20) UNIQUE NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    jurusan VARCHAR(50) NOT NULL,
    no_hp VARCHAR(15),
    email VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel Dudi (Dunia Usaha/Dunia Industri)
CREATE TABLE dudi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    nama_perusahaan VARCHAR(100) NOT NULL,
    bidang_usaha VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    no_telp VARCHAR(15) NOT NULL,
    email_perusahaan VARCHAR(100),
    nama_pembimbing VARCHAR(100),
    jabatan_pembimbing VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel Tempat Magang
CREATE TABLE tempat_magang (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dudi_id INT NOT NULL,
    nama_tempat VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    kuota INT DEFAULT 0,
    status ENUM('available', 'full', 'inactive') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dudi_id) REFERENCES dudi(id) ON DELETE CASCADE
);

-- Tabel Penempatan Siswa
CREATE TABLE penempatan_siswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    siswa_id INT NOT NULL,
    tempat_magang_id INT NOT NULL,
    guru_id INT NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    status ENUM('active', 'completed', 'terminated') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (tempat_magang_id) REFERENCES tempat_magang(id) ON DELETE CASCADE,
    FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE
);

-- Tabel Logbook
CREATE TABLE logbook (
    id INT PRIMARY KEY AUTO_INCREMENT,
    siswa_id INT NOT NULL,
    tanggal DATE NOT NULL,
    kegiatan TEXT NOT NULL,
    pembelajaran TEXT,
    kendala TEXT,
    status ENUM('draft', 'submitted', 'approved', 'rejected') DEFAULT 'draft',
    catatan_guru TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
);

-- Tabel Presensi
CREATE TABLE presensi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    siswa_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jam_masuk TIME,
    jam_keluar TIME,
    status ENUM('hadir', 'izin', 'sakit', 'alfa') NOT NULL,
    keterangan TEXT,
    bukti_foto VARCHAR(255),
    approved_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES guru(id) ON DELETE SET NULL
);

-- Tabel Pengajuan Izin
CREATE TABLE pengajuan_izin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    siswa_id INT NOT NULL,
    jenis_izin ENUM('sakit', 'izin', 'cuti') NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    alasan TEXT NOT NULL,
    bukti_dokumen VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    catatan_penolakan TEXT,
    approved_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES guru(id) ON DELETE SET NULL
);

-- Tabel Penilaian Siswa
CREATE TABLE penilaian (
    id INT PRIMARY KEY AUTO_INCREMENT,
    siswa_id INT NOT NULL,
    guru_id INT NOT NULL,
    dudi_id INT NOT NULL,
    periode VARCHAR(50) NOT NULL,
    nilai_disiplin DECIMAL(3,2) DEFAULT 0,
    nilai_kinerja DECIMAL(3,2) DEFAULT 0,
    nilai_sikap DECIMAL(3,2) DEFAULT 0,
    nilai_kehadiran DECIMAL(3,2) DEFAULT 0,
    total_nilai DECIMAL(5,2) GENERATED ALWAYS AS (
        (nilai_disiplin + nilai_kinerja + nilai_sikap + nilai_kehadiran) / 4
    ) STORED,
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE,
    FOREIGN KEY (dudi_id) REFERENCES dudi(id) ON DELETE CASCADE
);

-- Tabel Pengumuman
CREATE TABLE pengumuman (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dudi_id INT NOT NULL,
    judul VARCHAR(200) NOT NULL,
    isi TEXT NOT NULL,
    target_audience ENUM('all', 'siswa', 'guru') DEFAULT 'all',
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (dudi_id) REFERENCES dudi(id) ON DELETE CASCADE
);

-- Tabel Password Reset
CREATE TABLE password_resets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Index untuk performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_siswa_nis ON siswa(nis);
CREATE INDEX idx_logbook_siswa_tanggal ON logbook(siswa_id, tanggal);
CREATE INDEX idx_presensi_siswa_tanggal ON presensi(siswa_id, tanggal);
CREATE INDEX idx_pengajuan_siswa_status ON pengajuan_izin(siswa_id, status);

-- Insert default admin user
INSERT INTO users (email, password, role) VALUES 
('admin@simagang.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert admin detail (manual insert needed for admin profile if required)
-- INSERT INTO admin (user_id, nama_lengkap, nip) VALUES (1, 'Administrator', 'ADMIN001');
