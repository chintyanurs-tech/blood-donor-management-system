<?php include '../koneksi.php'; ?>
<html>
<head>
    <title>Cetak Laporan</title>
    <style>
        body { font-family: sans-serif; padding: 30px; }
        h2 { text-align: center; color: #d32f2f; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body onload="window.print()">
    <h2>LAPORAN KEGIATAN DONOR DARAH</h2>
    <hr>
    <table>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Pendonor</th>
            <th>Golongan</th>
            <th>Status</th>
        </tr>
        <?php 
        $no = 1;
        $q = mysqli_query($koneksi, "SELECT t.*, p.nama_pendonor, p.golongan_darah FROM transaksi t JOIN pendonor p ON t.id_pendonor = p.id_pendonor ORDER BY t.id_transaksi DESC");
        if ($q && mysqli_num_rows($q) > 0) {
            while($d = mysqli_fetch_assoc($q)){
                echo "<tr>
                    <td>".$no++."</td>
                    <td>".htmlspecialchars($d['tgl_transaksi'])."</td>
                    <td>".htmlspecialchars($d['nama_pendonor'])."</td>
                    <td>".htmlspecialchars($d['golongan_darah'])."</td>
                    <td>".htmlspecialchars($d['status'])."</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='5' style='text-align:center;'>Belum ada data transaksi.</td></tr>";
        } ?>
    </table>
</body>
</html>