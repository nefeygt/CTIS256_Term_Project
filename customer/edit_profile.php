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
        echo "<div id='flash-modal' class='fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center' onclick='this.remove();'>
                <div class='p-4 border rounded {$class}' onclick='event.stopPropagation();'>{$flash['message']}
                    <button class='ml-4 px-4 py-2 rounded text-white bg-blue-500' onclick='document.getElementById(\"flash-modal\").remove();'>Close</button>
                </div>
              </div>";
        unset($_SESSION['flash']); // Clear the flash message after displaying
    }
}

// Ensure the user is authenticated
if (!isset($_SESSION['token'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit;
}

// Fetch the customer information using the email stored in the session
$token = $_SESSION['token'];
$customerInfo = getCustomerByToken($token);

// If the customer information is not found
if ($customerInfo == false) {
    setFlashMessage('error', 'Customer information not found.');
    header("Location: error_page.php"); // Redirect to an error page
    exit;
}

$name = $customerInfo['name'];
$address = $customerInfo['address'];
$city = $customerInfo['city'];
$district = $customerInfo['district'];
$email = $customerInfo['email'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $district = htmlspecialchars($_POST['district']);

    $res = updateCustomer($name, $address, $city, $district, $email);
    if ($res === true) {
        setFlashMessage('success', 'Profile updated successfully.');
    } else {
        setFlashMessage('error', 'Error updating profile.');
    }
    header("Location: edit_profile.php"); // Redirect back to the form or to the success page
    exit;
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
        <?php displayFlashMessage(); ?>
        <div class="navbar bg-gray-800 flex justify-between items-center my-4 p-4 text-white">
            <a href="./index.php" class="text-blue-300 hover:text-blue-500"><i class="fas fa-store mr-2"></i>Home</a>
            <a href="./profile.php" class="text-blue-300 hover:text-blue-500 flex items-center">
                <i class="fas fa-user-circle mr-2"></i>
                <?= htmlspecialchars($customerInfo['name']) ?>
            </a>
        </div>
        <div class="mb-4">
            <h2 class="text-2xl font-bold mb-2">Edit Profile</h2>
            <form action="edit_profile.php" method="POST" class="space-y-4">
                <div>
                    <label for="name" class="block text-gray-700">Customer Name:</label>
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