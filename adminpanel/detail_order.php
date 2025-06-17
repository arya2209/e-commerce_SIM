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
    SELECT od.*, p.nama AS nama_produk 
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
    <h2>Detail Order #<?= $order_id ?></h2>
    <p><strong>Nama User:</strong> <?= $order['nama_user'] ?></p>
    <p><strong>Nama Penerima:</strong> <?= $order['nama_penerima'] ?></p>
    <p><strong>Alamat:</strong> <?= $order['alamat'] ?></p>
    <p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($item = mysqli_fetch_assoc($items)): ?>
            <tr>
                <td><?= $item['nama_produk'] ?></td>
                <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>Rp<?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
