<?php
include '../config/koneksi.php';

// Validasi ID
if (isset($_GET['idk']) && is_numeric($_GET['idk'])) {
    $id = $_GET['idk'];
    
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $koneksi->prepare("DELETE FROM pasien WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Set session untuk alert sukses
        session_start();
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Data pasien berhasil dihapus'
        ];
    } else {
        // Set session untuk alert error
        session_start();
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Gagal menghapus data: ' . $stmt->error
        ];
    }
    
    $stmt->close();
} else {
    session_start();
    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'ID tidak valid'
    ];
}

// Redirect kembali ke halaman pasien
header("Location: ../pages/pasien.php");
exit();
?>