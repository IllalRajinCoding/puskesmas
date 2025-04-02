<?php
include '../config/koneksi.php';

if (isset($_POST['nama'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $tmp_lahir = $_POST['tmp_lahir'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $kelurahan_id = $_POST['kelurahan_id'];

    // Validasi kelurahan_id
    if (empty($kelurahan_id)) {
        die('<script>alert("Silakan pilih kelurahan")</script>');
    }

    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $koneksi->prepare("INSERT INTO pasien (kode, nama, tmp_lahir, tgl_lahir, gender, email, kelurahan_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $kode, $nama, $tmp_lahir, $tgl_lahir, $gender, $email, $kelurahan_id);

    if ($stmt->execute()) {
        echo '<script>alert("Tambah Data Berhasil"); window.location.href="index.php";</script>';
    } else {
        echo '<script>alert("Tambah Data Gagal: ' . $stmt->error . '")</script>';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Pasien</title>
    <link rel="stylesheet" href="../src/output.css">
</head>
<body class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Tambah Data Pasien</h1>
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
                           placeholder="awali dengan PSN" pattern="PSN.*" title="Kode harus diawali dengan PSN">
                </div>
                
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="nama" id="nama" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="tmp_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tmp_lahir" id="tmp_lahir" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" id="tgl_lahir" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select name="gender" id="gender" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Gender</option>
                        <option value="L">Laki-Laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="kelurahan_id" class="block text-sm font-medium text-gray-700 mb-1">Kelurahan</label>
                    <select name="kelurahan_id" id="kelurahan_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih kelurahan</option>
                        <?php
                        $query_kelurahan = mysqli_query($koneksi, "SELECT * FROM kelurahan ORDER BY nama_kelurahan");
                        while ($data_kelurahan = mysqli_fetch_assoc($query_kelurahan)) {
                            echo "<option value='" . $data_kelurahan['id'] . "'>" . htmlspecialchars($data_kelurahan['nama_kelurahan']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="flex space-x-3 pt-4">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Simpan
                </button>
                <button type="reset" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    Reset
                </button>
                <button type="button" onclick="window.location.href='../pages/pasien.php'" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Kembali
                </button>
            </div>
        </form>
    </div>
</body>
</html>