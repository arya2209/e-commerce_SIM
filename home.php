<?php
session_start();
require "koneksi.php"; // Harus di paling atas file
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Luxia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-color:#e7f2f8;
            padding: 20px;
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
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm py-3 sticky-top bg-light">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Luxia</a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav align-items-center gap-4">
                <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="item.php">Item</a></li>
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



<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const quantities = [0, 0, 0];
    function increaseQuantity(index) {
        quantities[index]++;
        document.getElementById(`quantity-${index}`).innerText = quantities[index];
    }
    function decreaseQuantity(index) {
        if (quantities[index] > 0) {
            quantities[index]--;
            document.getElementById(`quantity-${index}`).innerText = quantities[index];
        }
    }
    function buyProduct(index) {
        if (quantities[index] > 0) {
            alert(`Anda membeli ${quantities[index]} produk!`);
            quantities[index] = 0;
            document.getElementById(`quantity-${index}`).innerText = 0;
        } else {
            alert('Silakan pilih jumlah produk terlebih dahulu.');
        }
    }
</script>
</body>
</html>
