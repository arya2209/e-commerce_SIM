<?php
session_start();
require "../koneksi.php";

// Pastikan hanya admin yang boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($mysqli, "
    SELECT o.*, u.nama AS nama_user 
    FROM orders o
    JOIN user u ON o.user_id = u.id
    ORDER BY o.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Order</title>
      <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- Font Awesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    .no-decoration {
        text-decoration: none;
    }

    body {
        background-color: #e7f2f8;
    }
</style>
<body class="bg-light">
    <?php require "navbar.php" ?>
<div class="container my-5">
    <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="../adminpanel" class="no-decoration text-muted">
                        <i class="fa fa-home"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Order
                </li>
            </ol>
        </nav>

        <div class="my-5 col-12 col-md-6">
            <h3>List Order Pesanan</h3>

        </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID Order</th>
                <th>User</th>
                <th>Nama Penerima</th>
                <th>Alamat</th>
                <th>Total Harga</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($order = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['nama_user'] ?></td>
                <td><?= $order['nama_penerima'] ?></td>
                <td><?= $order['alamat'] ?></td>
                <td>Rp<?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                <td><?= $order['tanggal'] ?></td>
                <td><?= ucfirst($order['status']) ?></td>
                <td><a href="detail_order.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-info">Lihat</a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
</body>
</html>
