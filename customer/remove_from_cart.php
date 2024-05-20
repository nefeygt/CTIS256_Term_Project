<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$_GET['product_id']])) {
    unset($_SESSION['cart'][$_GET['product_id']]);
    header("Location: index.php");
    exit;
    

    $_SESSION['cart'][$_GET['product_id']] = $_SESSION['cart'][$_GET['product_id']] - 1;
}

header("Location: index.php");
