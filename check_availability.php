<?php
include 'db_connect.php';

if (isset($_GET['raw_material_id'])) {
    $rawMaterialID = $_GET['raw_material_id'];

    // Validate that $rawMaterialID is a numeric value
    if (!is_numeric($rawMaterialID)) {
        echo "Invalid Raw Material ID.";
        exit;
    }

    // Fetch stock_in and stock_out quantities from the stock table
    $query = "SELECT SUM(stock_in) - SUM(stock_out) AS availableQuantity FROM stock WHERE RawMaterialID = ?";
    $stmt = $conn->prepare($query);

    // Check if the prepare statement succeeded
    if ($stmt === false) {
        echo "Error preparing query: " . $conn->error;
        exit;
    }

    $stmt->bind_param("i", $rawMaterialID);

    // Execute the query and check for errors
    if ($stmt->execute()) {
        $stmt->bind_result($availableQuantity);

        if ($stmt->fetch()) {
            // Convert available quantity from kg to g
            $availableQuantityInGrams = $availableQuantity * 1000;

            // Respond with the calculated available quantity in grams
            echo max(0, $availableQuantityInGrams); // Ensure the result is non-negative
        } else {
            // If the raw material is not found, you may want to handle it appropriately
            echo 0;
        }

        $stmt->close();
    } else {
        // Handle errors during query execution
        echo "Error executing query: " . $stmt->error;
    }
} else {
    // If raw_material_id is not set in the request, respond with an error or default value
    echo 0;
}

$conn->close();
