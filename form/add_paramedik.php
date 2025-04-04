<?php
include '../config/koneksi.php';

if (isset($_POST['nama'])) {
    $nama = $_POST['nama'];
    $gender = $_POST['gender'];
    $tmp_lahir = $_POST['tmp_lahir'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $kategori = $_POST['kategori'];
    $telpon = $_POST['telpon'];
    $alamat = $_POST['alamat'];
    $unitkerja_id = $_POST['unitkerja_id'];

    // Check if all required fields are provided
    if ($nama == "" || $gender == "" || $tmp_lahir == "" || $tgl_lahir == "" || $kategori == "" || $telpon == "" || $alamat == "" || $unitkerja_id == "") {
        echo '<script>alert("Semua data harus diisi");</script>';
    } else {
        $stmt = $koneksi->prepare("INSERT INTO paramedik (nama, gender, tmp_lahir, tgl_lahir, kategori, telpon, alamat, unitkerja_id) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssssi", $nama, $gender, $tmp_lahir, $tgl_lahir, $kategori, $telpon, $alamat, $unitkerja_id);

        if ($stmt->execute()) {
            echo '<script>alert("Tambah Data Berhasil")</script>';
            echo '<script>window.location.href = "../pages/paramedik.php";</script>';
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
    <title>Tambah Data Paramedik</title>
    <link rel="stylesheet" href="../src/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="fixed top-0 -z-10 h-full w-full">
        <div class="absolute top-0 z-[-2] h-screen w-screen bg-neutral-950 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(120,119,198,0.3),rgba(255,255,255,0))]"></div>
    </div>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 px-6 py-4 text-white">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">
                        <i class="fas fa-user-plus mr-2"></i>Tambah Data Paramedik
                    </h1>
                    <div class="space-x-2">
                        <a href="add_unit.php" class="px-4 py-2 bg-white text-blue-600 rounded-md hover:bg-blue-50 transition">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div class="space-y-2">
                            <label for="nama" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user text-blue-500 mr-1"></i>Nama Lengkap
                            </label>
                            <input type="text" id="nama" name="nama" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Gender -->
                        <div class="space-y-2">
                            <label for="gender" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-venus-mars text-blue-500 mr-1"></i>Jenis Kelamin
                            </label>
                            <select name="gender" id="gender" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <!-- Tempat Lahir -->
                        <div class="space-y-2">
                            <label for="tmp_lahir" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i>Tempat Lahir
                            </label>
                            <input type="text" id="tmp_lahir" name="tmp_lahir" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="space-y-2">
                            <label for="tgl_lahir" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-calendar-day text-blue-500 mr-1"></i>Tanggal Lahir
                            </label>
                            <input type="date" id="tgl_lahir" name="tgl_lahir" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Kategori -->
                        <div class="space-y-2">
                            <label for="kategori" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-briefcase-medical text-blue-500 mr-1"></i>Kategori
                            </label>
                            <select name="kategori" id="kategori" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Kategori</option>
                                <option value="Dokter">Dokter</option>
                                <option value="Perawat">Perawat</option>
                                <option value="Bidan">Bidan</option>
                            </select>
                        </div>

                        <!-- Telpon -->
                        <div class="space-y-2">
                            <label for="telpon" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-phone text-blue-500 mr-1"></i>Nomor Telepon
                            </label>
                            <input type="tel" id="telpon" name="telpon" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                pattern="[0-9]{10,13}" title="Masukkan nomor telepon yang valid (10-13 digit)">
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2 space-y-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-map-marked-alt text-blue-500 mr-1"></i>Alamat Lengkap
                            </label>
                            <textarea name="alamat" id="alamat" rows="3" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
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
                                    echo "<option value='" . $data['id'] . "'>" . $data['nama_unit'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-4">
                        <button type="submit"
                            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-save mr-2"></i>Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>