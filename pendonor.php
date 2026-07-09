<?php 
include '../koneksi.php';
session_start();
if(!isset($_SESSION['admin'])){ header("location:../login.php"); }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pendonor | Donor Darah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Mengambil tema yang sama dengan dashboard */
        :root { --primary-red: #d32f2f; --sidebar-color: #2c3e50; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; margin: 0; display: flex; }
        
        .sidebar { width: 260px; height: 100vh; background: var(--sidebar-color); color: white; position: fixed; padding-top: 20px; }
        .sidebar a { display: block; padding: 15px 25px; color: #bdc3c7; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: var(--primary-red); color: white; }
        .sidebar i { margin-right: 10px; }

        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        /* Table Styling */
        .card-table { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th { text-align: left; padding: 12px; border-bottom: 2px solid #eee; color: #7f8c8d; font-size: 0.85rem; text-transform: uppercase; }
        table td { padding: 15px 12px; border-bottom: 1px solid #eee; font-size: 0.95rem; }
        
        .btn-tambah { background: var(--primary-red); color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: 600; transition: 0.3s; }
        .btn-tambah:hover { background: #b71c1c; }
        
        .badge-gol { background: #ffebee; color: #d32f2f; padding: 4px 10px; border-radius: 5px; font-weight: 700; }
        .btn-edit { color: #f39c12; margin-right: 10px; text-decoration: none; }
        .btn-hapus { color: #e74c3c; text-decoration: none; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2 style="text-align:center; font-size: 1.1rem;"><i class="fas fa-hand-holding-heart"></i> DONOR DARAH</h2>
        <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="pendonor.php" class="active"><i class="fas fa-users"></i> Data Pendonor</a>
        <a href="transaksi.php"><i class="fas fa-exchange-alt"></i> Transaksi Donor</a>
        <a href="stok.php"><i class="fas fa-tint"></i> Stok Darah</a>
        <a href="laporan.php"><i class="fas fa-file-alt"></i> Laporan</a>
        <a href="logout.php" style="margin-top:50px; color:#e74c3c;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Data Pendonor</h1>
            <a href="addPendonor.php" class="btn-tambah"><i class="fas fa-plus"></i> Tambah Pendonor</a>
        </div>

        <div class="card-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>NIK</th>
                        <th>JK</th>
                        <th>Gol. Darah</th>
                        <th>No. Telp</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $query = mysqli_query($koneksi, "SELECT * FROM pendonor ORDER BY id_pendonor DESC");
                    if ($query && mysqli_num_rows($query) > 0) {
                        while($d = mysqli_fetch_assoc($query)){
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><strong><?= htmlspecialchars($d['nama']); ?></strong></td>
                                <td><?= htmlspecialchars($d['nik']); ?></td>
                                <td><?= htmlspecialchars($d['jenis_kelamin']); ?></td>
                                <td><span class="badge-gol"><?= htmlspecialchars($d['gol_darah']); ?></span></td>
                                <td><?= htmlspecialchars($d['no_hp']); ?></td>
                                <td><?= htmlspecialchars($d['alamat']); ?></td>
                                <td>
                                    <a href="editPendonor.php?id=<?= $d['id_pendonor']; ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                    <a href="delPendonor.php?id=<?= $d['id_pendonor']; ?>" class="btn-hapus" onclick="return confirm('Hapus data ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php 
                        }
                    } else {
                        echo '<tr><td colspan="8" style="text-align:center; color:#999;">Belum ada data pendonor.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>