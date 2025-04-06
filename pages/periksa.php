<?php
include '../config/koneksi.php';

// Inisialisasi pesan sukses/gagal
$message = '';

if (isset($_POST['submit'])) {
    $pasien_id = $_POST['pasien_id'];
    $tanggal = $_POST['tanggal'];
    $berat = $_POST['berat'];
    $tinggi = $_POST['tinggi'];
    $tensi = $_POST['tensi'];
    $keterangan = $_POST['keterangan'];
    $dokter_id = $_POST['dokter_id'];

    // Validasi data
    if (empty($pasien_id) || empty($dokter_id)) {
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong>Error!</strong> Pasien dan Dokter harus dipilih.
                    </div>';
    } else {
        $stmt = $koneksi->prepare("INSERT INTO periksa (pasien_id, tanggal, berat, tinggi, tensi, keterangan, dokter_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isddssi", $pasien_id, $tanggal, $berat, $tinggi, $tensi, $keterangan, $dokter_id);

        if ($stmt->execute()) {
            // Redirect untuk menghindari resubmit
            $_SESSION['success_message'] = "Data Berhasil Ditambahkan";
            header("Location: periksa.php");
            exit();
        } else {
            $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong>Error!</strong> Data Gagal Ditambahkan: ' . htmlspecialchars($stmt->error) . '
                        </div>';
        }
        $stmt->close();
    }
}

// Tampilkan pesan sukses dari session jika ada
if (isset($_SESSION['success_message'])) {
    $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    ' . htmlspecialchars($_SESSION['success_message']) . '
                </div>';
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Periksa Pasien</title>
    <link rel="stylesheet" href="../src/output.css">
</head>

<body class="bg-gray-100 font-sans">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <h1 class="text-2xl font-bold text-gray-800">Periksa Pasien</h1>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8 space-y-8">
        <!-- Tampilkan pesan -->
        <?php echo $message; ?>

        <!-- Data Table Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Periksa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat Badan (kg)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tinggi Badan (cm)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tensi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        $i = 1;
                        $query = mysqli_query($koneksi, "SELECT p.id, p.tanggal, p.berat, p.tinggi, p.tensi, p.keterangan, 
                                                        pas.nama AS nama_pasien, 
                                                        dok.nama AS nama_dokter 
                                                        FROM periksa p 
                                                        LEFT JOIN pasien pas ON p.pasien_id = pas.id 
                                                        LEFT JOIN paramedik dok ON p.dokter_id = dok.id
                                                        ORDER BY p.tanggal DESC");
                        while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $i++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['nama_pasien']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($data['tanggal'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['berat']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['tinggi']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['tensi']); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($data['keterangan']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['nama_dokter'] ?? '-'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a href="edit_periksa.php?id=<?= $data['id'] ?>" class="text-blue-500 hover:text-blue-700 mr-2">Edit</a>
                                    <a href="delete_periksa.php?id=<?= $data['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus?')" class="text-red-500 hover:text-red-700">Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Form Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Tambah Data Periksa</h2>
            <form action="" method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Patient Selection -->
                    <div>
                        <label for="pasien_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Pasien</label>
                        <select name="pasien_id" id="pasien_id" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Pasien</option>
                            <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM pasien");
                            while ($data = mysqli_fetch_assoc($query)) {
                                echo '<option value="' . htmlspecialchars($data['id']) . '">' . 
                                     htmlspecialchars($data['nama']) . ' (ID: ' . htmlspecialchars($data['id']) . ')</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Doctor Selection -->
                    <div>
                        <label for="dokter_id" class="block text-sm font-medium text-gray-700 mb-1">Dokter</label>
                        <select name="dokter_id" id="dokter_id" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Dokter</option>
                            <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM paramedik");
                            while ($data = mysqli_fetch_assoc($query)) {
                                echo '<option value="' . htmlspecialchars($data['id']) . '">' . 
                                     htmlspecialchars($data['nama']) . ' (ID: ' . htmlspecialchars($data['id']) . ')</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Examination Date -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Periksa</label>
                        <input type="date" name="tanggal" id="tanggal" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= date('Y-m-d') ?>">
                    </div>

                    <!-- Weight -->
                    <div>
                        <label for="berat" class="block text-sm font-medium text-gray-700 mb-1">Berat Badan (kg)</label>
                        <input type="number" step="0.1" name="berat" id="berat" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Height -->
                    <div>
                        <label for="tinggi" class="block text-sm font-medium text-gray-700 mb-1">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" name="tinggi" id="tinggi" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Blood Pressure -->
                    <div>
                        <label for="tensi" class="block text-sm font-medium text-gray-700 mb-1">Tensi</label>
                        <input type="text" name="tensi" id="tensi" required placeholder="Contoh: 120/80"
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit" name="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Tambah Data
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>