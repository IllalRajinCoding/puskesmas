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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            padding: 8px;
        }

        input,
        select {
            padding: 6px;
            width: 200px;
        }

        button {
            padding: 8px 12px;
            margin-right: 5px;
        }

        a {
            display: inline-block;
            margin-bottom: 15px;
            color: blue;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>Tambah Data Pasien</h1>
    <a href="add_kelurahan.php">Tambah daftar kelurahan</a>
    <a href="index.php" style="margin-left: 10px;">Kembali</a>

    <form action="" method="POST">
        <table>
            <tr>
                <td>Kode</td>
                <td><input type="text" name="kode" required placeholder="awali dengan PSN" pattern="PSN.*" title="Kode harus diawali dengan PSN"></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td><input type="text" name="nama" required></td>
            </tr>
            <tr>
                <td>Tempat Lahir</td>
                <td><input type="text" name="tmp_lahir" required></td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td><input type="date" name="tgl_lahir" required></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td>
                    <select name="gender" required>
                        <option value="">Pilih Gender</option>
                        <option value="L">Laki-Laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="email" name="email" required></td>
            </tr>
            <tr>
                <td>Kelurahan</td>
                <td>
                    <select name="kelurahan_id" required>
                        <option value="">Pilih kelurahan</option>
                        <?php
                        $query_kelurahan = mysqli_query($koneksi, "SELECT * FROM kelurahan ORDER BY nama_kelurahan");
                        while ($data_kelurahan = mysqli_fetch_assoc($query_kelurahan)) {
                            echo "<option value='" . $data_kelurahan['id'] . "'>" . htmlspecialchars($data_kelurahan['nama_kelurahan']) . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit">Simpan</button>
                    <button type="reset">Reset</button>
                    <button type="button" onclick="window.location.href='index.php'">Kembali</button>
                </td>
            </tr>
        </table>
    </form>
</body>

</html>