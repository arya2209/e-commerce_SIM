<?php
session_start();
require "koneksi.php";

$nama = htmlspecialchars($_GET['nama']);

$queryProduk = mysqli_query($mysqli, "SELECT * FROM produk WHERE nama='$nama'");
$produk = mysqli_fetch_Array($queryProduk);

$queryProdukTerkait = mysqli_query($mysqli, "SELECT * FROM produk WHERE kategori_id='$produk[kategori_id]' AND id!='$produk[id]' LIMIT 4");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxia | Detail Item</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <!-- Font Awesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light">

    <!-- Detail Produk -->
    <div class="container py-5">
        <div class="row align-items-center">
            <!-- Gambar Produk -->
            <div class="col-lg-5 mb-4">
                <div class="border rounded shadow-sm overflow-hidden">
                    <img src="image/<?php echo $produk['foto']; ?>" class="img-fluid w-100"
                        alt="<?php echo $produk['nama']; ?>">
                </div>
            </div>

            <!-- Informasi Produk -->
            <div class="col-lg-6 offset-lg-1">
                <h2 class="mb-3"><?php echo $produk['nama']; ?></h2>
                <p class="fs-5 mb-4 text-muted"><?php echo $produk['detail']; ?></p>

                <h4 class="text-primary fw-bold mb-3">Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?>
                </h4>

                <p class="fs-5">Stok: <span class="fw-semibold text-dark"><?php echo $produk['stok']; ?></span></p>

                <div class="mt-4">
                    <a href="add_to_cart.php?produk_id=<?= $produk['id'] ?>" class="btn btn-warning btn-lg me-2">
                        <i class="fas fa-cart-plus me-1"></i> Tambah ke Keranjang
                    </a>
                    <a href="item.php" class="btn btn-outline-secondary btn-lg">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
        </script>
</body>

</html>