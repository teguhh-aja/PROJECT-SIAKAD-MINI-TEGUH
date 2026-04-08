<?php
session_start();
require_once '../classes/Mahasiswa.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $_SESSION['mahasiswa'][] = [
                'nim' => $_POST['nim'],
                'nama' => $_POST['nama'],
                'jurusan' => $_POST['jurusan'],
                'angkatan' => $_POST['angkatan']
            ];
            $message = "Mahasiswa berhasil ditambahkan!";
        } elseif ($_POST['action'] == 'edit') {
            $index = $_POST['index'];
            $_SESSION['mahasiswa'][$index] = [
                'nim' => $_POST['nim'],
                'nama' => $_POST['nama'],
                'jurusan' => $_POST['jurusan'],
                'angkatan' => $_POST['angkatan']
            ];
            $message = "Mahasiswa berhasil diupdate!";
        } elseif ($_POST['action'] == 'delete') {
            $index = $_POST['index'];
            array_splice($_SESSION['mahasiswa'], $index, 1);
            $message = "Mahasiswa berhasil dihapus!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Mahasiswa - SIAKAD Mini</title>
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
                <a href="mahasiswa.php" class="nav-item active"><span class="icon">👨‍🎓</span><span>Manajemen
                        Mahasiswa</span></a>
                <a href="matakuliah.php" class="nav-item"><span class="icon">📚</span><span>Manajemen Mata
                        Kuliah</span></a>
                <a href="input_nilai.php" class="nav-item"><span class="icon">✏️</span><span>Input Nilai</span></a>
                <a href="hitung_ipk.php" class="nav-item"><span class="icon">📊</span><span>Hitung IPK</span></a>
                <a href="cetak_khs.php" class="nav-item"><span class="icon">🖨️</span><span>Cetak KHS</span></a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header">
                <h1>Manajemen Mahasiswa</h1>
                <div class="user-info">
                    <span>Welcome, Admin</span>
                </div>
            </header>

            <?php if (isset($message)): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>

            <div class="form-card">
                <h2>Tambah Mahasiswa Baru</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" name="nim" required placeholder="Contoh: A112022001">
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label>Jurusan</label>
                        <select name="jurusan" required>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Teknik Komputer">Teknik Komputer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Angkatan</label>
                        <input type="number" name="angkatan" required min="2020" max="2026">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Mahasiswa</button>
                </form>
            </div>

            <div class="table-container">
                <h2>Daftar Mahasiswa</h2>
                <table>
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Jurusan</th>
                            <th>Angkatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($_SESSION['mahasiswa'])): ?>
                            <?php foreach ($_SESSION['mahasiswa'] as $index => $mhs): ?>
                                <tr>
                                    <td><?= $mhs['nim'] ?></td>
                                    <td><?= $mhs['nama'] ?></td>
                                    <td><?= $mhs['jurusan'] ?></td>
                                    <td><?= $mhs['angkatan'] ?></td>
                                    <td>
                                        <button class="btn btn-warning"
                                            onclick="editMahasiswa(<?= $index ?>, '<?= $mhs['nim'] ?>', '<?= $mhs['nama'] ?>', '<?= $mhs['jurusan'] ?>', '<?= $mhs['angkatan'] ?>')">Edit</button>
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
                                <td colspan="5" style="text-align: center;">Belum ada data mahasiswa</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Mahasiswa</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="index" id="editIndex">
                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" name="nim" id="editNim" required>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" id="editNama" required>
                </div>
                <div class="form-group">
                    <label>Jurusan</label>
                    <select name="jurusan" id="editJurusan" required>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Teknik Komputer">Teknik Komputer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Angkatan</label>
                    <input type="number" name="angkatan" id="editAngkatan" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>

    <script>
        function editMahasiswa(index, nim, nama, jurusan, angkatan) {
            document.getElementById('editIndex').value = index;
            document.getElementById('editNim').value = nim;
            document.getElementById('editNama').value = nama;
            document.getElementById('editJurusan').value = jurusan;
            document.getElementById('editAngkatan').value = angkatan;
            document.getElementById('editModal').style.display = 'block';
        }

        document.querySelector('.close').onclick = function () {
            document.getElementById('editModal').style.display = 'none';
        }

        window.onclick = function (event) {
            if (event.target == document.getElementById('editModal')) {
                document.getElementById('editModal').style.display = 'none';
            }
        }
    </script>
</body>

</html>