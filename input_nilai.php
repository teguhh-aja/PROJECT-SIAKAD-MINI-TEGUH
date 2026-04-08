<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $kode_mk = $_POST['kode_mk'];
    $nilai = $_POST['nilai'];

    $key = $nim . '_' . $kode_mk;
    $_SESSION['nilai'][$key] = [
        'nim' => $nim,
        'kode_mk' => $kode_mk,
        'nilai' => $nilai
    ];
    $message = "Nilai berhasil diinput!";
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai - SIAKAD Mini</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <h2>SIAKAD Mini</h2>
                <p>Academic System</p>
            </div>
            <nav class="nav-menu">
                <a href="../index.php" class="nav-item"><span class="icon">🏠</span><span>Dashboard</span></a>
                <a href="mahasiswa.php" class="nav-item"><span class="icon">👨‍🎓</span><span>Manajemen
                        Mahasiswa</span></a>
                <a href="matakuliah.php" class="nav-item"><span class="icon">📚</span><span>Manajemen Mata
                        Kuliah</span></a>
                <a href="input_nilai.php" class="nav-item active"><span class="icon">✏️</span><span>Input
                        Nilai</span></a>
                <a href="hitung_ipk.php" class="nav-item"><span class="icon">📊</span><span>Hitung IPK</span></a>
                <a href="cetak_khs.php" class="nav-item"><span class="icon">🖨️</span><span>Cetak KHS</span></a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header">
                <h1>Input Nilai Mahasiswa</h1>
                <div class="user-info">
                    <span>Welcome, Admin</span>
                </div>
            </header>

            <?php if (isset($message)): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>

            <div class="form-card">
                <h2>Form Input Nilai</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Pilih Mahasiswa</label>
                        <select name="nim" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            <?php foreach ($_SESSION['mahasiswa'] as $mhs): ?>
                                <option value="<?= $mhs['nim'] ?>"><?= $mhs['nama'] ?> (<?= $mhs['nim'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pilih Mata Kuliah</label>
                        <select name="kode_mk" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            <?php foreach ($_SESSION['matakuliah'] as $mk): ?>
                                <option value="<?= $mk['kode'] ?>"><?= $mk['nama'] ?> (<?= $mk['kode'] ?>) -
                                    <?= $mk['sks'] ?> SKS</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nilai</label>
                        <select name="nilai" required>
                            <option value="A">A (4.00) - Sangat Baik</option>
                            <option value="A-">A- (3.70) - Baik Sekali</option>
                            <option value="B+">B+ (3.30) - Baik</option>
                            <option value="B">B (3.00) - Cukup Baik</option>
                            <option value="B-">B- (2.70) - Cukup</option>
                            <option value="C+">C+ (2.30) - Kurang Cukup</option>
                            <option value="C">C (2.00) - Kurang</option>
                            <option value="D">D (1.00) - Sangat Kurang</option>
                            <option value="E">E (0.00) - Gagal</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                </form>
            </div>

            <div class="table-container">
                <h2>Daftar Nilai yang Sudah Diinput</h2>
                <table>
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Kode MK</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Nilai</th>
                            <th>Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($_SESSION['nilai'])): ?>
                            <?php foreach ($_SESSION['nilai'] as $nilai):
                                $mhs = array_filter($_SESSION['mahasiswa'], fn($m) => $m['nim'] == $nilai['nim']);
                                $mhs = reset($mhs);
                                $mk = array_filter($_SESSION['matakuliah'], fn($m) => $m['kode'] == $nilai['kode_mk']);
                                $mk = reset($mk);
                                $bobot = match ($nilai['nilai']) {
                                    'A' => 4.00, 'A-' => 3.70, 'B+' => 3.30, 'B' => 3.00,
                                    'B-' => 2.70, 'C+' => 2.30, 'C' => 2.00, 'D' => 1.00,
                                    'E' => 0.00, default => 0
                                };
                                ?>
                                <tr>
                                    <td><?= $nilai['nim'] ?></td>
                                    <td><?= $mhs['nama'] ?? '-' ?></td>
                                    <td><?= $nilai['kode_mk'] ?></td>
                                    <td><?= $mk['nama'] ?? '-' ?></td>
                                    <td><?= $nilai['nilai'] ?></td>
                                    <td><?= number_format($bobot, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">Belum ada data nilai</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>