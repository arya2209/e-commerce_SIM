<?php
session_start();
require "koneksi.php";

$nama = htmlspecialchars($_GET['nama']);
$queryProduk = mysqli_query($mysqli, "SELECT * FROM produk WHERE nama='$nama'");
$produk = mysqli_fetch_array($queryProduk);

$queryProdukTerkait = mysqli_query($mysqli, "SELECT * FROM produk WHERE kategori_id='$produk[kategori_id]' AND id!='$produk[id]' LIMIT 4");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Luxia | Detail Produk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- Detail Produk -->
<div class="container py-5">
    <div class="row">
        <div class="col-lg-5 mb-4">
            <img src="image/<?= $produk['foto']; ?>" class="img-fluid" alt="<?= $produk['nama']; ?>">
        </div>
        <div class="col-lg-6 offset-lg-1">
            <h2><?= $produk['nama']; ?></h2>
            <p><?= $produk['detail']; ?></p>
            <h4 class="text-danger">Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></h4>
            <p>Stok tersedia: <strong><?= $produk['stok']; ?></strong></p>

            <!-- Form Tambah ke Keranjang -->
            <form action="add_to_cart.php" method="GET">
                <input type="hidden" name="produk_id" value="<?= $produk['id']; ?>">
                <input type="hidden" name="nama" value="<?= $produk['nama']; ?>">

                <div class="input-group mb-3" style="max-width: 120px;">
                    <button class="btn btn-outline-secondary" type="button" id="btn-minus">-</button>
                    <input type="text" name="jumlah" id="jumlah" class="form-control text-center" value="1" data-stok="<?= $produk['stok']; ?>" readonly>
                    <button class="btn btn-outline-secondary" type="button" id="btn-plus">+</button>
                </div>

                <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
            </form>
        </div>
    </div>
</div>


<!-- JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btnMinus = document.getElementById("btn-minus");
        const btnPlus = document.getElementById("btn-plus");
        const inputJumlah = document.getElementById("jumlah");
        const stok = parseInt(inputJumlah.dataset.stok);

        btnMinus.addEventListener("click", function () {
            let jumlah = parseInt(inputJumlah.value);
            if (jumlah > 1) inputJumlah.value = jumlah - 1;
        });

        btnPlus.addEventListener("click", function () {
            let jumlah = parseInt(inputJumlah.value);
            if (jumlah < stok) inputJumlah.value = jumlah + 1;
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
