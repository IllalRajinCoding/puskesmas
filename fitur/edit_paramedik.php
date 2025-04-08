<?php
include '../config/koneksi.php';

// Initialize variables
$paramedik = null;

// Check if ID is provided for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $koneksi->prepare("SELECT * FROM paramedik WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $paramedik = $result->fetch_assoc();
    $query->close();
}

// Process form submission
if (isset($_POST['nama'])) {
    $id = $_POST['id'] ?? null;
    $nama = $_POST['nama'];
    $gender = $_POST['gender'];
    $tmp_lahir = $_POST['tmp_lahir'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $kategori = $_POST['kategori'];
    $telpon = $_POST['telpon'];
    $alamat = $_POST['alamat'];
    $unitkerja_id = $_POST['unitkerja_id'];

    // Validate required fields
    if (empty($nama) || empty($gender) || empty($tmp_lahir) || empty($tgl_lahir) || 
        empty($kategori) || empty($telpon) || empty($alamat) || empty($unitkerja_id)) {
        echo '<script>alert("Semua data harus diisi");</script>';
    } else {
        if ($id) {
            // Update existing record
            $stmt = $koneksi->prepare("UPDATE paramedik SET nama=?, gender=?, tmp_lahir=?, tgl_lahir=?, 
                                      kategori=?, telpon=?, alamat=?, unitkerja_id=? WHERE id=?");
            $stmt->bind_param("sssssssii", $nama, $gender, $tmp_lahir, $tgl_lahir, $kategori, 
                             $telpon, $alamat, $unitkerja_id, $id);
            $success_message = "Data berhasil diperbarui";
        } else {
            // Insert new record
            $stmt = $koneksi->prepare("INSERT INTO paramedik (nama, gender, tmp_lahir, tgl_lahir, 
                                      kategori, telpon, alamat, unitkerja_id) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssi", $nama, $gender, $tmp_lahir, $tgl_lahir, $kategori, 
                             $telpon, $alamat, $unitkerja_id);
            $success_message = "Data berhasil ditambahkan";
        }

        if ($stmt->execute()) {
            echo '<script>
                alert("'.$success_message.'");
                window.location.href = "../pages/paramedik.php";
            </script>';
        } else {
            echo '<script>alert("Operasi gagal: ' . $stmt->error . '")</script>';
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
    <title><?= isset($paramedik) ? 'Edit' : 'Tambah' ?> Data Paramedik</title>
    <link rel="stylesheet" href="../src/output.css">
</head>

<body class="bg-gray-100 font-sans">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <h1 class="text-2xl font-bold text-gray-800"><?= isset($paramedik) ? 'Edit' : 'Tambah' ?> Data Paramedik</h1>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <form action="" method="POST" class="space-y-6">
                    <?php if (isset($paramedik)): ?>
                        <input type="hidden" name="id" value="<?= $paramedik['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" required
                                class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="<?= isset($paramedik) ? htmlspecialchars($paramedik['nama']) : '' ?>">
                        </div>
                        
                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select name="gender" id="gender" required
                                class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" <?= (isset($paramedik) && $paramedik['gender'] == 'L') ? 'selected' : '' ?>>Laki-Laki</option>
                                <option value="P" <?= (isset($paramedik) && $paramedik['gender'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        
                        <!-- Tempat Lahir -->
                        <div>
                            <label for="tmp_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                            <input type="text" id="tmp_lahir" name="tmp_lahir" required
                                class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="<?= isset($paramedik) ? htmlspecialchars($paramedik['tmp_lahir']) : '' ?>">
                        </div>
                        
                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" id="tgl_lahir" name="tgl_lahir" required
                                class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="<?= isset($paramedik) ? htmlspecialchars($paramedik['tgl_lahir']) : '' ?>">
                        </div>
                        
                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="kategori" id="kategori" required
                                class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Kategori</option>
                                <option value="Dokter" <?= (isset($paramedik) && $paramedik['kategori'] == 'Dokter') ? 'selected' : '' ?>>Dokter</option>
                                <option value="Perawat" <?= (isset($paramedik) && $paramedik['kategori'] == 'Perawat') ? 'selected' : '' ?>>Perawat</option>
                                <option value="Bidan" <?= (isset($paramedik) && $paramedik['kategori'] == 'Bidan') ? 'selected' : '' ?>>Bidan</option>
                            </select>
                        </div>
                        
                        <!-- Telpon -->
                        <div>
                            <label for="telpon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="tel" id="telpon" name="telpon" required
                                class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                                pattern="[0-9]{10,13}" title="Masukkan nomor telepon yang valid (10-13 digit)"
                                value="<?= isset($paramedik) ? htmlspecialchars($paramedik['telpon']) : '' ?>">
                        </div>
                        
                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" rows="3" required
                                class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"><?= isset($paramedik) ? htmlspecialchars($paramedik['alamat']) : '' ?></textarea>
                        </div>
                        
                        <!-- Unit Kerja -->
                        <div class="md:col-span-2">
                            <label for="unitkerja_id" class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja</label>
                            <select name="unitkerja_id" id="unitkerja_id" required
                                class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Unit Kerja</option>
                                <?php
                                $query = mysqli_query($koneksi, "SELECT * FROM unit_kerja");
                                while ($data = mysqli_fetch_assoc($query)) {
                                    $selected = (isset($paramedik) && $paramedik['unitkerja_id'] == $data['id']) ? 'selected' : '';
                                    echo "<option value='" . $data['id'] . "' $selected>" . htmlspecialchars($data['nama_unit']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-4 pt-4">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                            <?= isset($paramedik) ? 'Update' : 'Simpan' ?> Data
                        </button>
                        <a href="../pages/paramedik.php"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>