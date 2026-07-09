<?php 
include '../koneksi.php';
session_start();
if(!isset($_SESSION['admin'])){ header("location:../login.php"); }

// Sinkronisasi otomatis stok_darah table dengan transaksi yang berhasil
if(isset($_POST['sinkronisasi'])){
    $gol_darah_list = ['A', 'B', 'AB', 'O'];
    
    mysqli_query($koneksi, "START TRANSACTION");
    
    try {
        foreach($gol_darah_list as $gol){
            // Hitung jumlah transaksi BERHASIL untuk setiap golongan
            $count_query = mysqli_query($koneksi, "
                SELECT COUNT(*) as total FROM transaksi t
                JOIN pendonor p ON t.id_pendonor = p.id_pendonor
                WHERE p.golongan_darah = '$gol' AND t.status = 'Berhasil'
            ");
            $count_result = mysqli_fetch_array($count_query);
            $jumlah = $count_result['total'];
            
            // Update stok_darah dengan nilai yang benar
            mysqli_query($koneksi, "UPDATE stok_darah SET jumlah_kantong = '$jumlah' WHERE golongan_darah = '$gol'");
        }
        
        mysqli_query($koneksi, "COMMIT");
        $pesan_sukses = "✓ Sinkronisasi stok berhasil! Stok sekarang sesuai dengan transaksi berhasil.";
    } catch(Exception $e) {
        mysqli_query($koneksi, "ROLLBACK");
        $pesan_error = "✗ Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sinkronisasi Stok | Donor Darah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-red: #d32f2f; --sidebar-color: #2c3e50; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; margin: 0; display: flex; }
        .sidebar { width: 260px; height: 100vh; background: var(--sidebar-color); color: white; position: fixed; padding-top: 20px; }
        .sidebar a { display: block; padding: 15px 25px; color: #bdc3c7; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: var(--primary-red); color: white; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .btn { padding: 12px 25px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-primary { background: var(--primary-red); color: white; }
        .btn-primary:hover { background: #b71c1c; }
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #e8f5e9; color: #2e7d32; border-left: 4px solid #4caf50; }
        .alert-danger { background: #ffebee; color: #c32f2f; border-left: 4px solid #f44336; }
        .warning-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th { background: #f5f5f5; padding: 12px; text-align: left; border-bottom: 2px solid #ddd; font-weight: 600; }
        table td { padding: 12px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 style="text-align:center; font-size: 1.1rem;"><i class="fas fa-hand-holding-heart"></i> DONOR DARAH</h2>
        <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="pendonor.php"><i class="fas fa-users"></i> Data Pendonor</a>
        <a href="transaksi.php"><i class="fas fa-exchange-alt"></i> Transaksi Donor</a>
        <a href="stok.php"><i class="fas fa-tint"></i> Stok Darah</a>
        <a href="laporan.php"><i class="fas fa-file-alt"></i> Laporan</a>
        <a href="logout.php" style="margin-top:50px; color:#e74c3c;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="card">
            <h1><i class="fas fa-sync-alt" style="color: var(--primary-red);"></i> Sinkronisasi Stok Darah</h1>
            <p style="color: #666;">Gunakan fitur ini jika ada ketidaksesuaian antara stok darah dengan riwayat transaksi.</p>

            <?php if(isset($pesan_sukses)): ?>
                <div class="alert alert-success"><?= $pesan_sukses; ?></div>
            <?php endif; ?>

            <?php if(isset($pesan_error)): ?>
                <div class="alert alert-danger"><?= $pesan_error; ?></div>
            <?php endif; ?>

            <div class="warning-box">
                <strong>⚠️ Penting:</strong> Sistem stok sekarang menggunakan <strong>real-time calculation</strong> dari transaksi yang berhasil. 
                Tombol di bawah hanya untuk sinkronisasi tabel stok_darah sebagai backup/cache. Stok di halaman <strong>Stok Darah</strong> 
                selalu menampilkan nilai real-time dari transaksi.
            </div>

            <h3 style="margin-top: 30px;">Perbandingan Data</h3>
            <table>
                <thead>
                    <tr>
                        <th>Golongan Darah</th>
                        <th>Stok Saat Ini (stok_darah table)</th>
                        <th>Stok Seharusnya (dari transaksi berhasil)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $gol_darah_list = ['A', 'B', 'AB', 'O'];
                    foreach($gol_darah_list as $gol):
                        // Stok dari table
                        $stok_table = mysqli_query($koneksi, "SELECT jumlah_kantong FROM stok_darah WHERE golongan_darah = '$gol'");
                        $stok_val = mysqli_fetch_array($stok_table);
                        $jumlah_stok = $stok_val['jumlah_kantong'] ?? 0;

                        // Stok dari transaksi berhasil
                        $stok_real = mysqli_query($koneksi, "
                            SELECT COUNT(*) as total FROM transaksi t
                            JOIN pendonor p ON t.id_pendonor = p.id_pendonor
                            WHERE p.golongan_darah = '$gol' AND t.status = 'Berhasil'
                        ");
                        $stok_real_val = mysqli_fetch_array($stok_real);
                        $jumlah_real = $stok_real_val['total'];

                        $match = ($jumlah_stok == $jumlah_real) ? '<span style="color:green;">✓ Sesuai</span>' : '<span style="color:red;">✗ Tidak Sesuai</span>';
                    ?>
                    <tr>
                        <td><strong><?= $gol; ?></strong></td>
                        <td><?= $jumlah_stok; ?></td>
                        <td><?= $jumlah_real; ?></td>
                        <td><?= $match; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form method="POST" style="margin-top: 30px;">
                <button type="submit" name="sinkronisasi" class="btn btn-primary" onclick="return confirm('Yakin ingin sinkronisasi? Stok akan disamakan dengan transaksi berhasil.')">
                    <i class="fas fa-sync"></i> Lakukan Sinkronisasi
                </button>
            </form>
        </div>
    </div>
</body>
</html>
