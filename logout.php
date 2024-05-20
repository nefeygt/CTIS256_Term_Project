<?php
session_start();
session_destroy();
header("Location: ./"); // Redirect to the main registration page
exit;
?>
