<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: adminpanel/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$nama_penerima = $_POST['nama_penerima'];
$alamat = $_POST['alamat'];
$total_harga = $_POST['total_harga'];

// Simpan ke tabel orders
mysqli_query($mysqli, "INSERT INTO orders (user_id, nama_penerima, alamat, total_harga, tanggal, status)
                       VALUES ($user_id, '$nama_penerima', '$alamat', $total_harga, NOW(), 'pending')");


// Ambil ID order terakhir
$order_id = mysqli_insert_id($mysqli);

// Ambil data cart
$query = mysqli_query($mysqli, "SELECT * FROM cart WHERE user_id = $user_id");
while ($cart = mysqli_fetch_assoc($query)) {
    $produk_id = $cart['produk_id'];
    $quantity = $cart['quantity'];
    $harga = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT harga FROM produk WHERE id = $produk_id"))['harga'];

    mysqli_query($mysqli, "INSERT INTO order_detail (order_id, produk_id, quantity, harga)
                           VALUES ($order_id, $produk_id, $quantity, $harga)");
}

// Hapus isi cart
mysqli_query($mysqli, "DELETE FROM cart WHERE user_id = $user_id");

// Redirect ke halaman sukses
header("Location: checkout_sukses.php");
exit;
