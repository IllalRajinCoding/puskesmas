<?php
include '../config/koneksi.php';

// Mulai session untuk pesan feedback
session_start();

// Ambil data pasien yang akan diedit
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pasien = null;
$kelurahan_list = [];

// Ambil data pasien dari database
if ($id > 0) {
    $stmt = $koneksi->prepare("SELECT * FROM pasien WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pasien = $result->fetch_assoc();
    $stmt->close();
    
    if (!$pasien) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Data pasien tidak ditemukan'
        ];
        header("Location: ../pages/pasien.php");
        exit();
    }
}

// Ambil daftar kelurahan untuk dropdown
$query_kel = mysqli_query($koneksi, "SELECT * FROM kelurahan ORDER BY nama_kelurahan");
while ($row = mysqli_fetch_assoc($query_kel)) {
    $kelurahan_list[] = $row;
}

// Proses form jika ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = $_POST['kode'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $tmp_lahir = $_POST['tmp_lahir'] ?? '';
    $tgl_lahir = $_POST['tgl_lahir'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $email = $_POST['email'] ?? '';
    $kelurahan_id = $_POST['kelurahan_id'] ?? 0;

    // Validasi input
    $errors = [];
    if (empty($kode)) $errors[] = "Kode harus diisi";
    if (empty($nama)) $errors[] = "Nama harus diisi";
    if (empty($tmp_lahir)) $errors[] = "Tempat lahir harus diisi";
    if (empty($tgl_lahir)) $errors[] = "Tanggal lahir harus diisi";
    if (empty($gender)) $errors[] = "Gender harus dipilih";
    if (empty($email)) $errors[] = "Email harus diisi";
    if (empty($kelurahan_id)) $errors[] = "Kelurahan harus dipilih";

    if (empty($errors)) {
        try {
            $stmt = $koneksi->prepare("UPDATE pasien SET 
                kode = ?, 
                nama = ?, 
                tmp_lahir = ?, 
                tgl_lahir = ?, 
                gender = ?, 
                email = ?, 
                kelurahan_id = ? 
                WHERE id = ?");
            
            $stmt->bind_param("ssssssii", $kode, $nama, $tmp_lahir, $tgl_lahir, $gender, $email, $kelurahan_id, $id);
            
            if ($stmt->execute()) {
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => 'Data pasien berhasil diperbarui'
                ];
                header("Location: ../pages/pasien.php");
                exit();
            } else {
                $errors[] = "Gagal memperbarui data: " . $stmt->error;
            }
            $stmt->close();
        } catch (Exception $e) {
            $errors[] = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
    
    // Jika ada error, simpan ke session
    if (!empty($errors)) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => implode("<br>", $errors)
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pasien</title>
    <link rel="stylesheet" href="../src/output.css">
    <script>
        // Tampilkan alert jika ada
        window.onload = function() {
            <?php if (isset($_SESSION['alert'])): ?>
                alert("<?= addslashes($_SESSION['alert']['message']) ?>");
                <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>
        };
    </script>
</head>
<body class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Data Pasien</h1>
            <div class="space-x-2">
                <a href="add_kelurahan.php" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                    Tambah Kelurahan
                </a>
                <a href="../pages/pasien.php" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-200">
                    Kembali
                </a>
            </div>
        </div>

        <form action="" method="POST" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode</label>
                    <input type="text" name="kode" id="kode" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: PSN001" pattern="PSN.*" title="Kode harus diawali dengan PSN"
                           value="<?= htmlspecialchars($pasien['kode'] ?? ''); ?>">
                </div>
                
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="nama" id="nama" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           value="<?= htmlspecialchars($pasien['nama'] ?? ''); ?>">
                </div>
                
                <div>
                    <label for="tmp_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tmp_lahir" id="tmp_lahir" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           value="<?= htmlspecialchars($pasien['tmp_lahir'] ?? ''); ?>">
                </div>
                
                <div>
                    <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" id="tgl_lahir" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           value="<?= htmlspecialchars($pasien['tgl_lahir'] ?? ''); ?>">
                </div>
                
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select name="gender" id="gender" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Gender</option>
                        <option value="L" <?= ($pasien['gender'] ?? '') == 'L' ? 'selected' : ''; ?>>Laki-Laki</option>
                        <option value="P" <?= ($pasien['gender'] ?? '') == 'P' ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           value="<?= htmlspecialchars($pasien['email'] ?? ''); ?>">
                </div>
                
                <div>
                    <label for="kelurahan_id" class="block text-sm font-medium text-gray-700 mb-1">Kelurahan</label>
                    <select name="kelurahan_id" id="kelurahan_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Kelurahan</option>
                        <?php foreach ($kelurahan_list as $kel): ?>
                            <option value="<?= $kel['id']; ?>" <?= ($pasien['kelurahan_id'] ?? 0) == $kel['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($kel['nama_kelurahan']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="flex space-x-3 pt-4">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Simpan Perubahan
                </button>
                <button type="button" onclick="window.location.href='../pages/pasien.php'" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Batal
                </button>
            </div>
        </form>
    </div>
</body>
</html>