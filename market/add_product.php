<?php
require "../db.php";
session_start();

if (!isset($_SESSION['token'])) {
    header("Location: login.php");
    exit;
}

$user = getMarketByToken($_SESSION['token']);
if (!$user) {
    echo "Invalid session. Please re-login.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $disc_price = $_POST['disc_price'];
    $exp_date = $_POST['exp_date'];
    $product_image = $_POST['product_image'];
    $product_city = $_POST['product_city'];
    $stock = $_POST['stock'];

    // Insert new product into the database
    if (insertProduct($user['email'], $title, $price, $disc_price, $exp_date, $product_image, $product_city, $stock)) {
        echo "Product added successfully.";
    } else {
        echo "Failed to add product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto px-4">
        <div class="navbar bg-gray-800 flex justify-between items-center my-4 p-4 text-white">
            <a href="./index.php" class="text-blue-300 hover:text-blue-500"><i class="fas fa-store mr-2"></i>Market</a>
            <a href="./profile.php" class="text-blue-300 hover:text-blue-500 flex items-center">
                <i class="fas fa-user-circle mr-2"></i>
                <?= htmlspecialchars($user['market_name']) ?>
            </a>
        </div>
        <div class="mb-4 mt-8">
            <h2 class="text-xl font-semibold">Add New Product</h2>
            <form action="" method="post" class="mt-4">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                <input type="number" id="stock" name="stock" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="text" id="price" name="price" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="disc_price" class="block text-sm font-medium text-gray-700">Discounted Price</label>
                <input type="text" id="disc_price" name="disc_price" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="exp_date" class="block text-sm font-medium text-gray-700">Expiration Date</label>
                <input type="date" id="exp_date" name="exp_date" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="product_image" class="block text-sm font-medium text-gray-700">Product Image</label>
                <input type="text" id="product_image" name="product_image" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="product_city" class="block text-sm font-medium text-gray-700">Product City</label>
                <input type="text" id="product_city" name="product_city" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Product</button>

            </form>
        </div>
    </div>
</body>
</html>
