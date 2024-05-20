<?php
require "../db.php";
session_start();

if (!isset($_SESSION['token'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit;
}

$user = getMarketByToken($_SESSION['token']);
if ($user == false) {
    echo "Invalid session. Please re-login.";
    exit;
}
$title = $price = $disc_price = $exp_date = $product_image = $product_city = $stock = '';
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $disc_price = $_POST['disc_price'];
    $exp_date = $_POST['exp_date'];
    $product_city = $_POST['product_city'];
    $stock = $_POST['stock'];

    // Handle file upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['product_image']['name'];
        $file_tmp = $_FILES['product_image']['tmp_name'];
        $file_size = $_FILES['product_image']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed) && $file_size <= 2097152) { // 2MB file size limit
            $new_file_name = uniqid() . '.' . $file_ext;
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $upload_path = $upload_dir . $new_file_name;
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Insert new product into the database
                if (insertProduct($user['email'], $title, $price, $disc_price, $exp_date, $new_file_name, $product_city, $stock)) {
                    echo "Product added successfully.";
                } else {
                    echo "Failed to add product.";
                }
            } else {
                echo "Failed to upload image.";
            }
        } else {
            echo "Invalid file type or size. Allowed types: jpg, jpeg, png, gif. Max size: 2MB.";
        }
    } else {
        echo "No file uploaded or there was an upload error.";
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
    <script>
        // Function to preview the image before upload
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
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
                <input type="text" id="title" name="title" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="<?= htmlspecialchars($title)?>">
            </div>

            <div class="mb-4">
                <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                <input type="number" id="stock" name="stock" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="<?= htmlspecialchars($number)?>">
            </div>

            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="text" id="price" name="price" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="<?= htmlspecialchars($price)?>">
            </div>

            <div class="mb-4">
                <label for="disc_price" class="block text-sm font-medium text-gray-700">Discounted Price</label>
                <input type="text" id="disc_price" name="disc_price" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="<?= htmlspecialchars($disc_price)?>">
            </div>

            <div class="mb-4">
                <label for="exp_date" class="block text-sm font-medium text-gray-700">Expiration Date</label>
                <input type="date" id="exp_date" name="exp_date" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="<?= htmlspecialchars($exp_date)?>">
            </div>

            <div class="mb-4">
                <label for="product_image" class="block text-sm font-medium text-gray-700">Product Image</label>
                <input type="text" id="product_image" name="product_image" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="<?= htmlspecialchars($product_image)?>">
            </div>

            <div class="mb-4">
                <label for="product_city" class="block text-sm font-medium text-gray-700">Product City</label>
                <input type="text" id="product_city" name="product_city" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="<?= htmlspecialchars($product_city)?>">
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
