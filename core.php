<?php
session_start();

// Function to check if the user's userType is allowed to access the given page
function checkUserTypeAccess($allowedUserTypes, $redirectPage, $warningMessage)
{
    if (!isset($_SESSION['userType']) || !in_array($_SESSION['userType'], $allowedUserTypes)) {
        // Set the warning message in the session
        $_SESSION['warning'] = $warningMessage;

        // Redirect the user to the specified page
        header("Location: $redirectPage");
        exit();
    }
}

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    // Set the warning message in the session
    $_SESSION['warning'] = "You are not logged in. Please log in to access this page.";

    // Redirect the user to the login page
    header("Location: login.php");
    exit();
}
