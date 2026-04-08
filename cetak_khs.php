<?php
session_start();
require_once '../classes/CetakLaporan.php';

class CetakKHS implements CetakLaporan
{
    public function cetak($data)
    {
        $html = '
        <div style="font-family: Arial, sans-serif; padding: 20px;">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2>KARTU HASIL STUDI (KHS)</h2>
                <h3>UNIVERSITAS SIAKAD MINI</h3>
                <p>Jl. Pendidikan No. 123, Kota Akademik</p>
                <hr>
            </div>
            
            <div style="margin-bottom: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 5px;"><strong>NIM</strong></td>
                        <td style="padding: 5px;">: ' . $data['mahasiswa']['nim'] . '</td>
                        <td style="padding: 5px;"><strong>Jurusan</strong></td>
                        <td style="padding: 5px;">: ' . $data['mahasiswa']['jurusan'] . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px;"><strong>Nama</strong></td>
                        <td style="padding: 5px;">: ' . $data['mahasiswa']['nama'] . '</td>
                        <td style="padding: 5px;"><strong>Angkatan</strong></td>
                        <td style="padding: 5px;">: ' . $data['mahasiswa']['angkatan'] . '</td>
                    </tr>
                </table>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
                <thead>
                    <tr style="background-color: #1e3c72; color: white;">
                        <th style="padding: 10px; border: 1px solid #000;">No</th>
                        <th style="padding: 10px; border: 1px solid #000;">Kode MK</th>
                        <th style="padding: 10px; border: 1px solid #000;">Mata Kuliah</th>
                        <th style="padding: 10px; border: 1px solid #000;">SKS</th>
                        <th style="padding: 10px; border: 1px solid #000;">Nilai</th>
                        <th style="padding: 10px; border: 1px solid #000;">Bobot</th>
                        <th style="padding: 10px; border: 1px solid #000;">Mutu</th>
                    </tr>
                </thead>
                <tbody>';

        $no = 1;
        $total_bobot = 0;
        $total_sks = 0;

        foreach ($data['nilai'] as $nilai) {
            $mk = $data['matakuliah'][$nilai['kode_mk']] ?? null;
            if ($mk) {
                $bobot_nilai = match ($nilai['nilai']) {
                    'A' => 4.00, 'A-' => 3.70, 'B+' => 3.30, 'B' => 3.00,
                    'B-' => 2.70, 'C+' => 2.30, 'C' => 2.00, 'D' => 1.00,
                    'E' => 0.00, default => 0
                };
                $mutu = $bobot_nilai * $mk['sks'];
                $total_bobot += $mutu;
                $total_sks += $mk['sks'];

                $html .= '
                <tr>
                    <td style="padding: 8px; border: 1px solid #000; text-align: center;">' . $no++ . '</td>
                    <td style="padding: 8px; border: 1px solid #000;">' . $nilai['kode_mk'] . '</td>
                    <td style="padding: 8px; border: 1px solid #000;">' . $mk['nama'] . '</td>
                    <td style="padding: 8px; border: 1px solid #000; text-align: center;">' . $mk['sks'] . '</td>
                    <td style="padding: 8px; border: 1px solid #000; text-align: center;">' . $nilai['nilai'] . '</td>
                    <td style="padding: 8px; border: 1px solid #000; text-align: center;">' . number_format($bobot_nilai, 2) . '</td>
                    <td style="padding: 8px; border: 1px solid #000; text-align: center;">' . number_format($mutu, 2) . '</td>
                </tr>';
            }
        }

        $ipk = $total_sks > 0 ? $total_bobot / $total_sks : 0;

        $html .= '
                </tbody>
                <tfoot>
                    <tr style="background-color: #f0f0f0;">
                        <td colspan="3" style="padding: 10px; border: 1px solid #000; text-align: center;"><strong>TOTAL</strong></td>
                        <td style="padding: 10px; border: 1px solid #000; text-align: center;"><strong>' . $total_sks . '</strong></td>
                        <td colspan="2" style="padding: 10px; border: 1px solid #000;"></td>
                        <td style="padding: 10px; border: 1px solid #000; text-align: center;"><strong>' . number_format($total_bobot, 2) . '</strong></td>
                    </tr>
                    <tr>
                        <td colspan="6" style="padding: 10px; border: 1px solid #000; text-align: center;"><strong>Indeks Prestasi Kumulatif (IPK)</strong></td>
                        <td style="padding: 10px; border: 1px solid #000; text-align: center;"><strong>' . number_format($ipk, 2) . '</strong></td>
                    </tr>
                </tfoot>
            </table>
            
            <div style="margin-top: 30px; text-align: right;">
                <p>Kota Akademik, ' . date('d F Y') . '</p>
                <p>Kepala Program Studi</p>
                <br><br><br>
                <p><u>Dr. SIAKAD, M.Kom.</u></p>
                <p>NIP. 197001012000121001</p>
            </div>
        </div>';

        return $html;
    }
}

$selected_nim = $_GET['nim'] ?? ($_SESSION['mahasiswa'][0]['nim'] ?? '');
$khs_data = null;

if ($selected_nim && !empty($_SESSION['mahasiswa'])) {
    $mhs = array_filter($_SESSION['mahasiswa'], fn($m) => $m['nim'] == $selected_nim);
    $mhs = reset($mhs);

    $nilai_mhs = array_filter($_SESSION['nilai'], fn($n) => $n['nim'] == $selected_nim);

    $matakuliah_map = [];
    foreach ($_SESSION['matakuliah'] as $mk) {
        $matakuliah_map[$mk['kode']] = $mk;
    }

    $khs_data = [
        'mahasiswa' => $mhs,
        'nilai' => $nilai_mhs,
        'matakuliah' => $matakuliah_map
    ];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak KHS - SIAKAD Mini</title>
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
                <a href="hitung_ipk.php" class="nav-item"><span class="icon">📊</span><span>Hitung IPK</span></a>
                <a href="cetak_khs.php" class="nav-item active"><span class="icon">🖨️</span><span>Cetak KHS</span></a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header">
                <h1>Cetak KHS (Kartu Hasil Studi)</h1>
                <div class="user-info">
                    <span>Welcome, Admin</span>
                </div>
            </header>

            <div class="form-card">
                <h2>Pilih Mahasiswa</h2>
                <form method="GET" action="">
                    <div class="form-group">
                        <label>Pilih Mahasiswa</label>
                        <select name="nim" required onchange="this.form.submit()">
                            <option value="">-- Pilih Mahasiswa --</option>
                            <?php foreach ($_SESSION['mahasiswa'] as $mhs): ?>
                                <option value="<?= $mhs['nim'] ?>" <?= $selected_nim == $mhs['nim'] ? 'selected' : '' ?>>
                                    <?= $mhs['nama'] ?> (<?= $mhs['nim'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>

            <?php if ($khs_data && $khs_data['mahasiswa']):
                $cetak = new CetakKHS();
                echo $cetak->cetak($khs_data);
                ?>
                <div style="margin-top: 20px; text-align: center;">
                    <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak / Simpan PDF</button>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">Silakan pilih mahasiswa terlebih dahulu</div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>