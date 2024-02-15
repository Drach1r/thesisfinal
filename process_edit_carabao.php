<?php
// Include the database connection file
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the carabao ID from the form submission
    $carabao_id = $_POST['carabao_id'];

    // Get the other form data

    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    // Update the carabao data in the database
    $updateStmt = $conn->prepare("UPDATE carabaos SET name = ?, age = ?, gender = ? WHERE id = ?");
    $updateStmt->bind_param("sisi", $name, $age, $gender, $carabao_id);

    if ($updateStmt->execute()) {
        // Redirect to the carabao.php page with a success message
        header("Location: carabao.php?success=1");
        exit();
    } else {
        // Redirect to the carabao.php page with an error message
        header("Location: carabao.php?error=1");
        exit();
    }
}