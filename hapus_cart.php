<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: adminpanel/login.php");
    exit;
}

$id = $_GET['id'];
mysqli_query($mysqli, "DELETE FROM cart WHERE id=$id AND user_id=" . $_SESSION['user_id']);
header("Location: cart.php");
