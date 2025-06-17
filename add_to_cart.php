<?php
session_start();
require "koneksi.php"; // atau koneksi kamu

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    die("Anda belum login. <a href='adminpanel/login.php'>Login di sini</a>");
}

// Ambil ID user dan produk
$user_id = $_SESSION['user_id'];
$produk_id = $_GET['produk_id'];

// Cek apakah produk ini sudah ada di cart user
$query = mysqli_query($mysqli, "SELECT * FROM cart WHERE user_id='$user_id' AND produk_id='$produk_id'");
$data = mysqli_fetch_assoc($query);

if ($data) {
    // Produk sudah ada, update quantity +1
    mysqli_query($mysqli, "UPDATE cart SET quantity = quantity + 1 WHERE user_id='$user_id' AND produk_id='$produk_id'");
} else {
    // Produk belum ada, insert baru
    mysqli_query($mysqli, "INSERT INTO cart (user_id, produk_id, quantity) VALUES ('$user_id', '$produk_id', 1)");
}

// Redirect kembali ke halaman produk atau cart
header("Location: cart.php");
exit();
?>
