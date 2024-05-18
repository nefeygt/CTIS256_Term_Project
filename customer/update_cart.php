<?php
session_start();

require "../db.php";

if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $productId => $quantity) {
        if (isset($_SESSION['cart'][$productId])) {
            $product = getProductById($productId); // You need to ensure this function checks stock
            if ($quantity > 0 && $quantity <= $product['stock']) {
                $_SESSION['cart'][$productId] = $quantity;
            } else {
                // Set quantity to max stock if requested quantity exceeds stock
                $_SESSION['cart'][$productId] = $product['stock'];
            }
        }
    }
}

header("Location: index.php"); // Redirect back to the main page
exit;
