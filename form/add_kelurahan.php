<?php
include '../config/koneksi.php';

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

<body class="bg-gray-100 font-sans">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <h1 class="text-2xl font-bold text-gray-800">Data Kelurahan</h1>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="overflow-x-auto">
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
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Tambah Kelurahan Baru</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label for="id" class="block text-sm font-medium text-gray-700 mb-1">ID (Kodepos)</label>
                    <input type="text" name="id" id="id" pattern="[0-9]{5}" maxlength="5"
                        class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                        title="Harus 5 digit angka" required>
                </div>
                <div>
                    <label for="nama_kelurahan" class="block text-sm font-medium text-gray-700 mb-1">Nama Kelurahan</label>
                    <input type="text" name="nama_kelurahan" id="nama_kelurahan"
                        class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>
                <div class="flex items-center space-x-4">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Submit
                    </button>
                    <button type="button" onclick="window.location.href='../pages/pasien.php'"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Kembali
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>