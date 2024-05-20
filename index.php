<?php
session_start();
require "db.php";

if (isset($_SESSION['token'])) {
    // Assuming you have a function to verify and get user data by token
    $user = getCustomerByToken($_SESSION['token']); // Replace with your actual function
    if ($user != false) {
        header("Location: customer");
        exit;
    }
    $user = getMarketByToken($_SESSION['token']);
    if ($user != false) {
        header("Location: market");
        exit;
    }
}

$error = '';
$formData = [
    'userType' => '',
    'email' => '',
    'name' => '',
    'city' => '',
    'district' => '',
    'address' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formData = [
        'userType' => htmlspecialchars($_POST['userType']),
        'email' => filter_var($_POST["email"], FILTER_SANITIZE_EMAIL),
        'name' => htmlspecialchars($_POST["name"]),
        'city' => htmlspecialchars($_POST["city"]),
        'district' => htmlspecialchars($_POST["district"]),
        'address' => htmlspecialchars($_POST["address"])
    ];

    if ($_POST["password"] != $_POST["passwordconfirm"]) {
        $error = "Passwords do not match.";
    } else {
        $password = $_POST["password"];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(16));

        $_SESSION["email"] = $formData['email'];
        $_SESSION["token"] = $token;

        if ($formData['userType'] == "market" && !checkMarketExists($formData['email'])) {
            storeInTemporaryTable($formData['email'], $hashed_password, $token, $formData['name'], $formData['city'], $formData['district'], $formData['address'], $formData['userType']);
            header("Location: verify.php");
            exit;
        } elseif ($formData['userType'] == "customer" && !checkCustomerExists($formData['email'])) {
            storeInTemporaryTable($formData['email'], $hashed_password, $token, $formData['name'], $formData['city'], $formData['district'], $formData['address'], $formData['userType']);
            header("Location: verify.php");
            exit;
        } else {
            $error = "User already exists.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .border-red-500 { border-color: #f56565; }
        .text-red-500 { color: #f56565; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
        <div class="mb-4 text-center">
            <h1 class="text-2xl font-semibold text-gray-700">Sign Up</h1>
            <?php if (!empty($error)): ?>
                <p class="text-red-500"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>
        <div class="flex justify-around mb-6">
            <button type="button" onclick="showMarketForm()" class="flex items-center justify-center px-4 py-2 bg-blue-500 rounded-md text-white hover:bg-blue-600 transition duration-300">
                <i class="fas fa-store mr-2"></i> Market User
            </button>
            <button type="button" onclick="showCustomerForm()" class="flex items-center justify-center px-4 py-2 bg-green-500 rounded-md text-white hover:bg-green-600 transition duration-300">
                <i class="fas fa-user mr-2"></i> Customer User
            </button>
        </div>
        <!-- Forms container -->
        <div id="formsContainer" class="transition-colors duration-300">
            <form id="marketForm" action="" method="post" class="hidden space-y-4" onsubmit="return validatePasswords('marketForm')">
                <input type="hidden" name="userType" value="market" <?= $formData['userType'] == 'market' ? 'checked' : '' ?>>
                <!-- Form fields with icons -->
                <div class="relative">
                    <i class="fas fa-envelope absolute text-gray-400 left-3 top-3"></i>
                    <input type="email" name="email" required placeholder="Email" value="<?= htmlspecialchars($formData['email']) ?>" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                    <i class="fas fa-tag absolute text-gray-400 left-3 top-3"></i>
                    <input type="text" name="name" required placeholder="Market Name" value="<?= htmlspecialchars($formData['name']) ?>" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                    <i class="fas fa-city absolute text-gray-400 left-3 top-3"></i>
                    <input type="text" name="city" required placeholder="City" value="<?= htmlspecialchars($formData['city']) ?>" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                    <i class="fas fa-map-marker-alt absolute text-gray-400 left-3 top-3"></i>
                    <input type="text" name="district" required placeholder="District" value="<?= htmlspecialchars($formData['district']) ?>" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                    <i class="fas fa-home absolute text-gray-400 left-3 top-3"></i>
                    <input type="text" name="address" required placeholder="Address" value="<?= htmlspecialchars($formData['address']) ?>" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                    <i class="fas fa-lock absolute text-gray-400 left-3 top-3"></i>
                    <input type="password" name="password" required placeholder="Password" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                    <i class="fas fa-lock absolute text-gray-400 left-3 top-3"></i>
                    <input type="password" name="passwordconfirm" id="marketPasswordConfirm" required placeholder="Confirm Password" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                    <p id="marketPasswordError" class="hidden text-red-500">Passwords do not match.</p>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">Register</button>
            </form>

            <form id="customerForm" action="" method="post" class="hidden space-y-4" onsubmit="return validatePasswords('customerForm')">
                <input type="hidden" name="userType" value="customer" <?= $formData['userType'] == 'customer' ? 'checked' : '' ?>>
                <!-- Form fields with icons -->
                <div class="relative">
                    <i class="fas fa-envelope absolute text-gray-400 left-3 top-3"></i>
                    <input type="email" name="email" required placeholder="Email" value="<?= htmlspecialchars($formData['email']) ?>" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                    <i class="fas fa-tag absolute text-gray-400 left-3 top-3"></i>
                    <input type="text" name="name" required placeholder="Full Name" value="<?= htmlspecialchars($formData['name']) ?>" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                    <i class="fas fa-city absolute text-gray-400 left-3 top-3"></i>
                    <input type="text" name="city" required placeholder="City" value="<?= htmlspecialchars($formData['city']) ?>" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                <i class="fas fa-map-marker-alt absolute text-gray-400 left-3 top-3"></i>
                <input type="text" name="district" required placeholder="District" value="<?= htmlspecialchars($formData['district']) ?>" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                <i class="fas fa-home absolute text-gray-400 left-3 top-3"></i>
                <input type="text" name="address" required placeholder="Address" value="<?= htmlspecialchars($formData['address']) ?>" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                <i class="fas fa-lock absolute text-gray-400 left-3 top-3"></i>
                <input type="password" name="password" required placeholder="Password" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                <i class="fas fa-lock absolute text-gray-400 left-3 top-3"></i>
                <input type="password" name="passwordconfirm" id="customerPasswordConfirm" required placeholder="Confirm Password" class="w-full pl-10 pr-4 py-2 border rounded-md focus:border-blue-500 focus:outline-none">
                <p id="customerPasswordError" class="hidden text-red-500">Passwords do not match.</p>                </div>
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">Register</button>
            </form>
        </div>
        <div class="text-center mt-6">
            <p class="text-gray-600">Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Log in here</a>.</p>
        </div>
    </div>
    <script>
        function showMarketForm() {
            document.getElementById("marketForm").style.display = "block";
            document.getElementById("customerForm").style.display = "none";
            document.getElementById("formsContainer").style.backgroundColor = '#e0f4ff';
        }
        
        function showCustomerForm() {
            document.getElementById("marketForm").style.display = "none";
            document.getElementById("customerForm").style.display = "block";
            document.getElementById("formsContainer").style.backgroundColor = '#e0ffe8';
        }
        function validatePasswords(formId) {
            const form = document.getElementById(formId);
            const password = form.querySelector('input[name="password"]').value;
            const confirmPassword = form.querySelector('input[name="passwordconfirm"]').value;
            const confirmPasswordInput = form.querySelector('input[name="passwordconfirm"]');
            const passwordError = form.querySelector('p[id*="PasswordError"]');

            if (password !== confirmPassword) {
                confirmPasswordInput.classList.add("border-red-500");
                passwordError.classList.remove("hidden");
                confirmPasswordInput.focus();
                return false;
            } else {
                confirmPasswordInput.classList.remove("border-red-500");
                passwordError.classList.add("hidden");
            }
            return true;
        }
    </script>
</body>
</html>