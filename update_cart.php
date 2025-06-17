<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: adminpanel/login.php");
    exit;
}

$id = $_GET['id'];
$action = $_GET['action'];

$query = mysqli_query($mysqli, "SELECT quantity FROM cart WHERE id=$id AND user_id=" . $_SESSION['user_id']);
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: cart.php");
    exit;
}

$quantity = $data['quantity'];

if ($action == "increase") {
    $quantity++;
} elseif ($action == "decrease" && $quantity > 1) {
    $quantity--;
}

mysqli_query($mysqli, "UPDATE cart SET quantity=$quantity WHERE id=$id");
header("Location: cart.php");
