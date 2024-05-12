<?php

const DSN = "mysql:host=localhost;dbname=test;charset=utf8mb4" ;
const USER = "root" ;
const PASSWORD = "" ;

try {
   $db = new PDO(DSN, USER, PASSWORD) ; 
} catch(PDOException $e) {
     echo "Set username and password in 'db.php' appropriately" ;
     exit ;
}
 
 function checkUser($email, $pass, &$user) {
     global $db ;

     $stmt = $db->prepare("select * from auth where email=?") ;
     $stmt->execute([$email]) ;
     $user = $stmt->fetch() ;
     if ( $user) {
         return password_verify($pass, $user["password"]) ;
     }
     return false ;
 }

 function isAuthenticated() {
     return isset($_SESSION["user"]) ;
 }

 function getUserByToken($token) {
    global $db ;
    $stmt = $db->prepare("select * from auth where remember = ?") ;
    $stmt->execute([$token]) ;
    return $stmt->fetch() ;
 }

 function setTokenByEmail($email, $token) {
    global $db ;
    $stmt = $db->prepare("update auth set remember = ? where email = ?") ;
    $stmt->execute([$token, $email]) ;
 }

 function getAdminEmail() {
    global $db ;

    $stmt = $db->prepare("select * from mailadmin") ;
    $stmt->execute() ;
    $admin = $stmt->fetch() ;
    if ( $admin) {
        return [$admin["email"], $admin["password"]] ;
    }
    return false ;
}
