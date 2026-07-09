<?php 
include '../koneksi.php';
include 'header.php'; 

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id_transaksi='$id'");
$t = mysqli_fetch_array($data);
?>

<div class="container">
    <h2>Edit Transaksi Donor</h2>
    <hr>
    <form method="POST" action="transaksiUpdateProses.php">
        <input type="hidden" name="id_transaksi" value="<?php echo $t['id_transaksi']; ?>">
        
        <table cellpadding="10">
            <tr>
                <td>Pendonor</td>
                <td>:</td>
                <td>
                    <select name="id_pendonor" required>
                        <?php 
                        $p = mysqli_query($koneksi, "SELECT * FROM pendonor");
                        while($dp = mysqli_fetch_array($p)){
                            // Cek agar pendonor yang lama otomatis terpilih (selected)
                            $select = ($dp['id_pendonor'] == $t['id_pendonor']) ? 'selected' : '';
                            echo "<option value='$dp[id_pendonor]' $select>$dp[nama_pendonor]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td><input type="date" name="tgl_transaksi" value="<?php echo $t['tgl_transaksi']; ?>" required></td>
            </tr>
            <tr>
                <td>Tensi</td>
                <td>:</td>
                <td><input type="text" name="tekanan_darah" value="<?php echo $t['tekanan_darah']; ?>" required></td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td>
                    <select name="status">
                        <option value="Berhasil" <?php if($t['status']=='Berhasil') echo 'selected'; ?>>Berhasil</option>
                        <option value="Gagal" <?php if($t['status']=='Gagal') echo 'selected'; ?>>Gagal</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><button type="submit" name="update_transaksi">Update Transaksi</button></td>
            </tr>
        </table>
    </form>
</div>

<?php include 'footer.php'; ?>