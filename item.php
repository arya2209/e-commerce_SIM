<?php
session_start();
require "koneksi.php"; // Harus di paling atas file

$queryKategori = mysqli_query($mysqli, "SELECT * FROM kategori");

// get produk by nama produk/keyword
if (isset($_GET['keyword'])) {
    $queryProduk = mysqli_query($mysqli, "SELECT * FROM produk WHERE nama LIKE '%" . $_GET['keyword'] . "%' ");
}
// get produk by kategori
else if (isset($_GET['kategori'])) {
    $queryGetKategoriId = mysqli_query($mysqli, "SELECT id FROM kategori WHERE nama='$_GET[kategori]'");
    $kategoriId = mysqli_fetch_Array($queryGetKategoriId);

    $queryProduk = mysqli_query($mysqli, "SELECT * FROM produk WHERE kategori_id='$kategoriId[id]'");
}
// get produk by default
else {
    $queryProduk = mysqli_query($mysqli, "SELECT * FROM produk");
}

$countData = mysqli_num_rows($queryProduk);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Produk</title>
    <link rel="stylesheet" href="item.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body class="bg-light">

    <!-- Navbar -->
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg shadow-sm py-3 fixed-top">
        <div class="container">
            <!-- Logo dan Brand -->
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="image/luxia_fix.png" alt="Luxia Logo" width="100" height="43" class="">
            </a>

            <!-- Toggle button for mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation links -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center gap-4">
                    <li class="nav-item">
                        <strong><a class="nav-link text-white fs-5" href="home.php">Beranda</a></strong>
                    </li>
                    <li class="nav-item">
                        <strong><a class="nav-link text-white fs-5" href="item.php">Produk</a></strong>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fs-5" href="cart.php">
                            <strong><i class="fas fa-cart-shopping me-1"></i></strong>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['nama'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white " style="cursor:pointer;"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?= $_SESSION['nama']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="./adminpanel/index.php">Dashboard Admin</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="./adminpanel/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="adminpanel/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <div class="container py-5 mt-5">
        <div class="row">
            <!-- Sidebar Kategori -->
            <div class="col-lg-3 mb-4">
                <h4 class="text-white mb-2">Kategori</h4>
                <div class="list-group" style="margin-top:33px">
                    <?php while ($kategori = mysqli_fetch_array($queryKategori)) { ?>
                        <a href="item.php?kategori=<?php echo $kategori['nama']; ?>"
                            class="list-group-item list-group-item-action">
                            <?php echo $kategori['nama']; ?>
                        </a>
                    <?php } ?>
                </div>
            </div>

            <!-- Daftar Produk -->
            <div class="col-lg-9">
                <h3 class="text-white text-center mb-4 fs-2" style="font-weight:bold;">Produk</h3>
                <div class="row">
                    <?php if ($countData < 1) { ?>
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                Produk yang Anda cari tidak tersedia.
                            </div>
                        </div>
                    <?php } ?>

                    <?php while ($produk = mysqli_fetch_array($queryProduk)) { ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm text-white"
                                style="background-color: rgba(255, 255, 255, 0.3); border: 2px solid rgba(255, 255, 255, 0.62); padding: 25px 20px 10px 20px; border-radius: 20px; backdrop-filter: blur(4px); transition: transform 0.3s ease, background-color 0.3s ease;">
                                <img src="image/<?php echo $produk['foto']; ?>" class="card-img-top rounded-3"
                                    alt="<?php echo $produk['nama']; ?>" />
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $produk['nama']; ?></h5>
                                    <p class="card-text text-truncate"><?php echo $produk['detail']; ?></p>
                                    <p class="card-text fw-bold text-primary">Rp <?php echo $produk['harga']; ?></p>
                                    <a href="item_detail.php?nama=<?php echo $produk['nama']; ?>"
                                        class="btn btn-outline-light btn-sm w-100 mb-2">Lihat Detail</a>
                                    <a href="add_to_cart.php?produk_id=<?= $produk['id'] ?>"
                                        class="btn btn-warning btn-sm w-100">
                                        <i class="fas fa-cart-shopping me-1"></i>Tambah ke Keranjang
                                    </a>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>