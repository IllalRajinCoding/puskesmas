<?php
include '../config/koneksi.php';

session_start();
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    echo '<script>alert("' . $alert . '");</script>';
    unset($_SESSION['alert']);
}

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $gender = $_POST['gender'];
    $tmp_lahir = $_POST['tmp_lahir'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $kategori = $_POST['kategori'];
    $telpon = $_POST['telpon'];
    $alamat = $_POST['alamat'];
    $unitkerja_id = $_POST['unitkerja_id'];

    $stmt = $koneksi->prepare("INSERT INTO paramedik (nama, gender, tmp_lahir, tgl_lahir, kategori, telpon, alamat, unitkerja_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $nama, $gender, $tmp_lahir, $tgl_lahir, $kategori, $telpon, $alamat, $unitkerja_id);

    if ($stmt->execute()) {
        echo '<script>alert("Data Berhasil Ditambahkan"); window.location.href = "../pages/paramedik.php";</script>';
    } else {
        echo '<script>alert("Data Gagal Ditambahkan: ' . $stmt->error . '");</script>';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Paramedik</title>
    <link rel="stylesheet" href="../src/output.css">
</head>

<body class="bg-gray-100 font-sans">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Data Paramedik</h1>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat Lahir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Lahir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Kerja</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        $query = mysqli_query($koneksi, "SELECT p.*, ut.kode_unit, ut.nama_unit FROM paramedik p LEFT JOIN unit_kerja ut ON p.unitkerja_id = ut.id");
                        $i = 1;
                        while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $i++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['nama']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $data['gender'] == 'L' ? 'Laki-Laki' : 'Perempuan'; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['tmp_lahir']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($data['tgl_lahir'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['kategori']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['telpon']); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars(substr($data['alamat'], 0, 30)) . (strlen($data['alamat']) > 30 ? '...' : ''); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($data['nama_unit'] ?? '-'); ?>
                                    <div class="text-xs text-gray-400"><?= htmlspecialchars($data['kode_unit'] ?? ''); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a href="../fitur/edit_paramedik.php?id=<?= $data['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>
                                    <a href="#" onclick="return confirmDelete(<?= $data['id']; ?>)" class="text-red-500 hover:text-red-700">Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <?php if (mysqli_num_rows($query) == 0): ?>
                <div class="text-center py-12">
                    <h3 class="text-lg font-medium text-gray-900">Tidak ada data paramedik</h3>
                    <p class="text-gray-500 mt-1">Klik tombol "Tambah Data" untuk menambahkan data baru</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Form Tambah Paramedik</h2>
            <form action="" method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" required placeholder="Nama Lengkap"
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="gender" id="gender" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tmp_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="tmp_lahir" id="tmp_lahir" required placeholder="Tempat Lahir"
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" id="tgl_lahir" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="kategori" id="kategori" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Kategori</option>
                            <option value="Dokter">Dokter</option>
                            <option value="Perawat">Perawat</option>
                            <option value="Bidan">Bidan</option>
                        </select>
                    </div>

                    <!-- Telpon -->
                    <div>
                        <label for="telpon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="tel" name="telpon" id="telpon" required placeholder="Nomor Telepon"
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            pattern="[0-9]{10,13}" title="Masukkan nomor telepon yang valid (10-13 digit)">
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" rows="3" required placeholder="Alamat Lengkap"
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <!-- Unit Kerja -->
                    <div class="md:col-span-2">
                        <label for="unitkerja_id" class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja</label>
                        <select name="unitkerja_id" id="unitkerja_id" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Unit Kerja</option>
                            <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM unit_kerja");
                            while ($data = mysqli_fetch_assoc($query)) {
                                echo "<option value='" . $data['id'] . "'>" . htmlspecialchars($data['nama_unit']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit" name="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Tambah Data
                    </button>
                    <button type="button" onclick="window.location.href='../form/add_unit.php'"
                        class="bg-green-600 hover:bg-blue-200 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Tambah Unit Kerja
                    </button>
                    <button type="button" onclick="window.location.href='../dashboard.php'"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Kembali
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data paramedik ini?")) {
                window.location.href = '../fitur/delete_paramedik.php?idk=' + id;
            }
            return false;
        }
    </script>
</body>

</html>