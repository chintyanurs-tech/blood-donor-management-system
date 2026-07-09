<?php 
include '../koneksi.php';
session_start();
if(!isset($_SESSION['admin'])){ header("location:../login.php"); }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Transaksi | Donor Darah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-red: #d32f2f; --sidebar-color: #2c3e50; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; margin: 0; display: flex; }
        .sidebar { width: 260px; height: 100vh; background: var(--sidebar-color); color: white; position: fixed; padding-top: 20px; }
        .sidebar a { display: block; padding: 15px 25px; color: #bdc3c7; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: var(--primary-red); color: white; }
        .sidebar i { margin-right: 10px; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .card-form { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 700px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn-save { background: var(--primary-red); color: white; padding: 12px 25px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 style="text-align:center; font-size: 1.1rem;"><i class="fas fa-hand-holding-heart"></i> DONOR DARAH</h2>
        <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="pendonor.php"><i class="fas fa-users"></i> Data Pendonor</a>
        <a href="transaksi.php" class="active"><i class="fas fa-exchange-alt"></i> Transaksi Donor</a>
        <a href="stok.php"><i class="fas fa-tint"></i> Stok Darah</a>
        <a href="laporan.php"><i class="fas fa-file-alt"></i> Laporan</a>
        <a href="logout.php" style="margin-top:50px; color:#e74c3c;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h1><i class="fas fa-plus-circle" style="color: var(--primary-red);"></i> Input Riwayat Donor</h1>
        <div class="card-form">
            <form method="POST" action="transaksiUpdate.php">
                <div class="form-group">
                    <label>Pilih Pendonor</label>
                    <select name="id_pendonor" required>
                        <option value="">-- Pilih Nama Pendonor --</option>
                        <?php 
                        $p = mysqli_query($koneksi, "SELECT * FROM pendonor");
                        while($dp = mysqli_fetch_array($p)){
                            echo "<option value='$dp[id_pendonor]'>$dp[nama_pendonor] (Gol: $dp[golongan_darah])</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Transaksi</label>
                    <input type="date" name="tgl_transaksi" value="<?= date('Y-m-d'); ?>" required>
                </div>
                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Tekanan Darah (Tensi)</label>
                        <input type="text" name="tekanan_darah" placeholder="120/80" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Berat Badan (kg)</label>
                        <input type="number" name="berat_badan" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Status Kelayakan</label>
                    <select name="status" required>
                        <option value="Berhasil">Berhasil (Layak Donor)</option>
                        <option value="Gagal">Gagal (Tidak Layak)</option>
                    </select>
                </div>
                <button type="submit" name="simpan_transaksi" class="btn-save">Simpan Transaksi</button>
            </form>
        </div>
    </div>
</body>
</html>