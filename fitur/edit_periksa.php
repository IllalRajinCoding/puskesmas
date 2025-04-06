<?php
include '../config/koneksi.php';
session_start();

// Ambil ID dari parameter URL
$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['alert'] = "ID pemeriksaan tidak valid";
    header("Location: periksa.php");
    exit();
}

// Ambil data pemeriksaan yang akan diedit
$query = $koneksi->prepare("SELECT p.*, pas.nama AS nama_pasien, dok.nama AS nama_dokter 
                           FROM periksa p
                           LEFT JOIN pasien pas ON p.pasien_id = pas.id
                           LEFT JOIN paramedik dok ON p.dokter_id = dok.id
                           WHERE p.id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    $_SESSION['alert'] = "Data pemeriksaan tidak ditemukan";
    header("Location: periksa.php");
    exit();
}

// Proses update data
if (isset($_POST['submit'])) {
    $pasien_id = $_POST['pasien_id'];
    $tanggal = $_POST['tanggal'];
    $berat = $_POST['berat'];
    $tinggi = $_POST['tinggi'];
    $tensi = $_POST['tensi'];
    $keterangan = $_POST['keterangan'];
    $dokter_id = $_POST['dokter_id'];

    $stmt = $koneksi->prepare("UPDATE periksa SET 
                              pasien_id = ?, 
                              tanggal = ?, 
                              berat = ?, 
                              tinggi = ?, 
                              tensi = ?, 
                              keterangan = ?, 
                              dokter_id = ?
                              WHERE id = ?");
    $stmt->bind_param("isddssii", $pasien_id, $tanggal, $berat, $tinggi, $tensi, $keterangan, $dokter_id, $id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Data pemeriksaan berhasil diperbarui";
        header("Location: ../pages/periksa.php");
        exit();
    } else {
        $error = $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pemeriksaan</title>
    <link rel="stylesheet" href="../src/output.css">
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <h1 class="text-2xl font-bold text-gray-800">Edit Data Pemeriksaan</h1>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Edit Data Pemeriksaan</h2>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong>Error!</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Patient Selection -->
                    <div>
                        <label for="pasien_id" class="block text-sm font-medium text-gray-700 mb-1">Pasien</label>
                        <select name="pasien_id" id="pasien_id" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Pasien</option>
                            <?php
                            $query_pasien = mysqli_query($koneksi, "SELECT * FROM pasien");
                            while ($pasien = mysqli_fetch_assoc($query_pasien)) {
                                $selected = ($pasien['id'] == $data['pasien_id']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($pasien['id']) . '" ' . $selected . '>' . 
                                     htmlspecialchars($pasien['nama']) . ' (ID: ' . htmlspecialchars($pasien['id']) . ')</option>';
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
                            $query_dokter = mysqli_query($koneksi, "SELECT * FROM paramedik");
                            while ($dokter = mysqli_fetch_assoc($query_dokter)) {
                                $selected = ($dokter['id'] == $data['dokter_id']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($dokter['id']) . '" ' . $selected . '>' . 
                                     htmlspecialchars($dokter['nama']) . ' (ID: ' . htmlspecialchars($dokter['id']) . ')</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Examination Date -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Periksa</label>
                        <input type="date" name="tanggal" id="tanggal" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= htmlspecialchars($data['tanggal']) ?>">
                    </div>

                    <!-- Weight -->
                    <div>
                        <label for="berat" class="block text-sm font-medium text-gray-700 mb-1">Berat Badan (kg)</label>
                        <input type="number" step="0.1" name="berat" id="berat" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= htmlspecialchars($data['berat']) ?>">
                    </div>

                    <!-- Height -->
                    <div>
                        <label for="tinggi" class="block text-sm font-medium text-gray-700 mb-1">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" name="tinggi" id="tinggi" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= htmlspecialchars($data['tinggi']) ?>">
                    </div>

                    <!-- Blood Pressure -->
                    <div>
                        <label for="tensi" class="block text-sm font-medium text-gray-700 mb-1">Tensi</label>
                        <input type="text" name="tensi" id="tensi" required placeholder="Contoh: 120/80"
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= htmlspecialchars($data['tensi']) ?>">
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($data['keterangan']) ?></textarea>
                    </div>
                </div>

                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit" name="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Simpan Perubahan
                    </button>
                    <a href="../pages/periksa.php"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>