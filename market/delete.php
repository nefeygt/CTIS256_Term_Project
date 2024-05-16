<?php

session_start();

require "../db.php";

if(!isset($_SESSION['token'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit;
}

$user = getMarketByToken($_SESSION['token']);
if (!$user) {
    echo "Invalid session. Please re-login.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    deleteProduct($id);
}

header("Location: index.php");
