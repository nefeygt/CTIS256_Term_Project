<?php
require "../db.php";
session_start();

function setFlashMessage($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function displayFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        $class = $flash['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
        echo "<div id='flash-modal' class='modal' style='display: block;'>
                <div class='modal-content {$class}'>
                    <p>{$flash['message']}</p>
                    <button onclick='hideFlashModal()' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'>Close</button>
                </div>
              </div>";
        unset($_SESSION['flash']); // Clear the flash message after displaying
    }
}

if (!isset($_SESSION['token'])) {
    setFlashMessage('error', 'You are not logged in. Please login to continue.');
    header("Location: login.php");
    exit;
}

$user = getMarketByToken($_SESSION['token']);
if ($user == false) {
    setFlashMessage('error', 'Invalid session. Please re-login.');
    header("Location: login.php");
    exit;
}

$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    setFlashMessage('error', 'Product ID is required.');
    header("Location: index.php"); // Redirect to a safe page
    exit;
}

$product = getProductById($product_id);
if (!$product) {
    setFlashMessage('error', 'Product not found.');
    header("Location: index.php"); // Redirect to a safe page
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $disc_price = $_POST['disc_price'];
    $exp_date = $_POST['exp_date'];
    $product_city = $_POST['product_city'];
    $stock = $_POST['stock'];

    $product_image = $product['product_image']; // Keep the current image by default
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
                // Remove the old image file if a new image is uploaded
                if ($product['product_image'] && file_exists($upload_dir . $product['product_image'])) {
                    unlink($upload_dir . $product['product_image']);
                }
                $product_image = $new_file_name; // Use the new image file name
            } else {
                setFlashMessage('error', 'Failed to upload image.');
                exit;
            }
        } else {
            setFlashMessage('error', 'Invalid file type or size. Allowed types: jpg, jpeg, png, gif. Max size: 2MB.');
            exit;
        }
    }

    $updated = updateProduct($product_id, $title, $price, $disc_price, $exp_date, $product_image, $product_city, $stock);
    if ($updated) {
        setFlashMessage('success', 'Product updated successfully.');
        // Refresh the product details after update
        $product = getProductById($product_id);
    } else {
        setFlashMessage('error', 'Failed to update product.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
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

        // Function to show the current image initially
        function showCurrentImage(imagePath) {
            var output = document.getElementById('imagePreview');
            output.src = imagePath;
            output.style.display = 'block';
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Show current image on page load
            <?php if ($product['product_image']) : ?>
                showCurrentImage("../uploads/<?= htmlspecialchars($product['product_image']) ?>");
            <?php endif; ?>
        });
    </script>
    <style>
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0; 
        top: 0; 
        width: 100%; 
        height: 100%; 
        overflow: auto; 
        background-color: rgba(0, 0, 0, 0.5); /* Dimmed background */
    }
    .modal-content {
        background-color: #ffffff;
        margin: 10% auto; /* Raise the modal a bit */
        padding: 20px;
        border: 1px solid #888;
        width: 90%; /* Responsive width */
        max-width: 450px; /* Maximum width */
        text-align: center;
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 4px 6px rgba(0,0,0,0.1); /* Subtle shadow */
    }
    </style>
</head>
<body>
    <div class="container mx-auto px-4">
        <?php displayFlashMessage(); ?>
        <div class="navbar bg-gray-800 flex justify-between items-center my-4 p-4 text-white">
            <a href="./index.php" class="text-blue-300 hover:text-blue-500"><i class="fas fa-store mr-2"></i>Market</a>
            <a href="./profile.php" class="text-blue-300 hover:text-blue-500 flex items-center">
                <i class="fas fa-user-circle mr-2"></i>
                <?= htmlspecialchars($user['market_name']) ?>
            </a>
        </div>
        <div class="mb-4 mt-8">
            <h2 class="text-xl font-semibold">Update Product Details</h2>
            <form action="" method="post" enctype="multipart/form-data" class="mt-4">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($product['product_title']) ?>" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                    <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input type="text" id="price" name="price" value="<?= htmlspecialchars($product['product_price']) ?>" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="disc_price" class="block text-sm font-medium text-gray-700">Discounted Price</label>
                    <input type="text" id="disc_price" name="disc_price" value="<?= htmlspecialchars($product['product_disc_price']) ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="exp_date" class="block text-sm font-medium text-gray-700">Expiration Date</label>
                    <input type="date" id="exp_date" name="exp_date" value="<?= htmlspecialchars($product['product_exp_date']) ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="product_image" class="block text-sm font-medium text-gray-700">Product Image</label>
                    <img id="imagePreview" src="#" alt="Product Image" class="mb-4 max-w-xs" style="display: none;" />
                    <input type="file" id="product_image" name="product_image" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" onchange="previewImage(event)">
                </div>
                <div class="mb-4">
                    <label for="product_city" class="block text-sm font-medium text-gray-700">Product City</label>
                    <input type="text" id="product_city" name="product_city" value="<?= htmlspecialchars($product['product_city']) ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Product</button>
            </form>
        </div>
    </div>
    <script>
    function showFlashModal() {
        ocument.getElementById('flash-modal').style.display = 'block';
    }

    function hideFlashModal() {
        document.getElementById('flash-modal').style.display = 'none';
    }
    </script>
</body>
</html>
