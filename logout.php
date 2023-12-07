<?php
/************
    Name: Hargun Singh
    Date: 2023-09-25
    Description: Logout Page
************/

session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the index page after logout
header("Location: index.php");
exit;
?>
