<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    echo "<script>
        window.location.href = 'adminpanel/login.php';
    </script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$produk_id = $_GET['produk_id'];
$jumlah = isset($_GET['jumlah']) ? (int)$_GET['jumlah'] : 1;

// Ambil data stok produk
$queryStok = mysqli_query($mysqli, "SELECT stok FROM produk WHERE id='$produk_id'");
$produk = mysqli_fetch_assoc($queryStok);
$stok = $produk['stok'];

// Fungsi tampilkan SweetAlert dan redirect
function alertRedirect($pesan, $redirect) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: '$pesan',
        }).then(() => {
            window.location.href = '$redirect';
        });
    </script>";
    exit;
}

// Validasi stok tidak cukup
if ($jumlah > $stok) {
    alertRedirect('Jumlah melebihi stok yang tersedia!', "produk_detail.php?nama=$_GET[nama]");
}

// Cek apakah produk sudah di cart
$query = mysqli_query($mysqli, "SELECT * FROM cart WHERE user_id='$user_id' AND produk_id='$produk_id'");
$data = mysqli_fetch_assoc($query);

if ($data) {
    $jumlahBaru = $data['quantity'] + $jumlah;
    if ($jumlahBaru > $stok) {
        alertRedirect('Total item melebihi stok yang tersedia!', "produk_detail.php?nama=$_GET[nama]");
    }
    mysqli_query($mysqli, "UPDATE cart SET quantity = quantity + $jumlah WHERE user_id='$user_id' AND produk_id='$produk_id'");
} else {
    mysqli_query($mysqli, "INSERT INTO cart (user_id, produk_id, quantity) VALUES ('$user_id', '$produk_id', '$jumlah')");
}

// Redirect sukses ke cart
header("Location: cart.php");
exit();
?>
