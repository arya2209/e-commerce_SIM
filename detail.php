<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: adminpanel/login.php");
    exit;
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Pastikan order milik user
$order = mysqli_fetch_assoc(mysqli_query($mysqli, "
    SELECT * FROM orders 
    WHERE id = $order_id AND user_id = $user_id
"));

if (!$order) {
    echo "<p>Pesanan tidak ditemukan atau bukan milik Anda.</p>";
    exit;
}

// Ambil detail produk dalam order (tambahkan kolom p.foto)
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
    <title>Detail Order #<?= $order_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h3>Detail Order #<?= $order_id ?></h3>
    <p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
    <p><strong>Tanggal:</strong> <?= $order['tanggal'] ?></p>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Gambar</th>
            <th>Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($item = mysqli_fetch_assoc($items)): ?>
            <tr>
                <td>
                    <img src="image/<?= $item['foto'] ?>" width="80" alt="<?= $item['nama_produk'] ?>">
                </td>
                <td><?= $item['nama_produk'] ?></td>
                <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>Rp<?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <a href="order.php" class="btn btn-secondary">Kembali</a>
</div>
</body>
</html>
