<?php
session_start();
require "koneksi.php";

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: adminpanel/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($mysqli, "
    SELECT * FROM orders 
    WHERE user_id = $user_id 
    ORDER BY tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Order Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
</head>
<body>
   <!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm py-3 sticky-top bg-light">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Luxia</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarUser">
            <ul class="navbar-nav align-items-center gap-4">
                <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="item.php">Item</a></li>
                <li class="nav-item"><a class="nav-link" href="riwayat_user.php">Order</a></li>
                <li class="nav-item">
                    <a class="nav-link position-relative" href="cart.php">
                        <i class="fas fa-cart-shopping me-1"></i> Cart
                        <!-- Jika ingin menambahkan badge jumlah item -->
                        <!-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">2</span> -->
                    </a>
                </li>

                <?php if (isset($_SESSION['nama'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['nama']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <li><a class="dropdown-item" href="./adminpanel/index.php">Dashboard Admin</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="./adminpanel/logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="adminpanel/login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


<div class="container my-5">
    <h3 class="mb-4">Riwayat Pesanan Saya</h3>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
        <tr>
            <th>ID Order</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Detail</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($order = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td>Rp<?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                <td><?= ucfirst($order['status']) ?></td>
                <td><?= $order['tanggal'] ?></td>
                <td><a href="detail.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-info">Lihat</a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
 