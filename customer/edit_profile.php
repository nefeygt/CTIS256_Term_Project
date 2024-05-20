<?php
require "../db.php";
session_start();

// Ensure the user is authenticated
if (!isset($_SESSION['token'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit;
}

// Fetch the market information using the email stored in the session
$token = $_SESSION['token'];
$marketInfo = getCustomerByToken($token);

// If the market information is not found, display an error message and exit
if ($marketInfo == false) {
    echo "Market information not found.";
    exit;
}

$name = $marketInfo['name'];
$address = $marketInfo['address'];
$city = $marketInfo['city'];
$district = $marketInfo['district'];
$email = $marketInfo['email'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $name = htmlspecialchars($_POST['name']);
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $district = htmlspecialchars($_POST['district']);

    // Update the market information in the database
    $res = updateCustomer($name, $address, $city, $district, $email);
    if ($res === true) {
        echo "Profile updated successfully.";
    } else {
        echo "Error updating profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto px-4">
        <div class="navbar bg-gray-800 flex justify-between items-center my-4 p-4 text-white">
            <a href="./index.php" class="text-blue-300 hover:text-blue-500"><i class="fas fa-store mr-2"></i>Market</a>
            <a href="./profile.php" class="text-blue-300 hover:text-blue-500 flex items-center">
                <i class="fas fa-user-circle mr-2"></i>
                <?= htmlspecialchars($marketInfo['name']) ?>
            </a>
        </div>
        <div class="mb-4">
            <h2 class="text-2xl font-bold mb-2">Edit Profile</h2>
            <form action="edit_profile.php" method="POST" class="space-y-4">
                <div>
                    <label for="name" class="block text-gray-700">Consumer Name:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="address" class="block text-gray-700">Address:</label>
                    <input type="text" id="address" name="address" value="<?= htmlspecialchars($address) ?>" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="city" class="block text-gray-700">City:</label>
                    <input type="text" id="city" name="city" value="<?= htmlspecialchars($city) ?>" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="district" class="block text-gray-700">District:</label>
                    <input type="text" id="district" name="district" value="<?= htmlspecialchars($district) ?>" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <!-- <div>
                    <label for="email" class="block text-gray-700">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" class="w-full p-2 border border-gray-300 rounded">
                </div> -->
                <div>
                    <button type="submit" class="bg-blue-500 hover bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</body>