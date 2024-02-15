<?php
// This script is included in delete_production.php

// Delete from manufacturing_mat table
$deleteMatQuery = "DELETE FROM manufacturing_mat WHERE order_id = ?";
$deleteMatStmt = $conn->prepare($deleteMatQuery);
$deleteMatStmt->bind_param("i", $orderId);

if ($deleteMatStmt->execute()) {
    // Both deletion operations were successful
    echo 'success';
} else {
    // There was an error in deleting from manufacturing_mat table
    echo 'error';
}

$deleteMatStmt->close();
