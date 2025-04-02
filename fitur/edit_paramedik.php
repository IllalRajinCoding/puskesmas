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
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 px-6 py-4 text-white">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">
                        <i class="fas fa-user-md mr-2"></i><?= isset($paramedik) ? 'Edit' : 'Tambah' ?> Data Paramedik
                    </h1>
                    <div class="space-x-2">
                        <a href="add_unitkerja.php" class="px-4 py-2 bg-white text-blue-600 rounded-md hover:bg-blue-50 transition">
                            <i class="fas fa-plus-circle mr-1"></i>Tambah Unit
                        </a>
                        <a href="../pages/paramedik.php" class="px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 transition">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="p-6">
                <form action="" method="POST" class="space-y-6">
                    <?php if (isset($paramedik)): ?>
                        <input type="hidden" name="id" value="<?= $paramedik['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div class="space-y-2">
                            <label for="nama" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user text-blue-500 mr-1"></i>Nama Lengkap
                            </label>
                            <input type="text" id="nama" name="nama" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                value="<?= isset($paramedik) ? htmlspecialchars($paramedik['nama']) : '' ?>">
                        </div>
                        
                        <!-- Gender -->
                        <div class="space-y-2">
                            <label for="gender" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-venus-mars text-blue-500 mr-1"></i>Jenis Kelamin
                            </label>
                            <select name="gender" id="gender" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" <?= (isset($paramedik) && $paramedik['gender'] == 'L') ? 'selected' : '' ?>>Laki-Laki</option>
                                <option value="P" <?= (isset($paramedik) && $paramedik['gender'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        
                        <!-- Tempat Lahir -->
                        <div class="space-y-2">
                            <label for="tmp_lahir" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i>Tempat Lahir
                            </label>
                            <input type="text" id="tmp_lahir" name="tmp_lahir" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                value="<?= isset($paramedik) ? htmlspecialchars($paramedik['tmp_lahir']) : '' ?>">
                        </div>
                        
                        <!-- Tanggal Lahir -->
                        <div class="space-y-2">
                            <label for="tgl_lahir" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-calendar-day text-blue-500 mr-1"></i>Tanggal Lahir
                            </label>
                            <input type="date" id="tgl_lahir" name="tgl_lahir" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                value="<?= isset($paramedik) ? htmlspecialchars($paramedik['tgl_lahir']) : '' ?>">
                        </div>
                        
                        <!-- Kategori -->
                        <div class="space-y-2">
                            <label for="kategori" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-briefcase-medical text-blue-500 mr-1"></i>Kategori
                            </label>
                            <select name="kategori" id="kategori" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Kategori</option>
                                <option value="Dokter" <?= (isset($paramedik) && $paramedik['kategori'] == 'Dokter') ? 'selected' : '' ?>>Dokter</option>
                                <option value="Perawat" <?= (isset($paramedik) && $paramedik['kategori'] == 'Perawat') ? 'selected' : '' ?>>Perawat</option>
                                <option value="Bidan" <?= (isset($paramedik) && $paramedik['kategori'] == 'Bidan') ? 'selected' : '' ?>>Bidan</option>
                            </select>
                        </div>
                        
                        <!-- Telpon -->
                        <div class="space-y-2">
                            <label for="telpon" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-phone text-blue-500 mr-1"></i>Nomor Telepon
                            </label>
                            <input type="tel" id="telpon" name="telpon" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                pattern="[0-9]{10,13}" title="Masukkan nomor telepon yang valid (10-13 digit)"
                                value="<?= isset($paramedik) ? htmlspecialchars($paramedik['telpon']) : '' ?>">
                        </div>
                        
                        <!-- Alamat -->
                        <div class="md:col-span-2 space-y-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-map-marked-alt text-blue-500 mr-1"></i>Alamat Lengkap
                            </label>
                            <textarea name="alamat" id="alamat" rows="3" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"><?= isset($paramedik) ? htmlspecialchars($paramedik['alamat']) : '' ?></textarea>
                        </div>
                        
                        <!-- Unit Kerja -->
                        <div class="md:col-span-2 space-y-2">
                            <label for="unitkerja_id" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-clinic-medical text-blue-500 mr-1"></i>Unit Kerja
                            </label>
                            <select name="unitkerja_id" id="unitkerja_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
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
                    <div class="flex justify-end space-x-4 pt-4">
                        <button type="button" onclick="window.location.href='../pages/paramedik.php'"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-save mr-2"></i><?= isset($paramedik) ? 'Update' : 'Simpan' ?> Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>