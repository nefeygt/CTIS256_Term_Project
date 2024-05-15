<?php
session_start();
require "db.php";

if (isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $userType = $_POST['userType'];

    if ($userType == "customer") {
        $user = getCustomer($email);
    } else if ($userType == "market") {
        $user = getMarket($email);
    } else {
        $user = null;
    }

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['token'] = $user['remember'];
        header('Location: ' . $userType);
        exit;
    } else {
        $errorMessage = "Invalid email, password, or user type.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
        <div class="mb-4 text-center">
            <h1 class="text-2xl font-semibold text-gray-700">Login</h1>
            <?php if (!empty($errorMessage)): ?>
                <p class="text-red-500"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
        </div>
        <form action="" method="post" class="space-y-4">
            <div class="relative">
                <i class="fas fa-envelope absolute text-gray-400 left-3 top-3"></i>
                <input type="email" name="email" required placeholder="Email" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
            </div>
            <div class="relative">
                <i class="fas fa-lock absolute text-gray-400 left-3 top-3"></i>
                <input type="password" name="password" required placeholder="Password" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
            </div>
            <div class="relative">
                <select name="userType" required class="w-full pl-3 pr-10 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                    <option value="">Select User Type</option>
                    <option value="customer">Customer</option>
                    <option value="market">Market</option>
                </select>
            </div>
            <button type="submit" name="login" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">Log In</button>
        </form>
    </div>
</body>
</html>
