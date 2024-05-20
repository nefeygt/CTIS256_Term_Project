<?php

session_start();

require_once "../db.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if (isset($_POST['product_id']) && isset($_POST['action'])) {
    $productId = $_POST['product_id'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$productId])) {
        $product = getProductById($productId);

        if ($action === 'increase') {
            if ($_SESSION['cart'][$productId] < $product['stock']) {
                $_SESSION['cart'][$productId]++;
                echo "in stock if";

            }
        } elseif ($action === 'decrease') {
            if ($_SESSION['cart'][$productId] > 1) {
                $_SESSION['cart'][$productId]--;
            }
        }
    }
}

header('Location: index.php');
exit;

?>