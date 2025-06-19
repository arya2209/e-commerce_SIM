<?php
session_start();
require "koneksi.php"; // Harus di paling atas file

    $queryKategori = mysqli_query($mysqli, "SELECT * FROM kategori");

    // get produk by nama produk/keyword
    if(isset($_GET['keyword'])){
        $queryProduk = mysqli_query($mysqli, "SELECT * FROM produk WHERE nama LIKE '%" .$_GET['keyword']. "%' ");
    }
    // get produk by kategori
    else if (isset($_GET['kategori'])){
        $queryGetKategoriId = mysqli_query($mysqli, "SELECT id FROM kategori WHERE nama='$_GET[kategori]'");
        $kategoriId = mysqli_fetch_Array($queryGetKategoriId);

        $queryProduk = mysqli_query($mysqli, "SELECT * FROM produk WHERE kategori_id='$kategoriId[id]'");
    }
    // get produk by default
    else{
        $queryProduk = mysqli_query($mysqli, "SELECT * FROM produk");
    }

    $countData = mysqli_num_rows($queryProduk);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item</title>
     <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<style>
     body {
            background-color:#e7f2f8;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
       
        .navbar {
            background: rgba(255, 255, 255, 0.8);
        }
        
        .navbar-nav .nav-link {
            transition: color 0.3s;
        }
        
        .navbar-nav .nav-link:hover {
            color: blue !important;
        }
        .no-decoration {
    text-decoration: none;
    color: white;
}

</style>
<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm py-3 sticky-top bg-light">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Luxia</a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav align-items-center gap-4">
                <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="item.php">Item</a></li>
                <li class="nav-item"><a class="nav-link" href="order.php">Order</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">
                <i class="fas fa-cart-shopping me-1"></i>Cart</a></li>
                <?php if (isset($_SESSION['nama'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
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

    <!-- body -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3 mb-5">
                <h3>Kategori</h3>
                <ul class="list-group">
                    <?php while($kategori = mysqli_fetch_Array($queryKategori)){ ?>
                    <a href="item.php?kategori=<?php echo $kategori['nama'] ?>" class="no-decoration">
                        <li class="list-group-item"><?php echo $kategori['nama'] ?></li>
                    </a>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-lg-9">
                <h3 class="text-center mb-3">Produk</h3>
                <div class="row">
                    <?php
                        if($countData<1){
                    ?>
                        <h4 class="text-center my-5">Produk yang anda cari tidak tersedia</h4>
                    <?php
                        }
                    ?>

                        <?php while($produk = mysqli_fetch_Array($queryProduk)){ ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="image-box">
                                <img src="image/<?php echo $produk['foto']; ?>" class="card-img-top" alt="..."> 
                            </div>
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $produk['nama']; ?></h4>
                                <p class="card-text text-truncate"><?php echo $produk['detail']; ?></p>
                                <p class="card-text text-harga">Rp <?php echo $produk['harga']; ?></p>
                                <a href="item_detail.php?nama=<?php echo $produk['nama']; ?>" class="btn btn-primary">Lihat Detail</a>
                                <a href="add_to_cart.php?produk_id=<?= $produk['id'] ?>" class="btn btn-warning">Add Cart<i class="fas fa-cart-shopping me-1"></i></a>
                            </div>
                        </div>
                    </div>
                        <?php } ?>    
                </div>
            </div>
        </div>
    </div>


 <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>
</html>