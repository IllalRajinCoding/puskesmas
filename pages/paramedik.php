<?php
include '../config/koneksi.php';

session_start();
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    echo '<script>alert("' . $alert . '");</script>';
    unset($_SESSION['alert']);
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
            <a href="../form/add_paramedik.php" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                Tambah Data
            </a>
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
    </main>

    <script>
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data paramedik ini?")) {
                window.location.href = '../fitur/delete_paramedik.php?id=' + id;
            }
            return false;
        }
    </script>
</body>

</html>