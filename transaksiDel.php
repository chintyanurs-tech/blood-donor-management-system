<?php 
include '../koneksi.php';

$id = $_GET['id'];

// Mulai transaction untuk memastikan konsistensi data
mysqli_query($koneksi, "START TRANSACTION");

try {
    $data = mysqli_query($koneksi, "SELECT t.status, p.golongan_darah FROM transaksi t 
                                    JOIN pendonor p ON t.id_pendonor = p.id_pendonor 
                                    WHERE t.id_transaksi='$id'");
    $t = $data && mysqli_num_rows($data) > 0 ? mysqli_fetch_assoc($data) : null;

    if(!$t){
        throw new Exception("Data transaksi tidak ditemukan");
    }

    // 2. Hapus data transaksi terlebih dahulu
    $query = mysqli_query($koneksi, "DELETE FROM transaksi WHERE id_transaksi='$id'");
    
    if(!$query){
        throw new Exception("Gagal menghapus transaksi: " . mysqli_error($koneksi));
    }

    // PENTING: Stok akan otomatis terkoreksi melalui query COUNT real-time di stok.php
    // Tidak perlu update stok_darah table lagi karena stok dihitung dari transaksi yang tersisa

    mysqli_query($koneksi, "COMMIT");
    header("Location: transaksi.php?pesan=hapus");
    exit();

} catch(Exception $e) {
    // Rollback jika ada error
    mysqli_query($koneksi, "ROLLBACK");
    echo "Error: " . $e->getMessage();
}
?>