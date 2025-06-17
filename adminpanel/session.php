<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../home.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../adminpanel");
    exit;
}
?>
