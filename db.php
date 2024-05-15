<?php

// Database connection constants
const DSN = "mysql:host=localhost;dbname=test;charset=utf8mb4";
const USER = "root";
const PASSWORD = "";

// Establish a database connection
try {
    $db = new PDO(DSN, USER, PASSWORD);
} catch(PDOException $e) {
    echo "Set username and password in 'db.php' appropriately";
    exit;
}

// User related functions

// Check if a customer exists in the database by their email
function checkCustomerExists($email) {
    global $db;
    $stmt = $db->prepare("select * from customers where email=?");
    $stmt->execute([$email]);
    return $stmt->fetch() ? true : false;
}

// Check if a market user exists in the database by their email
function checkMarketExists($email) {
    global $db;
    $stmt = $db->prepare("select * from market_user where email=?");
    $stmt->execute([$email]);
    return $stmt->fetch() ? true : false;
}

// Get a customer by their email
function getCustomer($email) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC); // Fetches the next row as an associative array
}

// Get a market user by their email
function getMarket($email) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM market_user WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC); // Fetches the next row as an associative array
}

// Verify a customer's password
function checkCustomerPassword($email, $pass, &$user) {
    global $db;
    $stmt = $db->prepare("select * from customers where email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    return $user ? password_verify($pass, $user["password"]) : false;
}

// Verify a market user's password
function checkMarketPassword($email, $pass, &$user) {
    global $db;
    $stmt = $db->prepare("select * from market_user where email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    return $user ? password_verify($pass, $user["password"]) : false;
}

// Check if a user is authenticated
function isAuthenticated() {
    return isset($_SESSION["token"]);
}

// Get a user by their token
function getCustomerByToken($token) {
    global $db;
    $stmt = $db->prepare("select * from customers where remember = ?");
    $stmt->execute([$token]);
    return $stmt->fetch();
}

// Get a user by their token
function getMarketByToken($token) {
    global $db;
    $stmt = $db->prepare("select * from market_user where remember = ?");
    $stmt->execute([$token]);
    return $stmt->fetch();
}

// Set a user's token by their email
function setTokenByEmail($email, $token) {
    global $db;
    $stmt = $db->prepare("update auth set remember = ? where email = ?");
    $stmt->execute([$token, $email]);
}

// Temporary table related functions

// Store user data in a temporary table
function storeInTemporaryTable($email, $password, $token, $name, $city, $district, $address, $userType) {
    global $db;
    $stmt = $db->prepare("insert into temp (email, password, token, name, city, district, address, type) values (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$email, $password, $token, $name, $city, $district, $address, $userType]);
}

// Save user from temporary table to respective table
function saveUserFromTemp($email, $token) {
    global $db;
    $stmt = $db->prepare("select * from temp where email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user['type'] == "customer") {
        $stmt = $db->prepare("insert into customers (email, password, name, city, district, address, remember) values (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user["email"], $user["password"], $user["name"], $user["city"], $user["district"], $user["address"], $token]);
        return "customer";
    } else if ($user['type'] == "market") {
        $stmt = $db->prepare("insert into market_user (email, password, market_name, city, district, address, remember) values (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user["email"], $user["password"], $user["name"], $user["city"], $user["district"], $user["address"], $token]);
        return "market";
    }
}

// Empty the temporary table
function emptyTempTable() {
    global $db;
    $stmt = $db->prepare("TRUNCATE TABLE temp");
    $stmt->execute();
}

// Verification related functions

// Save the code in the verification table
function saveCode($email, $token, $code) {
    global $db;
    $deleteStmt = $db->prepare("DELETE FROM verification WHERE token = ?");
    $deleteStmt->execute([$token]);
    $insertStmt = $db->prepare("INSERT INTO verification (email, token, code) VALUES (?, ?, ?)");
    $insertStmt->execute([$email, $token, $code]);
}

// Get the code from the verification table
function getCode($token) {
    global $db;
    $stmt = $db->prepare("select * from verification where token = ?");
    $stmt->execute([$token]);
    $code = $stmt->fetch();
    return $code["code"];
}

// Admin related functions

// Get the admin's email
function getAdminEmail() {
    global $db;
    $stmt = $db->prepare("select * from mailadmin");
    $stmt->execute();
    $admin = $stmt->fetch();
    return $admin ? [$admin["email"], $admin["password"]] : false;
}

// Empty the customers and market_user tables
function emptyTables() {
    global $db;
    $stmt = $db->prepare("DELETE FROM customers");
    $stmt->execute();
    $stmt = $db->prepare("DELETE FROM market_user");
    $stmt->execute();
}
