<?php
include '../config/koneksi.php';

// Tampilkan alert jika ada
session_start();
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    echo '<script>alert("' . $alert['message'] . '");</script>';
    unset($_SESSION['alert']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabel Data Pasien</title>
    <link rel="stylesheet" href="../src/output.css">
    <script>
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data pasien ini?")) {
                window.location.href = '../fitur/delete_pasien.php?idk=' + id;
            }
            return false;
        }
    </script>
</head>

<body class="bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Data Pasien</h2>
            <a href="../form/add_pasien.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-300">
                Tambah Pasien
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat Lahir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Lahir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelurahan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        $query = mysqli_query($koneksi, "SELECT p.*, k.nama_kelurahan 
                                                        FROM pasien p 
                                                        LEFT JOIN kelurahan k ON p.kelurahan_id = k.id");
                        $no = 1;
                        while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($data['kode']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($data['nama']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['tmp_lahir']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d-m-Y', strtotime($data['tgl_lahir'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $data['gender'] == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' ?>">
                                        <?= $data['gender'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['email']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['nama_kelurahan'] ?? 'Tidak ada'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="../fitur/edit_pasien.php?id=<?= $data['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <a href="#" onclick="return confirmDelete(<?= $data['id']; ?>)" class="text-red-600 hover:text-red-900">Hapus</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>