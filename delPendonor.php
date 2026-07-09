<?php 
include '../koneksi.php';

$id = $_GET['id'];

mysqli_query($koneksi, "START TRANSACTION");

try {
    mysqli_query($koneksi, "DELETE FROM transaksi WHERE id_pendonor='$id'");
    $query = mysqli_query($koneksi, "DELETE FROM pendonor WHERE id_pendonor='$id'");

    if (!$query) {
        throw new Exception("Gagal menghapus pendonor");
    }

    mysqli_query($koneksi, "COMMIT");
    header("Location: pendonor.php?pesan=hapus");
    exit();
} catch (Exception $e) {
    mysqli_query($koneksi, "ROLLBACK");
    echo "Gagal menghapus: " . $e->getMessage();
}
?>