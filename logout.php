<?php
session_start();
session_destroy();
header("Location: ../CTIS256_Term_Project"); // Redirect to the main registration page
exit;
?>
