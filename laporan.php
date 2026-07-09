<?php 
include '../koneksi.php';
session_start();
if(!isset($_SESSION['admin'])){ header("location:../login.php"); }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan | Donor Darah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-red: #d32f2f; --sidebar-color: #2c3e50; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; margin: 0; display: flex; }
        .sidebar { width: 260px; height: 100vh; background: var(--sidebar-color); color: white; position: fixed; padding-top: 20px; }
        .sidebar a { display: block; padding: 15px 25px; color: #bdc3c7; text-decoration: none; }
        .sidebar a.active { background: var(--primary-red); color: white; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .card-table { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .btn-print { background: #27ae60; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-block; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        table th { text-align: left; padding: 12px; border-bottom: 2px solid #eee; }
        table td { padding: 12px; border-bottom: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 style="text-align:center; font-size: 1.1rem;"><i class="fas fa-hand-holding-heart"></i> DONOR DARAH</h2>
        <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="pendonor.php"><i class="fas fa-users"></i> Data Pendonor</a>
        <a href="transaksi.php"><i class="fas fa-exchange-alt"></i> Transaksi Donor</a>
        <a href="stok.php"><i class="fas fa-tint"></i> Stok Darah</a>
        <a href="laporan.php" class="active"><i class="fas fa-file-alt"></i> Laporan</a>
        <a href="logout.php" style="margin-top:50px; color:#e74c3c;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h1>Laporan Rekapitulasi</h1>
        <a href="laporanCetak.php" target="_blank" class="btn-print"><i class="fas fa-print"></i> Cetak Laporan ke PDF/Printer</a>
        <div class="card-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Pendonor</th>
                        <th>Gol</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $query = mysqli_query($koneksi, "SELECT t.*, p.nama_pendonor, p.golongan_darah FROM transaksi t JOIN pendonor p ON t.id_pendonor = p.id_pendonor ORDER BY t.id_transaksi DESC");
                    if ($query && mysqli_num_rows($query) > 0) {
                        while($d = mysqli_fetch_assoc($query)){
                            echo "<tr>
                                <td>".$no++."</td>
                                <td>".htmlspecialchars($d['tgl_transaksi'])."</td>
                                <td>".htmlspecialchars($d['nama_pendonor'])."</td>
                                <td>".htmlspecialchars($d['golongan_darah'])."</td>
                                <td>".htmlspecialchars($d['status'])."</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; color:#999;'>Belum ada data transaksi.</td></tr>";
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>