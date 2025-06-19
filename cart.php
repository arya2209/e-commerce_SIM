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
    <link rel="stylesheet" href="cart.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
    .table td a.btn {
        vertical-align: middle;
    }
</style>

<body>

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

    <!-- Cart Content -->
    <div class="container" style="margin-top:80px;">
        <div class="p-4 rounded shadow bg-white bg-opacity-50 text-white">
            <h2 class="mb-4">🛒 Keranjang Belanja</h2>
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-white">
                    <thead class="table-light text-dark">
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
                        $query = mysqli_query($mysqli, "
                            SELECT c.*, p.nama, p.harga, p.foto 
                            FROM cart c 
                            JOIN produk p ON c.produk_id = p.id 
                            WHERE c.user_id = $user_id
                        ");

                        $total_harga = 0;
                        $stok_kurang = false;
                        $pesan_error = "";

                        while ($cart = mysqli_fetch_assoc($query)):
                            $subtotal = $cart['harga'] * $cart['quantity'];
                            $total_harga += $subtotal;
                               $stok_produk = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT stok FROM produk WHERE id = {$cart['produk_id']}"))['stok'];
            if ($cart['quantity'] > $stok_produk) {
                $stok_kurang = true;
                $pesan_error .= "Stok produk <b>{$cart['nama']}</b> hanya tersedia <b>$stok_produk</b>, tapi Anda memesan <b>{$cart['quantity']}</b>.<br>";
            }
                            ?>
                            <tr>
                                <td>
                                    <img src="image/<?= $cart['foto']; ?>" class="img-thumbnail" width="80"
                                        alt="<?= $cart['nama']; ?>">
                                </td>

                                <td><?= $cart['nama']; ?></td>

                                <td>Rp<?= number_format($cart['harga'], 0, ',', '.'); ?></td>

                                <td>
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="update_cart.php?action=decrease&id=<?= $cart['id']; ?>"
                                            class="btn btn-sm btn-outline-light">−</a>

                                        <span><?= $cart['quantity']; ?></span>

                                        <a href="update_cart.php?action=increase&id=<?= $cart['id']; ?>"
                                            class="btn btn-sm btn-outline-light">+</a>
                                    </div>
                                </td>

                                <td>Rp<?= number_format($subtotal, 0, ',', '.'); ?></td>

                                <td>
            

                                    <button class="btn btn-sm btn-danger btn-hapus w-100" data-id="<?= $cart['id']; ?>">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                        <tr class="table-secondary text-dark fw-bold">
                            <td colspan="4" class="text-end">Total Harga</td>
                            <td colspan="2">Rp<?= number_format($total_harga, 0, ',', '.'); ?></td>
                            <td>
                <?php if ($stok_kurang): ?>
                    <button class="btn btn-sm btn-danger" id="btnStokKurang">Checkout</button>
                <?php else: ?>
                    <a href="checkout.php" class="btn btn-sm btn-primary">Checkout</a>
                <?php endif; ?>
            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- SweetAlert Script -->
<script>
    // Tombol hapus
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

    // Tombol checkout dicegah jika stok tidak cukup
    document.addEventListener("DOMContentLoaded", function () {
        const btnStokKurang = document.getElementById("btnStokKurang");
        if (btnStokKurang) {
            btnStokKurang.addEventListener("click", function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Tidak Cukup',
                    html: <?= json_encode($pesan_error) ?>,
                    confirmButtonText: 'OK'
                });
            });
        }
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>