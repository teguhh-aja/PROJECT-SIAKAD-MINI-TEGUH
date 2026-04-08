<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $_SESSION['matakuliah'][] = [
                'kode' => $_POST['kode'],
                'nama' => $_POST['nama'],
                'sks' => $_POST['sks']
            ];
            $message = "Mata kuliah berhasil ditambahkan!";
        } elseif ($_POST['action'] == 'edit') {
            $index = $_POST['index'];
            $_SESSION['matakuliah'][$index] = [
                'kode' => $_POST['kode'],
                'nama' => $_POST['nama'],
                'sks' => $_POST['sks']
            ];
            $message = "Mata kuliah berhasil diupdate!";
        } elseif ($_POST['action'] == 'delete') {
            $index = $_POST['index'];
            array_splice($_SESSION['matakuliah'], $index, 1);
            $message = "Mata kuliah berhasil dihapus!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Mata Kuliah - SIAKAD Mini</title>
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
                <a href="matakuliah.php" class="nav-item active"><span class="icon">📚</span><span>Manajemen Mata
                        Kuliah</span></a>
                <a href="input_nilai.php" class="nav-item"><span class="icon">✏️</span><span>Input Nilai</span></a>
                <a href="hitung_ipk.php" class="nav-item"><span class="icon">📊</span><span>Hitung IPK</span></a>
                <a href="cetak_khs.php" class="nav-item"><span class="icon">🖨️</span><span>Cetak KHS</span></a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header">
                <h1>Manajemen Mata Kuliah</h1>
                <div class="user-info">
                    <span>Welcome, Admin</span>
                </div>
            </header>

            <?php if (isset($message)): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>

            <div class="form-card">
                <h2>Tambah Mata Kuliah Baru</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label>Kode Mata Kuliah</label>
                        <input type="text" name="kode" required placeholder="Contoh: IF101">
                    </div>
                    <div class="form-group">
                        <label>Nama Mata Kuliah</label>
                        <input type="text" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label>SKS</label>
                        <select name="sks" required>
                            <option value="1">1 SKS</option>
                            <option value="2">2 SKS</option>
                            <option value="3" selected>3 SKS</option>
                            <option value="4">4 SKS</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Mata Kuliah</button>
                </form>
            </div>

            <div class="table-container">
                <h2>Daftar Mata Kuliah</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($_SESSION['matakuliah'])): ?>
                            <?php foreach ($_SESSION['matakuliah'] as $index => $mk): ?>
                                <tr>
                                    <td><?= $mk['kode'] ?></td>
                                    <td><?= $mk['nama'] ?></td>
                                    <td><?= $mk['sks'] ?></td>
                                    <td>
                                        <button class="btn btn-warning"
                                            onclick="editMatkul(<?= $index ?>, '<?= $mk['kode'] ?>', '<?= $mk['nama'] ?>', '<?= $mk['sks'] ?>')">Edit</button>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="index" value="<?= $index ?>">
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">Belum ada data mata kuliah</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Mata Kuliah</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="index" id="editIndex">
                <div class="form-group">
                    <label>Kode MK</label>
                    <input type="text" name="kode" id="editKode" required>
                </div>
                <div class="form-group">
                    <label>Nama MK</label>
                    <input type="text" name="nama" id="editNama" required>
                </div>
                <div class="form-group">
                    <label>SKS</label>
                    <select name="sks" id="editSks" required>
                        <option value="1">1 SKS</option>
                        <option value="2">2 SKS</option>
                        <option value="3">3 SKS</option>
                        <option value="4">4 SKS</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>

    <script>
        function editMatkul(index, kode, nama, sks) {
            document.getElementById('editIndex').value = index;
            document.getElementById('editKode').value = kode;
            document.getElementById('editNama').value = nama;
            document.getElementById('editSks').value = sks;
            document.getElementById('editModal').style.display = 'block';
        }

        document.querySelector('.close').onclick = function () {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>

</html>