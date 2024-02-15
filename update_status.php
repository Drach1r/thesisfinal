<?php

include 'db_connect.php';
function insertStockFromPurchase($conn, $rawMaterialID, $quantity, $unit, $p_amount)
{
    // Convert quantities from Kg to grams
    if ($unit === 'Kg') {
        $quantity *= 1000; // Convert kilograms to grams
        $unit = 'g'; // Set unit to grams
    }

    // Convert quantities from liters to milliliters
    if ($unit === 'L') {
        $quantity *= 1000; // Convert liters to milliliters
        $unit = 'ml'; // Set unit to milliliters
    }

    // Insert a new row into the stock table for each purchase
    $insertStockQuery = "INSERT INTO stock (RawMaterialID, stock_in, stock_out, unit, transaction_date) VALUES (?, ?, 0, ?, NOW())";

    $insertStockStmt = $conn->prepare($insertStockQuery);
    $insertStockStmt->bind_param("ids", $rawMaterialID, $quantity, $unit);

    if ($insertStockStmt->execute()) {
        $insertStockStmt->close();
        return true; // Successfully inserted
    } else {
        // Display a more informative error message
        echo '<div class="alert alert-danger" role="alert">Error inserting data into stock: ' . $insertStockStmt->error . '</div>';
        return false; // Failed to insert
    }
}

// Check if the status change request is made via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Update the status to 1 (In Stock)
    $updateStatusQuery = "UPDATE purchases SET status = 1 WHERE id = ?";
    $updateStatusStmt = $conn->prepare($updateStatusQuery);
    $updateStatusStmt->bind_param("i", $id);



    if ($updateStatusStmt->execute()) {
        // Call the function to insert data into the stock table
        // Retrieve necessary data from the purchases table
        $purchaseDataQuery = "SELECT RawMaterialID, qty_purchased, unit, p_amount FROM purchases WHERE id = ?";
        $purchaseDataStmt = $conn->prepare($purchaseDataQuery);
        $purchaseDataStmt->bind_param("i", $id);
        $purchaseDataStmt->execute();
        $purchaseDataResult = $purchaseDataStmt->get_result();

        if ($purchaseDataRow = $purchaseDataResult->fetch_assoc()) {
            // Retrieve data from the purchase record
            $rawMaterialID = $purchaseDataRow['RawMaterialID'];
            $quantity = $purchaseDataRow['qty_purchased'];
            $unit = $purchaseDataRow['unit'];
            $p_amount = $purchaseDataRow['p_amount'];

            // Call the function to insert data into the stock table
            insertStockFromPurchase($conn, $rawMaterialID, $quantity, $unit, $p_amount);

            echo "success"; // AJAX response indicating success
        } else {
            echo "error"; // AJAX response indicating error
        }
    } else {
        echo "error"; // AJAX response indicating error
    }

    // Exit script after handling AJAX request
    exit;
}
