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
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
    <main>
        <form action="" method="post">
            <label for="code">Enter Verification Code:</label>
            <input type="text" name="code" id="code" placeholder="Enter code">
            <input type="submit" value="Verify">
        </form>
    </main>
</body>
</html>