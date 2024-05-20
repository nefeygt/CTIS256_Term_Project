<?php
session_start();

require "../db.php";

if (!isset($_SESSION['token'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit;
}

$user = getMarketByToken($_SESSION['token']);
if ($user == false) {
    echo "Invalid session. Please re-login.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];

    // Fetch product details to get the image name
    $product = getProductById($id);
    if ($product && isset($product['product_image'])) {
        $image_path = '../uploads/' . $product['product_image'];

        // Delete the product from the database
        if (deleteProduct($id) == true) {
            // If the product is deleted successfully, delete the image file
            if (file_exists($image_path)) {
                if (unlink($image_path)) {
                    echo "Product and image deleted successfully.";
                } else {
                    echo "Product deleted, but failed to delete image.";
                    error_log("Failed to delete image: $image_path"); // Log the error
                }
            } else {
                echo "Product deleted, but image file does not exist.";
                error_log("Image file does not exist: $image_path"); // Log the error
            }
        } else {
            echo "Failed to delete product.";
            error_log("Failed to delete product with ID: $id"); // Log the error
        }
    } else {
        echo "Product not found or image not set.";
        error_log("Product not found or image not set with ID: $id"); // Log the error
    }
    exit; // Ensure no further code is executed
}

header("Location: index.php");
exit; // Ensure no further code is executed
?>
