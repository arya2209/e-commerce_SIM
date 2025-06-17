<?php
    session_start();
    require "koneksi.php";

    $nama =htmlspecialchars($_GET['nama']);

    $queryProduk = mysqli_query($mysqli, "SELECT * FROM produk WHERE nama='$nama'");
    $produk = mysqli_fetch_Array($queryProduk);

    $queryProdukTerkait = mysqli_query($mysqli, "SELECT * FROM produk WHERE kategori_id='$produk[kategori_id]' AND id!='$produk[id]' LIMIT 4");

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <!-- Bootstrap -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
     <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxia | Detail Item</title>
</head>
<body>

    <!-- detail produk -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-5">
                    <img src="image/<?php echo $produk['foto']; ?>" class="w-100" alt="">
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <h1><?php echo $produk['nama']; ?></h1>
                    <p class="fs-5"><?php echo $produk['detail']; ?></p>
                    <p class="text-harga">
                    Rp <?php echo $produk['harga']; ?>
                    </p>
                    <p class="fs-5">Stok : <strong><?php echo $produk['stok']; ?></strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- produk terkait -->
    <div class="container-fluid py-5 warna2">
        <div class="container">
            <h2 class="text-center text-white mb-5">Produk terkait</h2>

            <div class="row">
                <?php while($data = mysqli_fetch_Array($queryProdukTerkait)){ ?>
                <div class="col-md-6 col-lg-3 mb-3">
                    <a href="produk_detail.php?nama=<?php echo $data['nama'] ?>">
                        <img src="image/<?php echo $data['foto']; ?>" class="img-fluid img-thumbnail produk-terkait-image" 
                        alt="">
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

     <!-- footer -->
     <?php require "footer.php" ?>    

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
</body>
</html>