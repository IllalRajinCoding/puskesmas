<?php
include '../config/koneksi.php';

if (isset($_GET['id']) &&  is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $koneksi->prepare("DELETE FROM periksa WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['alert'] = "Data berhasil dihapus";
    } else {
        session_start();
        $_SESSION['alert'] = "Data gagal dihapus";
    }
    $stmt->close();
} else {
    session_start();
    $_SESSION['alert'] = 'Data tidak ditemukan';
}

header("Location: ../pages/periksa.php");
exit();
