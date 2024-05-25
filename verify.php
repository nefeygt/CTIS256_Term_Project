<?php
    // Include the mailer script
    require "mailer.php";

    // Start the session
    session_start();

    // Check if the session variables are set
    if (!isset($_SESSION["token"]) || !isset($_SESSION["email"])) {
        emptyTempTable();
        exit("Invalid request.");
    }

    // Handle GET requests
    if($_SERVER["REQUEST_METHOD"] == "GET") {
        // Initialize counter and last_request_time if not set
        if (!isset($_SESSION['counter'])) {
            $_SESSION['counter'] = 0;
            $_SESSION['last_request_time'] = time();
        }

        // Check if counter is not 1 or if 60 seconds have passed since last request
        if ($_SESSION['counter'] != 1 || time() - $_SESSION['last_request_time'] > 60) {
            $_SESSION['counter'] = 1;
            $_SESSION['last_request_time'] = time();

            $email = $_SESSION["email"];
            $token = $_SESSION["token"];

            // Generate and save the verification code
            $code = sha1(verify_email($email));
            saveCode($email, $token, $code);
        }
    }
    // Handle POST requests
    else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $token = $_SESSION["token"];
        $code = sha1($_POST["code"]);
        $email = $_SESSION["email"];
        
        // Verify the code
        if ($code == getCode($token)) {
            $newToken = bin2hex(random_bytes(16));
            // setcookie("token", $newToken, time() + 3600, "/", "", false, true);
            $_SESSION["token"] = $newToken;
            $type = saveUserFromTemp($email, $newToken);

            // Redirect based on user type
            if ($type == "customer") {
                header("Location: customer");
            }
            else if ($type == "market") {
                header("Location: market");
            }
        }
        else {
            exit("Invalid code.");
        }
    }
    // Handle invalid requests
    else {
        exit("Invalid request.");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <main class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
        <form action="" method="post" class="flex flex-col space-y-4">
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700">Enter Verification Code:</label>
                <input type="text" name="code" id="code" placeholder="Enter code" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <input type="submit" value="Verify" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
        </form>
    </main>
</body>
</html>
