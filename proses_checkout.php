<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: adminpanel/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$nama_penerima = mysqli_real_escape_string($mysqli, $_POST['nama_penerima']);
$alamat = mysqli_real_escape_string($mysqli, $_POST['alamat']);
$telepon = mysqli_real_escape_string($mysqli, $_POST['telepon']);
$email = mysqli_real_escape_string($mysqli, $_POST['email']);
$total_harga = (int) $_POST['total_harga'];

try {
    mysqli_begin_transaction($mysqli);

    // Simpan ke orders
    $insertOrder = mysqli_query($mysqli, "
        INSERT INTO orders (user_id, nama_penerima, alamat, telepon, email, total_harga, tanggal, status)
        VALUES ($user_id, '$nama_penerima', '$alamat', '$telepon', '$email', $total_harga, NOW(), 'pending')
    ");

    if (!$insertOrder) {
        throw new Exception("Gagal membuat order.");
    }

    $order_id = mysqli_insert_id($mysqli);

    // Ambil data cart
    $query = mysqli_query($mysqli, "SELECT * FROM cart WHERE user_id = $user_id");
    while ($cart = mysqli_fetch_assoc($query)) {
        $produk_id = $cart['produk_id'];
        $quantity = $cart['quantity'];

        // Ambil harga dan stok produk
        $produkQuery = mysqli_query($mysqli, "SELECT harga, stok FROM produk WHERE id = $produk_id");
        $produkData = mysqli_fetch_assoc($produkQuery);

        if (!$produkData) {
            throw new Exception("Produk tidak ditemukan.");
        }

        $harga = $produkData['harga'];
        $stok_sekarang = $produkData['stok'];

        if ($stok_sekarang < $quantity) {
            throw new Exception("Stok tidak mencukupi untuk produk ID $produk_id.");
        }

        // Simpan ke order_detail
        $insertDetail = mysqli_query($mysqli, "
            INSERT INTO order_detail (order_id, produk_id, quantity, harga)
            VALUES ($order_id, $produk_id, $quantity, $harga)
        ");
        if (!$insertDetail) {
            throw new Exception("Gagal menyimpan detail order.");
        }

        // Kurangi stok
        $stok_baru = $stok_sekarang - $quantity;
        $updateStok = mysqli_query($mysqli, "
            UPDATE produk SET stok = $stok_baru WHERE id = $produk_id
        ");
        if (!$updateStok) {
            throw new Exception("Gagal memperbarui stok produk.");
        }
    }

    // Hapus cart
    mysqli_query($mysqli, "DELETE FROM cart WHERE user_id = $user_id");

    mysqli_commit($mysqli);

    // Sukses
    header("Location: checkout_sukses.php");
    exit;

} catch (Exception $e) {
    mysqli_rollback($mysqli);
    echo "Gagal checkout: " . $e->getMessage();
}
