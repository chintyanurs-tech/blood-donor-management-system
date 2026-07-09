<?php
require __DIR__ . '/koneksi.php';
session_start();

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $passwordInput = trim($_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' LIMIT 1");

    if ($query && mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $storedPassword = $data['password'] ?? '';

        $isValid = false;
        if (!empty($storedPassword)) {
            $isValid = (
                $storedPassword === md5($passwordInput) ||
                $storedPassword === $passwordInput ||
                (function_exists('password_verify') && password_verify($passwordInput, $storedPassword))
            );
        }

        if ($isValid) {
            session_regenerate_id(true);
            $_SESSION['admin'] = $data['nama_lengkap'] ?: $data['username'];
            $_SESSION['admin_id'] = $data['id_admin'];
            header("Location: admin/index.php");
            exit();
        }
    }

    $error = "Username atau Password salah!";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Donor Darah</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('img/img.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container i.fa-droplet {
            font-size: 50px;
            color: #d32f2f;
            margin-bottom: 10px;
        }

        .login-container h2 {
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }

        .login-container p {
            color: #777;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 40px;
            color: #aaa;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: #d32f2f;
            box-shadow: 0 0 8px rgba(211, 47, 47, 0.2);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #d32f2f;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #b71c1c;
            transform: translateY(-2px);
        }

        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <i class="fa-solid fa-droplet"></i>
    <h2>Portal Admin</h2>
    <p>Sistem Informasi Donor Darah</p>

    <?php if(isset($error)): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="form-group">
            <label>Username</label>
            <i class="fa fa-user"></i>
            <input type="text" name="username" placeholder="Masukkan username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <i class="fa fa-lock"></i>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>
        <button type="submit" name="login" class="btn-login">LOGIN</button>
    </form>
</div>

</body>
</html>