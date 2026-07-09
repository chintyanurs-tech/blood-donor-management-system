<?php 
include '../koneksi.php';
session_start();
if(!isset($_SESSION['admin'])){ header("location:../login.php"); }

// 1. Ambil ID dari URL dan ambil data lamanya
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM pendonor WHERE id_pendonor='$id'");
$d = mysqli_fetch_array($data);

// 2. Logika Update Data
if (isset($_POST['update'])) {
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama_pendonor']);
    $nik     = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $jk      = $_POST['jenis_kelamin'];
    $gol     = $_POST['golongan_darah'];
    $alamat  = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $telp    = $_POST['no_telp'];

    $query = mysqli_query($koneksi, "UPDATE pendonor SET 
                                     nama_pendonor='$nama', 
                                     nik='$nik',
                                     jenis_kelamin='$jk', 
                                     golongan_darah='$gol', 
                                     alamat='$alamat', 
                                     no_telp='$telp' 
                                     WHERE id_pendonor='$id'");

    if ($query) {
        header("Location: pendonor.php?pesan=update");
        exit();
    } else {
        echo "<script>alert('Gagal Update Data');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pendonor | Donor Darah</title>
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
        .card-form { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 600px; }
        
        h1 { color: #333; margin-bottom: 20px; font-size: 1.5rem; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        
        input[type="text"], input[type="number"], select, textarea {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; outline: none; transition: 0.3s;
        }
        input:focus, select:focus, textarea:focus { border-color: var(--primary-red); box-shadow: 0 0 8px rgba(211, 47, 47, 0.1); }
        
        .radio-group { display: flex; gap: 20px; padding: 10px 0; }
        
        .btn-update { background: #f39c12; color: white; padding: 12px 25px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-update:hover { background: #e67e22; transform: translateY(-2px); }
        .btn-back { color: #7f8c8d; text-decoration: none; margin-left: 15px; font-size: 0.9rem; }
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
        <h1><i class="fas fa-user-edit" style="color: #f39c12;"></i> Edit Data Pendonor</h1>

        <div class="card-form">
            <form method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_pendonor" value="<?= $d['nama_pendonor']; ?>" required>
                </div>

                <div class="form-group">
                    <label>NIK</label>
                    <input type="text" name="nik" value="<?= $d['nik']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <div class="radio-group">
                        <label style="font-weight: 400;"><input type="radio" name="jenis_kelamin" value="L" <?= ($d['jenis_kelamin']=='L') ? 'checked' : ''; ?> required> Laki-laki</label>
                        <label style="font-weight: 400;"><input type="radio" name="jenis_kelamin" value="P" <?= ($d['jenis_kelamin']=='P') ? 'checked' : ''; ?> required> Perempuan</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Golongan Darah</label>
                    <select name="golongan_darah" required>
                        <option value="A" <?= ($d['golongan_darah']=='A') ? 'selected' : ''; ?>>A</option>
                        <option value="B" <?= ($d['golongan_darah']=='B') ? 'selected' : ''; ?>>B</option>
                        <option value="AB" <?= ($d['golongan_darah']=='AB') ? 'selected' : ''; ?>>AB</option>
                        <option value="O" <?= ($d['golongan_darah']=='O') ? 'selected' : ''; ?>>O</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="number" name="no_telp" value="<?= $d['no_telp']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" required><?= $d['alamat']; ?></textarea>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" name="update" class="btn-update">Perbarui Data</button>
                    <a href="pendonor.php" class="btn-back">Batal</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>