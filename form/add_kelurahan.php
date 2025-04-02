<?php
include '../config/koneksi.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama_kelurahan'];
    
    $stmt = $koneksi->prepare("INSERT INTO kelurahan(id, nama_kelurahan) VALUES (?, ?)");
    $stmt->bind_param("ss", $id, $nama);
    
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
    <title>Data Kelurahan</title>
    <link rel="stylesheet" href="../src/output.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Data Kelurahan</h1>
        
        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelurahan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $no = 1;
                    $sql = mysqli_query($koneksi, "SELECT * FROM kelurahan");
                    while ($data = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['id']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['nama_kelurahan']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
        <!-- Add Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Tambah Kelurahan</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label for="id" class="block text-sm font-medium text-gray-700">ID (Kodepos)</label>
                    <input type="text" name="id" id="id" pattern="[0-9]{5}" maxlength="5"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
                           title="Harus 5 digit angka" required>
                </div>
                <div>
                    <label for="nama_kelurahan" class="block text-sm font-medium text-gray-700">Nama Kelurahan</label>
                    <input type="text" name="nama_kelurahan" id="nama_kelurahan"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
                           required>
                </div>
                <div>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Submit
                    </button>
                    <button class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <a href="add_pasien.php">Halaman Sebelumnya</a>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>