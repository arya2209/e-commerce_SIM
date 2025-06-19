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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .no-decoration {
            text-decoration: none;
        }
        body {
            background-color: #e7f2f8;
        }
        .container {
            padding: 15px;
        }
        @media (max-width: 768px) {
            h3 {
                font-size: 1.4rem;
            }
            table {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <?php require "navbar.php"; ?>
    
    <div class="container my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="../adminpanel" class="no-decoration text-muted">
                        <i class="fa fa-home"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Order</li>
            </ol>
        </nav>

        <div class="my-4">
            <h3>List Order Pesanan</h3>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID Order</th>
                        <th>User</th>
                        <th>Nama Penerima</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Total Harga</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Detail</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= $order['nama_user'] ?></td>
                            <td><?= $order['nama_penerima'] ?></td>
                            <td><?= $order['email'] ?></td>
                            <td><?= $order['telepon'] ?></td>
                            <td><?= $order['alamat'] ?></td>
                            <td>Rp<?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                            <td><?= $order['tanggal'] ?></td>
                            <td><?= ucfirst($order['status']) ?></td>
                            <td>
                                <a href="detail_order.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-info">Lihat</a>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger btn-hapus" data-id="<?= $order['id'] ?>">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data order akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `hapus_order.php?id=${orderId}`;
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
