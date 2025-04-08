<?php
include '../config/koneksi.php';

// Tampilkan alert jika ada
session_start();
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    echo '<script>alert("' . $alert['message'] . '");</script>';
    unset($_SESSION['alert']);
}

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

    if ($nama == "" || $kode == "" || $tmp_lahir == "" || $tgl_lahir == "" || $gender == "" || $email == "" || $kelurahan_id == "") {
        echo '<script>alert("Data sudah ada");</script>';
    } else {

        if ($stmt->execute()) {
            echo '<script>alert("Tambah Data Berhasil")</script>';
            echo '<script>window.location.href = "../pages/pasien.php";</script>';
        } else {
            echo '<script>alert("Tambah Data Gagal: ' . $stmt->error . '")</script>';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien</title>
    <link rel="stylesheet" href="../src/output.css">
</head>

<body class="bg-gray-100 font-sans">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Data Pasien</h1>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
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
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['kode']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['nama']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['tmp_lahir']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d-m-Y', strtotime($data['tgl_lahir'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $data['gender'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['email']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($data['nama_kelurahan'] ?? 'Tidak ada'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a href="../fitur/edit_pasien.php?id=<?= $data['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>
                                    <a href="#" onclick="return confirmDelete(<?= $data['id']; ?>)" class="text-red-500 hover:text-red-700">Hapus</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Form Tambah Pasien</h2>
                <div class="space-x-2">
                    <a href="../form/add_kelurahan.php" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Tambah Kelurahan
                    </a>
                </div>
            </div>

            <form action="" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode</label>
                        <input type="text" name="kode" id="kode" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="awali dengan PSN" pattern="PSN.*" title="Kode harus diawali dengan PSN">
                    </div>

                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="nama" id="nama" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="tmp_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="tmp_lahir" id="tmp_lahir" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" id="tgl_lahir" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select name="gender" id="gender" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Jenis Kelamin</option>
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="kelurahan_id" class="block text-sm font-medium text-gray-700 mb-1">Kelurahan</label>
                        <select name="kelurahan_id" id="kelurahan_id" required
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
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

                <div class="flex space-x-4 pt-4">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Simpan
                    </button>
                    <button type="button" onclick="window.location.href='../dashboard.php'"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                        Kembali
                    </button>
                </div>
            </form>
        </div>

    </main>

    <script>
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data pasien ini?")) {
                window.location.href = '../fitur/delete_pasien.php?idk=' + id;
            }
            return false;
        }
    </script>
</body>

</html>