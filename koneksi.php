<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "dbdonordarah"; // Sesuaikan nama database

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>