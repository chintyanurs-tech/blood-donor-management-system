<?php 
include '../koneksi.php';

if(isset($_POST['update_transaksi'])){
    $id_t   = $_POST['id_transaksi'];
    $id_p   = $_POST['id_pendonor'];
    $tgl    = $_POST['tgl_transaksi'];
    $tensi  = $_POST['tekanan_darah'];
    $status = $_POST['status'];

    mysqli_query($koneksi, "START TRANSACTION");

    try {
        $query = mysqli_query($koneksi, "UPDATE transaksi SET 
                                         id_pendonor='$id_p', 
                                         tgl_transaksi='$tgl', 
                                         tekanan_darah='$tensi', 
                                         status='$status' 
                                         WHERE id_transaksi='$id_t'");

        if(!$query){
            throw new Exception("Gagal update: " . mysqli_error($koneksi));
        }

        mysqli_query($koneksi, "COMMIT");
        header("Location: transaksi.php?pesan=update");
        exit();

    } catch(Exception $e) {
        mysqli_query($koneksi, "ROLLBACK");
        echo "Error: " . $e->getMessage();
    }
}
?>