<?php 
include '../koneksi.php';

if(isset($_POST['simpan_transaksi'])){
    $id_p   = $_POST['id_pendonor'];
    $tgl    = $_POST['tgl_transaksi'];
    $tensi  = $_POST['tekanan_darah'];
    $berat  = $_POST['berat_badan'];
    $status = $_POST['status'];

    mysqli_query($koneksi, "START TRANSACTION");

    try {
        $query = mysqli_query($koneksi, "INSERT INTO transaksi (id_pendonor, tgl_transaksi, tekanan_darah, berat_badan, status) 
                                         VALUES ('$id_p', '$tgl', '$tensi', '$berat', '$status')");

        if(!$query){
            throw new Exception("Gagal insert: " . mysqli_error($koneksi));
        }

        mysqli_query($koneksi, "COMMIT");
        header("Location: transaksi.php?pesan=berhasil");
        exit();

    } catch(Exception $e) {
        mysqli_query($koneksi, "ROLLBACK");
        echo "Error: " . $e->getMessage();
    }
}
?>