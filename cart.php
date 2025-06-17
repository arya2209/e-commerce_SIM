<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../adminpanel/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
         body {
            background-color:#e7f2f8;
            padding: 20px;
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

<!-- Cart Content -->
<div class="container my-5">
    <h2 class="mb-4">Keranjang Belanja</h2>
    <table class="table align-middle table-bordered">
        <thead>
        <tr>
            <th>Gambar</th>
            <th>Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $query = mysqli_query($mysqli, "SELECT c.*, p.nama, p.harga, p.foto 
                                        FROM cart c 
                                        JOIN produk p ON c.produk_id = p.id 
                                        WHERE c.user_id = $user_id");

        $total_harga = 0;
        while ($cart = mysqli_fetch_assoc($query)) :
            $subtotal = $cart['harga'] * $cart['quantity'];
            $total_harga += $subtotal;
        ?>
            <tr>
                <td><img src="image/<?= $cart['foto']; ?>" width="80" alt="<?= $cart['nama']; ?>"></td>
                <td><?= $cart['nama']; ?></td>
                <td>Rp<?= number_format($cart['harga'], 0, ',', '.'); ?></td>
                <td>
                    <a href="update_cart.php?action=decrease&id=<?= $cart['id']; ?>" class="btn btn-sm btn-outline-secondary">-</a>
                    <span class="mx-2"><?= $cart['quantity']; ?></span>
                    <a href="update_cart.php?action=increase&id=<?= $cart['id']; ?>" class="btn btn-sm btn-outline-secondary">+</a>
                </td>
                <td>Rp<?= number_format($subtotal, 0, ',', '.'); ?></td>
                <td>
                    <a href="checkout.php" class="btn btn-sm btn-primary">Checkout</a>
                    <button class="btn btn-sm btn-danger btn-hapus" data-id="<?= $cart['id']; ?>">Hapus</button>
                </td>
            </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="4" class="text-end fw-bold">Total Harga</td>
            <td colspan="2" class="fw-bold">Rp<?= number_format($total_harga, 0, ',', '.'); ?></td>
        </tr>
        </tbody>
    </table>
</div>

<!-- SweetAlert Delete Script -->
<script>
    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Hapus produk dari keranjang?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `hapus_cart.php?id=${id}`;
                }
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
