<?php

include '../config/koneksi.php';

if (isset($_GET['idk']) &&  is_numeric($_GET['idk'])) {
    $id = $_GET['idk'];

    $stmt = $koneksi->prepare("DELETE FROM paramedik WHERE id = ?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
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

header("Location: ../pages/paramedik.php");
exit();
?>