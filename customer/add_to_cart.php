<?php
session_start();
require_once "../db.php";

// Ensure the cart array is initialized in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if(isset($_GET['pg'])) {
    $p = $_GET['pg'];
} else $p = '';

// Check if the product_id is posted and is numeric
if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
    $productId = (int) $_POST['product_id'];
    
    // Check if the product already exists in the cart and increment its quantity
    if (isset($_SESSION['cart'][$productId])) {
        $prd = getProductById($productId);
        if($prd['stock'] > $_SESSION['cart'][$productId]) {
            $_SESSION['cart'][$productId]++;
        }
    } else {
        // Otherwise, add the product with a quantity of 1
        $_SESSION['cart'][$productId] = 1;
    }
}

// Redirect back to the index page after adding to cart
header("Location: index.php?page=" . (urlencode((int)$p)));
exit;
?>
