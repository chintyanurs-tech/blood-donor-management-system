<?php 
include '../koneksi.php';
session_start();
if(!isset($_SESSION['admin'])){ header("location:../login.php"); }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stok Darah | Donor Darah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-red: #d32f2f; --sidebar-color: #2c3e50; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; margin: 0; display: flex; }
        
        /* Sidebar Navigation */
        .sidebar { width: 260px; height: 100vh; background: var(--sidebar-color); color: white; position: fixed; padding-top: 20px; }
        .sidebar a { display: block; padding: 15px 25px; color: #bdc3c7; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: var(--primary-red); color: white; }
        .sidebar i { margin-right: 10px; }

        /* Main Content */
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .header { margin-bottom: 30px; }
        .header h1 { color: #333; font-size: 1.8rem; }

        /* Grid for Blood Inventory */
        .blood-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        /* Blood Card Styling */
        .blood-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-top: 6px solid var(--primary-red);
            transition: 0.3s;
        }

        .blood-card:hover { transform: translateY(-5px); }

        .blood-type {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-red);
            margin: 10px 0;
        }

        .stock-count {
            font-size: 1.2rem;
            color: #7f8c8d;
            font-weight: 400;
        }

        .stock-number {
            font-size: 2rem;
            color: #2c3e50;
            font-weight: 700;
        }

        .update-time {
            font-size: 0.75rem;
            color: #bdc3c7;
            margin-top: 15px;
        }

        /* Table View for Details */
        .card-table { background: white; padding: 25px; border-radius: 12px; margin-top: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; }
        table th { text-align: left; padding: 12px; border-bottom: 2px solid #eee; color: #7f8c8d; }
        table td { padding: 12px; border-bottom: 1px solid #eee; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2 style="text-align:center; font-size: 1.1rem;"><i class="fas fa-hand-holding-heart"></i> DONOR DARAH</h2>
        <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="pendonor.php"><i class="fas fa-users"></i> Data Pendonor</a>
        <a href="transaksi.php"><i class="fas fa-exchange-alt"></i> Transaksi Donor</a>
        <a href="stok.php" class="active"><i class="fas fa-tint"></i> Stok Darah</a>
        <a href="laporan.php"><i class="fas fa-file-alt"></i> Laporan</a>
        <a href="logout.php" style="margin-top:50px; color:#e74c3c;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1><i class="fas fa-boxes" style="color: var(--primary-red);"></i> Monitoring Stok Darah</h1>
            <p style="color: #7f8c8d;">Pantau ketersediaan kantong darah secara real-time (Hitung dari Transaksi Berhasil).</p>
            <a href="stokSinkronisasi.php" style="color: #d32f2f; text-decoration: none; margin-top: 10px; display: inline-block;">
                <i class="fas fa-sync-alt"></i> Sinkronisasi Stok 
            </a>
        </div>

        <div class="blood-grid">
            <?php 
            $gol_darah_list = ['A', 'B', 'AB', 'O'];
            
            foreach($gol_darah_list as $gol){
                $count_query = mysqli_query($koneksi, "
                    SELECT COUNT(*) as total FROM transaksi t
                    JOIN pendonor p ON t.id_pendonor = p.id_pendonor
                    WHERE p.golongan_darah = '$gol' AND t.status = 'Berhasil'
                ");
                $count_result = $count_query && mysqli_num_rows($count_query) > 0 ? mysqli_fetch_assoc($count_query) : null;
                $jumlah = $count_result && isset($count_result['total']) ? (int) $count_result['total'] : 0;
                ?>
                <div class="blood-card">
                    <div class="stock-count">Golongan Darah</div>
                    <div class="blood-type"><?= $gol; ?></div>
                    <div class="stock-number"><?= $jumlah; ?> <span style="font-size: 0.9rem; font-weight: 400;">Kantong</span></div>
                    <div class="update-time">Terakhir diperbarui: <?= date('d/m/Y H:i:s'); ?></div>
                </div>
                <?php 
            }
            ?>
        </div>

        <div class="card-table">
            <h3>Detail Inventaris (Real-Time)</h3>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Golongan Darah</th>
                        <th>Jumlah Kantong</th>
                        <th>Transaksi Berhasil</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $gol_darah_list = ['A', 'B', 'AB', 'O'];
                    
                    foreach($gol_darah_list as $gol){
                        $count_query = mysqli_query($koneksi, "
                            SELECT COUNT(*) as total FROM transaksi t
                            JOIN pendonor p ON t.id_pendonor = p.id_pendonor
                            WHERE p.golongan_darah = '$gol' AND t.status = 'Berhasil'
                        ");
                        $count_result = $count_query && mysqli_num_rows($count_query) > 0 ? mysqli_fetch_assoc($count_query) : null;
                        $jumlah = $count_result && isset($count_result['total']) ? (int) $count_result['total'] : 0;
                        
                        $ket = ($jumlah < 5) ? "<span style='color:red; font-weight:bold;'>⚠️ Stok Hampir Habis!</span>" : "<span style='color:green;'>✓ Tersedia</span>";
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><strong><?= $gol; ?></strong></td>
                            <td><?= $jumlah; ?> Kantong</td>
                            <td>
                                <a href="javascript:void(0);" onclick="showTransactionDetails('<?= $gol; ?>')" style="color: #2196F3; cursor: pointer; text-decoration: underline;">
                                    Lihat Detail
                                </a>
                            </td>
                            <td><?= $ket; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="card-table" style="margin-top: 30px;">
            <h3>Log Transaksi Berhasil (30 Hari Terakhir)</h3>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Pendonor</th>
                        <th>Golongan Darah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $sql = "SELECT t.*, p.nama_pendonor, p.golongan_darah FROM transaksi t 
                            JOIN pendonor p ON t.id_pendonor = p.id_pendonor 
                            WHERE t.status = 'Berhasil' AND t.tgl_transaksi >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                            ORDER BY t.tgl_transaksi DESC LIMIT 50";
                    $query = mysqli_query($koneksi, $sql);
                    
                    if(mysqli_num_rows($query) > 0){
                        while($t = mysqli_fetch_array($query)){
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= date('d M Y', strtotime($t['tgl_transaksi'])); ?></td>
                                <td><?= $t['nama_pendonor']; ?></td>
                                <td><strong><?= $t['golongan_darah']; ?></strong></td>
                                <td><span class="badge badge-success">✓ Berhasil</span></td>
                            </tr>
                            <?php 
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; color:#999;'>Belum ada transaksi berhasil</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function showTransactionDetails(gol) {
            alert('Detail transaksi untuk golongan darah ' + gol + ' dapat dilihat di halaman Transaksi Donor');
        }
    </script>

</body>
</html>