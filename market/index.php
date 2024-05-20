<?php
require "../db.php";
session_start();

if(!isset($_SESSION['token'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit;
}

$user = getMarketByToken($_SESSION['token']);
if ($user == false) {
    echo "Invalid session. Please re-login.";
    exit;
}

// Pagination setup
$perPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);  // Ensure page is at least 1
$start = ($page - 1) * $perPage;

// Fetch products
$products = getMarketItems($user['email']);
$total = count($products);
$products = array_slice($products, $start, $perPage);

$pages = ceil($total / $perPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Godzilla</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0, 0, 0, 0.4); 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px; 
            border: 1px solid #888;
            width: 80%; 
            max-width: 400px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container mx-auto px-4">
        <div class="navbar bg-gray-800 flex justify-between items-center my-4 p-4 text-white">
            <a href="./index.php" class="text-blue-300 hover:text-blue-500"><i class="fas fa-store mr-2"></i>Market</a>
            <a href="./profile.php" class="text-blue-300 hover:text-blue-500 flex items-center">
                <i class="fas fa-user-circle mr-2"></i>
                <?= htmlspecialchars($user['market_name']) ?>
            </a>
            <button onclick="showLogoutModal()" class="text-red-300 hover:text-red-500 ml-4">Logout</button>
        </div>
        <div class="mb-4 flex justify-center">
            <a href="add_product.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add New Product</a>
        </div>
        <table class="table-auto w-full mb-4">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Title</th>
                    <th class="py-3 px-6 text-left">Stock</th>
                    <th class="py-3 px-6 text-left">Price</th>
                    <th class="py-3 px-6 text-left">Discounted Price</th>
                    <th class="py-3 px-6 text-left">Expiration Date</th>
                    <th class="py-3 px-6 text-center">Edit</th>
                    <th class="py-3 px-6 text-center">Delete</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach ($products as $product) { 
                    $isExpired = strtotime($product['product_exp_date']) < time();
                ?>
                <tr class="border-b border-gray-200 hover:bg-gray-100 <?= $isExpired ? 'bg-red-200' : '' ?>">
                    <td class="py-3 px-6 text-left"><?= htmlspecialchars($product['product_title']) ?></td>
                    <td class="py-3 px-6 text-left"><?= htmlspecialchars($product['stock'] ?? 'N/A') ?></td>
                    <td class="py-3 px-6 text-left"><?= htmlspecialchars($product['product_price']) ?></td>
                    <td class="py-3 px-6 text-left"><?= htmlspecialchars($product['product_disc_price']) ?></td>
                    <td class="py-3 px-6 text-left"><?= htmlspecialchars($product['product_exp_date']) ?></td>
                    <td class="py-3 px-6 text-center"><a href='edit.php?id=<?= $product['product_id'] ?>' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded'>Edit</a></td>
                    <td class="py-3 px-6 text-center"><a href='delete.php?id=<?= $product['product_id'] ?>' class='bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded'>Delete</a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <nav class="flex justify-center">
            <ul class="inline-flex">
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                <li><a href="?page=<?= $i ?>" class="<?= ($page === $i) ? 'text-blue-700' : 'text-blue-500' ?> mx-1 px-3 py-2 bg-gray-200 hover:bg-blue-500 hover:text-white"><?= $i ?></a></li>
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
</body>
</html>
