<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: adminpanel/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil isi cart
$query = mysqli_query($mysqli, "SELECT c.*, p.nama, p.harga 
                                FROM cart c 
                                JOIN produk p ON c.produk_id = p.id 
                                WHERE c.user_id = $user_id");

$total_harga = 0;
$items = [];
while ($row = mysqli_fetch_assoc($query)) {
    $subtotal = $row['harga'] * $row['quantity'];
    $total_harga += $subtotal;
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2>Checkout</h2>

    <?php if (count($items) == 0): ?>
        <div class="alert alert-warning">Keranjang kosong.</div>
    <?php else: ?>
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
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $item['nama']; ?></td>
                    <td>Rp<?= number_format($item['harga'], 0, ',', '.'); ?></td>
                    <td><?= $item['quantity']; ?></td>
                    <td>Rp<?= number_format($item['harga'] * $item['quantity'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="text-end fw-bold">Total</td>
                <td class="fw-bold">Rp<?= number_format($total_harga, 0, ',', '.'); ?></td>
            </tr>
            </tbody>
        </table>

        <form method="POST" action="proses_checkout.php">
            <div class="mb-3">
                <label>Nama Penerima</label>
                <input type="text" name="nama_penerima" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Alamat Pengiriman</label>
                <textarea name="alamat" class="form-control" required></textarea>
            </div>
            <input type="hidden" name="total_harga" value="<?= $total_harga; ?>">
            <button type="submit" class="btn btn-success">Konfirmasi & Bayar</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
