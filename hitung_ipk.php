<?php
session_start();

function hitungIPK($nim)
{
    global $_SESSION;
    $total_bobot = 0;
    $total_sks = 0;

    foreach ($_SESSION['nilai'] as $nilai) {
        if ($nilai['nim'] == $nim) {
            $mk = array_filter($_SESSION['matakuliah'], fn($m) => $m['kode'] == $nilai['kode_mk']);
            $mk = reset($mk);
            if ($mk) {
                $bobot = match ($nilai['nilai']) {
                    'A' => 4.00, 'A-' => 3.70, 'B+' => 3.30, 'B' => 3.00,
                    'B-' => 2.70, 'C+' => 2.30, 'C' => 2.00, 'D' => 1.00,
                    'E' => 0.00, default => 0
                };
                $total_bobot += $bobot * $mk['sks'];
                $total_sks += $mk['sks'];
            }
        }
    }

    return $total_sks > 0 ? $total_bobot / $total_sks : 0;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hitung IPK - SIAKAD Mini</title>
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
                <a href="input_nilai.php" class="nav-item"><span class="icon">✏️</span><span>Input Nilai</span></a>
                <a href="hitung_ipk.php" class="nav-item active"><span class="icon">📊</span><span>Hitung IPK</span></a>
                <a href="cetak_khs.php" class="nav-item"><span class="icon">🖨️</span><span>Cetak KHS</span></a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header">
                <h1>Hitung IPK Mahasiswa</h1>
                <div class="user-info">
                    <span>Welcome, Admin</span>
                </div>
            </header>

            <div class="table-container">
                <h2>Daftar IPK Mahasiswa</h2>
                <table>
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Jurusan</th>
                            <th>Total SKS</th>
                            <th>Total Bobot</th>
                            <th>IPK</th>
                            <th>Predikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['mahasiswa'] as $mhs):
                            $total_sks = 0;
                            $total_bobot = 0;
                            foreach ($_SESSION['nilai'] as $nilai) {
                                if ($nilai['nim'] == $mhs['nim']) {
                                    $mk = array_filter($_SESSION['matakuliah'], fn($m) => $m['kode'] == $nilai['kode_mk']);
                                    $mk = reset($mk);
                                    if ($mk) {
                                        $bobot = match ($nilai['nilai']) {
                                            'A' => 4.00, 'A-' => 3.70, 'B+' => 3.30, 'B' => 3.00,
                                            'B-' => 2.70, 'C+' => 2.30, 'C' => 2.00, 'D' => 1.00,
                                            'E' => 0.00, default => 0
                                        };
                                        $total_bobot += $bobot * $mk['sks'];
                                        $total_sks += $mk['sks'];
                                    }
                                }
                            }
                            $ipk = $total_sks > 0 ? $total_bobot / $total_sks : 0;

                            if ($ipk >= 3.51)
                                $predikat = "Cumlaude";
                            elseif ($ipk >= 3.00)
                                $predikat = "Sangat Memuaskan";
                            elseif ($ipk >= 2.50)
                                $predikat = "Memuaskan";
                            elseif ($ipk >= 2.00)
                                $predikat = "Cukup";
                            else
                                $predikat = "Kurang";
                            ?>
                            <tr>
                                <td><?= $mhs['nim'] ?></td>
                                <td><?= $mhs['nama'] ?></td>
                                <td><?= $mhs['jurusan'] ?></td>
                                <td><?= $total_sks ?></td>
                                <td><?= number_format($total_bobot, 2) ?></td>
                                <td><strong><?= number_format($ipk, 2) ?></strong></td>
                                <td><?= $predikat ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>