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

        <!-- Form checkout -->
        <form id="checkout-form">
            <div class="mb-3">
                <label>Nama Penerima</label>
                <input type="text" name="nama_penerima" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Alamat Pengiriman</label>
                <textarea name="alamat" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>Nomor Telepon</label>
                <input type="text" name="telepon" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <input type="hidden" name="total_harga" value="<?= $total_harga; ?>">
            <button type="button" id="pay-button" class="btn btn-primary">Bayar dengan Midtrans (VA BNI)</button>
        </form>
    <?php endif; ?>
</div>

<!-- Snap.js -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-CQQ-EtDjh0kuTyZ0"></script>
<script>
document.getElementById('pay-button').addEventListener('click', function () {
    const form = document.querySelector('#checkout-form');
    const formData = new FormData(form);

    fetch('get_token.php', {
        method: 'POST',
        body: new URLSearchParams(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.token) {
            snap.pay(data.token, {
                onSuccess: function (result) {
                    // Jika pembayaran berhasil, simpan ke database
                    fetch('proses_checkout.php', {
                        method: 'POST',
                        body: new URLSearchParams(formData)
                    }).then(() => window.location.href = 'checkout_sukses.php');
                },
                onPending: function (result) {
                    alert("Pembayaran tertunda. Cek transaksi Anda.");
                },
                onError: function (result) {
                    alert("Pembayaran gagal.");
                }
            });
        } else {
            alert("Gagal mendapatkan token Midtrans.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Terjadi kesalahan saat proses pembayaran.");
    });
});
</script>

</body>
</html>
