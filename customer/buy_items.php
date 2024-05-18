<?php
session_start();
require "../db.php"; // make sure db connection and functions are available

foreach ($_SESSION['cart'] as $productId => $quantity) {
    $product = getProductById($productId);
    if ($product && $quantity <= $product['stock']) {
        $res = buyItem($productId, $quantity); // Assuming this function updates the database and stock
        unset($_SESSION['cart'][$productId]); // Remove from cart after purchase
    } else {
        echo "Stock not sufficient for product ID: $productId";
        exit;
    }
}

echo "All items purchased successfully.";