<?php
session_start();
require_once "../db.php";

// Ensure the cart array is initialized in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Initialize CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_GET['pg'])) {
    $p = (int) $_GET['pg']; // Ensure $p is an integer
} else {
    $p = '';
}

// Check if the product_id and csrf_token are posted and valid
if (isset($_POST['product_id']) && is_numeric($_POST['product_id']) && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $productId = (int) $_POST['product_id'];
    
    // Check if the product already exists in the cart and increment its quantity
    if (isset($_SESSION['cart'][$productId])) {
        $prd = getProductById($productId);
        if ($prd && $prd['stock'] > $_SESSION['cart'][$productId]) {
            $_SESSION['cart'][$productId]++;
        }
    } else {
        // Otherwise, add the product with a quantity of 1
        $_SESSION['cart'][$productId] = 1;
    }
}

// Redirect back to the index page after adding to cart
header("Location: index.php?page=" . urlencode($p));
exit;
?>
