
<?php
require "../db.php";
session_start();

// Ensure the user is authenticated
if (!isset($_SESSION['token'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit;
}

// Fetch the customer information using the email stored in the session
$token = $_SESSION['token'];
$customerInfo = getCustomerByToken($token);
// If the customer information is not found, display an error message and exit
if ($customerInfo == false) {
    echo "Customer information not found.";
    exit;
}

// Access individual fields
$name = htmlspecialchars($customerInfo['name']);
$address = htmlspecialchars($customerInfo['address']);
$city = htmlspecialchars($customerInfo['city']);
$district = htmlspecialchars($customerInfo['district']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto px-4">
        <div class="navbar bg-gray-800 flex justify-between items-center my-4 p-4 text-white">
            <a href="./index.php" class="text-blue-300 hover:text-blue-500"><i class="fas fa-store mr-2"></i>Consumer</a>
            <a href="./profile.php" class="text-blue-300 hover:text-blue-500 flex items-center">
                <i class="fas fa-user-circle mr-2"></i>
                <?= $name ?>
            </a>
        </div>
        <div class="mb-4">
            <h2 class="text-2xl font-bold mb-2">Consumer Profile</h2>
            <p><strong>Consumer Name:</strong> <?= $name ?></p>
            <p><strong>Address:</strong> <?= $address ?></p>
            <p><strong>City:</strong> <?= $city ?></p>
            <p><strong>District:</strong> <?= $district ?></p>
            <!-- <p><strong>Email:</strong> <?= $email ?></p> -->
        </div>
        <div class="mb-4 flex justify-center">
            <a href="edit_profile.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit Profile</a>
        </div>
    </div>
</body>
</html>