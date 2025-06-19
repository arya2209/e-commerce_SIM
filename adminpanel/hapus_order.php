<?php
session_start();
require "../koneksi.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']);

    // Hapus dari order_detail terlebih dahulu
    mysqli_query($mysqli, "DELETE FROM order_detail WHERE order_id = $order_id");

    // Lalu hapus dari orders
    mysqli_query($mysqli, "DELETE FROM orders WHERE id = $order_id");

    header("Location: riwayat_order.php");
    exit;
}
