<?php 
include '../koneksi.php';
session_start();
if(!isset($_SESSION['admin'])){ header("location:../login.php"); }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Donor | Donor Darah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-red: #d32f2f; --sidebar-color: #2c3e50; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; margin: 0; display: flex; }
        
        /* Sidebar */
        .sidebar { width: 260px; height: 100vh; background: var(--sidebar-color); color: white; position: fixed; padding-top: 20px; }
        .sidebar a { display: block; padding: 15px 25px; color: #bdc3c7; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: var(--primary-red); color: white; }
        .sidebar i { margin-right: 10px; }

        /* Content */
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        .card-table { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th { text-align: left; padding: 15px 12px; border-bottom: 2px solid #eee; color: #7f8c8d; font-size: 0.85rem; text-transform: uppercase; }
        table td { padding: 15px 12px; border-bottom: 1px solid #eee; font-size: 0.9rem; color: #333; }
        
        .btn-tambah { background: var(--primary-red); color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; font-weight: 600; transition: 0.3s; }
        .btn-tambah:hover { background: #b71c1c; box-shadow: 0 4px 10px rgba(211, 47, 47, 0.3); }

        /* Badge Status */
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .badge-success { background: #e8f5e9; color: #2e7d32; }
        .badge-danger { background: #ffebee; color: #c32f2f; }

        .btn-action { text-decoration: none; margin-right: 10px; font-size: 1rem; transition: 0.2s; }
        .btn-edit { color: #f39c12; }
        .btn-del { color: #e74c3c; }
        .btn-action:hover { opacity: 0.7; }
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
        <div class="header">
            <h1>Riwayat Transaksi Donor</h1>
            <a href="transaksiAdd.php" class="btn-tambah"><i class="fas fa-plus-circle"></i> Catat Transaksi</a>
        </div>

        <div class="card-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Pendonor</th>
                        <th>Tensi</th>
                        <th>BB</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $sql = "SELECT t.*, p.nama_pendonor FROM transaksi t 
                            JOIN pendonor p ON t.id_pendonor = p.id_pendonor 
                            ORDER BY t.id_transaksi DESC";
                    $query = mysqli_query($koneksi, $sql);
                    if ($query && mysqli_num_rows($query) > 0) {
                        while($t = mysqli_fetch_assoc($query)){
                            $statusClass = ($t['status'] == 'Berhasil') ? 'badge-success' : 'badge-danger';
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= date('d M Y', strtotime($t['tgl_transaksi'])); ?></td>
                                <td><strong><?= htmlspecialchars($t['nama_pendonor']); ?></strong></td>
                                <td><?= htmlspecialchars($t['tekanan_darah']); ?></td>
                                <td><?= htmlspecialchars($t['berat_badan']); ?> kg</td>
                                <td><span class="badge <?= $statusClass; ?>"><?= htmlspecialchars($t['status']); ?></span></td>
                                <td>
                                    <a href="transaksiEdit.php?id=<?= $t['id_transaksi']; ?>" class="btn-action btn-edit"><i class="fas fa-edit"></i></a>
                                    <a href="transaksiDel.php?id=<?= $t['id_transaksi']; ?>" class="btn-action btn-del" onclick="return confirm('Hapus riwayat ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php 
                        }
                    } else {
                        echo '<tr><td colspan="7" style="text-align:center; color:#999;">Belum ada transaksi donor.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>