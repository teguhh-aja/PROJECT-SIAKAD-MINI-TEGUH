<?php
session_start();

// Inisialisasi data session jika belum ada
if (!isset($_SESSION['mahasiswa'])) {
    $_SESSION['mahasiswa'] = [];
    $_SESSION['matakuliah'] = [];
    $_SESSION['nilai'] = [];
}

// Data dummy awal
if (empty($_SESSION['mahasiswa'])) {
    $_SESSION['mahasiswa'] = [
        ['nim' => 'A112022001', 'nama' => 'Ahmad Fadillah', 'jurusan' => 'Teknik Informatika', 'angkatan' => '2022'],
        ['nim' => 'A112022002', 'nama' => 'Budi Santoso', 'jurusan' => 'Sistem Informasi', 'angkatan' => '2022'],
    ];
}

if (empty($_SESSION['matakuliah'])) {
    $_SESSION['matakuliah'] = [
        ['kode' => 'IF101', 'nama' => 'Pemrograman Web', 'sks' => 3],
        ['kode' => 'IF102', 'nama' => 'Basis Data', 'sks' => 3],
        ['kode' => 'IF103', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 3],
        ['kode' => 'IF104', 'nama' => 'Jaringan Komputer', 'sks' => 2],
    ];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD Mini - Sistem Akademik</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h2>SIAKAD Mini</h2>
                <p>Academic System</p>
            </div>
            <nav class="nav-menu">
                <a href="index.php" class="nav-item active">
                    <span class="icon">🏠</span>
                    <span>Dashboard</span>
                </a>
                <a href="pages/mahasiswa.php" class="nav-item">
                    <span class="icon">👨‍🎓</span>
                    <span>Manajemen Mahasiswa</span>
                </a>
                <a href="pages/matakuliah.php" class="nav-item">
                    <span class="icon">📚</span>
                    <span>Manajemen Mata Kuliah</span>
                </a>
                <a href="pages/input_nilai.php" class="nav-item">
                    <span class="icon">✏️</span>
                    <span>Input Nilai</span>
                </a>
                <a href="pages/hitung_ipk.php" class="nav-item">
                    <span class="icon">📊</span>
                    <span>Hitung IPK</span>
                </a>
                <a href="pages/cetak_khs.php" class="nav-item">
                    <span class="icon">🖨️</span>
                    <span>Cetak KHS</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <h1>Dashboard Akademik</h1>
                <div class="user-info">
                    <span>Welcome, Admin</span>
                </div>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👨‍🎓</div>
                    <div class="stat-info">
                        <h3>Total Mahasiswa</h3>
                        <p class="stat-number"><?= count($_SESSION['mahasiswa']) ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📚</div>
                    <div class="stat-info">
                        <h3>Total Mata Kuliah</h3>
                        <p class="stat-number"><?= count($_SESSION['matakuliah']) ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📝</div>
                    <div class="stat-info">
                        <h3>Total Nilai</h3>
                        <p class="stat-number"><?= count($_SESSION['nilai']) ?></p>
                    </div>
                </div>
            </div>

            <div class="recent-section">
                <h2>Informasi Sistem</h2>
                <div class="info-card">
                    <h3>📌 Fitur yang Tersedia</h3>
                    <ul>
                        <li>✅ Manajemen Data Mahasiswa (Tambah, Edit, Hapus)</li>
                        <li>✅ Manajemen Data Mata Kuliah (Tambah, Edit, Hapus)</li>
                        <li>✅ Input Nilai Mahasiswa per Mata Kuliah</li>
                        <li>✅ Perhitungan IPK (Indeks Prestasi Kumulatif)</li>
                        <li>✅ Cetak KHS (Kartu Hasil Studi) dalam format PDF view</li>
                    </ul>
                </div>
                <div class="info-card">
                    <h3>💡 Konsep OOP yang Digunakan</h3>
                    <ul>
                        <li>🔹 Interface (CetakLaporan)</li>
                        <li>🔹 Abstract Class (User)</li>
                        <li>🔹 Class Turunan (Mahasiswa, Dosen)</li>
                        <li>🔹 Polymorphism (Cetak laporan)</li>
                        <li>🔹 Encapsulation (Private/Protected)</li>
                    </ul>
                </div>
            </div>
        </main>
    </div>
</body>

</html>