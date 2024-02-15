<?php

include 'db_connect.php';

function sanitize($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['fname']);
    $email = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);
    $userType = $_POST['userType']; // No need to sanitize for options

    $stmt = $conn->prepare("INSERT INTO users (fname, email, password, userType) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $userType);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success" role="alert">Data saved to the database.</div>';
        echo '<script>alert("Data saved to the database."); window.location.href = "users.php";</script>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error: ' . $stmt->error . '</div>';
    }

    $stmt->close();
    mysqli_close($conn);

    // Redirect back to users.php after processing the form
    header("Location: users.php");
    exit();
}
