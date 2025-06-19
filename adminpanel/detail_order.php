<?php
session_start();
require "../koneksi.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$order_id = $_GET['id'];

$order = mysqli_fetch_assoc(mysqli_query($mysqli, "
    SELECT o.*, u.nama AS nama_user 
    FROM orders o 
    JOIN user u ON o.user_id = u.id 
    WHERE o.id = $order_id
"));

$items = mysqli_query($mysqli, "
    SELECT od.*, p.nama AS nama_produk, p.foto 
    FROM order_detail od 
    JOIN produk p ON od.produk_id = p.id 
    WHERE od.order_id = $order_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Order #<?= $order_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .order-summary {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .badge-status {
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .order-summary {
                padding: 20px;
            }
            h3 {
                font-size: 1.3rem;
            }
            p, td {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="order-summary">
        <h3 class="mb-4">🧾 Detail Order <span class="text-muted">#<?= $order_id ?></span></h3>

        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>Nama User:</strong> <?= $order['nama_user'] ?></p>
                <p><strong>Nama Penerima:</strong> <?= $order['nama_penerima'] ?></p>
                <p><strong>Alamat:</strong> <?= $order['alamat'] ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Email:</strong> <?= $order['email'] ?></p>
                <p><strong>Telepon:</strong> <?= $order['telepon'] ?></p>
                <p><strong>Status:</strong>
                    <?php
                    $status = strtolower($order['status']);
                    $badgeClass = match($status) {
                        'pending' => 'bg-warning',
                        'selesai' => 'bg-success',
                        'dibatalkan' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                    ?>
                    <span class="badge <?= $badgeClass ?> badge-status text-light"><?= ucfirst($status) ?></span>
                </p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                <tr>
                    <th>Gambar</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $grand_total = 0;
                while ($item = mysqli_fetch_assoc($items)):
                    $subtotal = $item['harga'] * $item['quantity'];
                    $grand_total += $subtotal;
                    ?>
                    <tr>
                        <td><img src="../image/<?= $item['foto'] ?>" alt="<?= $item['nama_produk'] ?>" class="thumbnail"></td>
                        <td><?= $item['nama_produk'] ?></td>
                        <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr class="fw-bold">
                    <td colspan="4" class="text-end">Total</td>
                    <td>Rp<?= number_format($grand_total, 0, ',', '.') ?></td>
                </tr>
                </tbody>
            </table>
        </div>

        <a href="riwayat_order.php" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
