<?php

session_start();
require "../db.php";


if(!isset($_SESSION['token'])) {
    header("Location: ../login.php"); // Redirect to login if not authenticated
    exit;
}

$user = getCustomerByToken($_SESSION['token']);
if ($user == false) {
    echo "Invalid session. Please re-login.";
    exit;
}

$search = $_GET['search'] ?? '';
$products = getFilteredProducts($user['city'], $user['district'], $search);
$total = count($products);
$perPage = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;
$products = array_slice($products, $start, $perPage);
$pages = ceil($total / $perPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        .cart-popup ul li {
            color: #333; /* Dark grey text */
            background-color: #fff; /* White background */
            list-style-type: none; /* Removes bullet points */
        }
        .cart-popup ul li a {
            color: red; /* Red color for links */
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4">
        <div class="navbar bg-gray-800 flex justify-between items-center my-4 p-4 text-white">
            <a href="./index.php" class="text-blue-300 hover:text-blue-500">Home</a>
            <a href="./profile.php" class="text-blue-300 hover:text-blue-500"><?= htmlspecialchars($user['name']) ?></a>
            <button onclick="showLogoutModal()" class="text-red-300 hover:text-red-500 ml-4">Logout</button>
            <div class="relative">
                <div class="cart-icon" onclick="toggleCart()">🛒</div>
                <div class="cart-popup hidden absolute right-0 w-300 bg-white shadow-lg p-4">
                    <?php include 'cart_popup.php'; ?>
                </div>
            </div>
        </div>
        <form method="GET" class="search-container my-4">
            <input type="text" placeholder="Search products..." class="border p-2 w-full" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-2">Search</button>
        </form>
        <table class="table-auto w-full mb-4 bg-white rounded shadow">
            <thead class="bg-gray-200 text-gray-600">
                <tr>
                    <th class="py-3 px-6 text-left">Title</th>
                    <th class="py-3 px-6 text-left">Stock</th>
                    <th class="py-3 px-6 text-left">Price</th>
                    <th class="py-3 px-6 text-left">Discounted Price</th>
                    <th class="py-3 px-6 text-left">Expiration Date</th>
                    <th class="py-3 px-6 text-left">City</th>
                    <th class="py-3 px-6 text-left">District</th>
                    <th class="py-3 px-6 text-center">Add to Cart</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6"><?= htmlspecialchars($product['product_title']) ?></td>
                    <td class="py-3 px-6"><?= htmlspecialchars($product['stock']) ?></td>
                    <td class="py-3 px-6"><?= htmlspecialchars($product['product_price']) ?></td>
                    <td class="py-3 px-6"><?= htmlspecialchars($product['product_disc_price']) ?></td>
                    <td class="py-3 px-6"><?= htmlspecialchars($product['product_exp_date']) ?></td>
                    <td class="py-3 px-6"><?= htmlspecialchars($product['product_city']) ?></td>
                    <td class="py-3 px-6"><?= htmlspecialchars($product['product_district']) ?></td>
                    <td class="py-3 px-6 text-center">
                        <form action="add_to_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                            <button class="add-to-cart bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">Add to Cart</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <nav class="flex justify-center">
            <ul class="inline-flex">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <li><a href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>" class="<?= ($page === $i) ? 'bg-blue-700 text-white' : 'text-blue-500' ?> mx-1 px-3 py-2 rounded hover:bg-blue-500 hover:text-white"><?= $i ?></a></li>
            <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <!-- Logout Modal -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to logout?</p>
            <button onclick="logout()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Yes, Logout</button>
            <button onclick="hideLogoutModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
        </div>
    </div>

    <script>
        function toggleCart() {
            const cartPopup = document.querySelector('.cart-popup');
            cartPopup.classList.toggle('hidden');
        }

        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'block';
        }

        function hideLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        function logout() {
            window.location.href = '../logout.php';
        }
    </script>

    <form action="update_cart.php" method="post">
        <input type="hidden" name="">
        <button type="submit" name="increase_prd" style="color: greenyellow">+</button>
        <button type="submit" name="decrease_prd" style="color: red">-</button>
    </form>

</body>
</html>
