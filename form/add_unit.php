<?php
include '../config/koneksi.php';

if (isset($_POST['submit'])) {
    $kode_unit = $_POST['kode_unit'];
    $nama_unit = $_POST['nama_unit'];
    $keterangan = $_POST['keterangan'];

    $stmt = $koneksi->prepare("INSERT INTO unit_kerja (kode_unit, nama_unit, keterangan) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $kode_unit, $nama_unit, $keterangan);

    if ($stmt->execute()) {
        echo '<script>alert("Data Berhasil Ditambahkan"); window.location.href = window.location.href;</script>';
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
    <title>Data Unit</title>
    <link rel="stylesheet" href="../src/output.css">
</head>

<body class="bg-gray-100 font-sans">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <h1 class="text-2xl font-bold text-gray-800">Data Unit</h1>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        $i = 1;
                        $query = mysqli_query($koneksi, "SELECT * FROM unit_kerja");
                        while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $i++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['kode_unit']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['nama_unit']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['keterangan']); ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Tambah Unit Baru</h2>
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label for="kode_unit" class="block text-sm font-medium text-gray-700 mb-1">Kode Unit</label>
                    <input type="text" name="kode_unit" id="kode_unit" required placeholder="Kode Unit" 
                        class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="nama_unit" class="block text-sm font-medium text-gray-700 mb-1">Nama Unit</label>
                    <input type="text" name="nama_unit" id="nama_unit" required placeholder="Nama Unit" 
                        class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" required placeholder="Keterangan" 
                        class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center space-x-4">
                    <button type="submit" name="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Tambah Unit
                    </button>
                    <button type="button" onclick="window.location.href='add_paramedik.php'" 
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Kembali
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>