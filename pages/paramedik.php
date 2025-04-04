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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="container pb-40 px-4 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 px-6  py-4 text-white flex justify-between items-center">
                <h1 class="text-2xl font-bold">
                    <i class="fas fa-user-md mr-2"></i>Data Paramedik
                </h1>
                <a href="../form/add_paramedik.php" class="px-4 py-2 bg-white text-blue-600 rounded-md hover:bg-blue-50 transition">
                    <i class="fas fa-plus-circle mr-1"></i>Tambah Data
                </a>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat Lahir</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Lahir</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Kerja</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        $query = mysqli_query($koneksi, "SELECT p.*, ut.kode_unit, ut.nama_unit FROM paramedik p LEFT JOIN unit_kerja ut ON p.unitkerja_id = ut.id");
                        $i = 1;
                        while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $i++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($data['nama']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $data['gender'] == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' ?>">
                                        <?= $data['gender'] == 'L' ? 'Laki-Laki' : 'Perempuan'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['tmp_lahir']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($data['tgl_lahir'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?=
                                        $data['kategori'] == 'Dokter' ? 'bg-green-100 text-green-800' : ($data['kategori'] == 'Perawat' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800')
                                        ?>">
                                        <?= htmlspecialchars($data['kategori']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['telpon']); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars(substr($data['alamat'], 0, 30)) . (strlen($data['alamat']) > 30 ? '...' : ''); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="font-medium"><?= htmlspecialchars($data['nama_unit'] ?? '-'); ?></div>
                                    <div class="text-xs text-gray-400"><?= htmlspecialchars($data['kode_unit'] ?? ''); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="../fitur/edit_paramedik.php?id=<?= $data['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" onclick="return confirmDelete(<?= $data['id']; ?>)" class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i>Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <?php if (mysqli_num_rows($query) == 0): ?>
                <div class="text-center py-12">
                    <i class="fas fa-user-md text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900">Tidak ada data paramedik</h3>
                    <p class="text-gray-500 mt-1">Klik tombol "Tambah Data" untuk menambahkan data baru</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data pasien ini?")) {
                window.location.href = '../fitur/delete_paramedik.php?id=' + id;
            }
            return false;
        }
    </script>
</body>

</html>