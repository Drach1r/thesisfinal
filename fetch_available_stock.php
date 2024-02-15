<?php
include 'db_connect.php';

// Check if the product_id is set and not empty
if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
    $productID = $_POST['product_id'];

    // Fetch available stock based on stock_in and stock_out for the selected product
    $query = "SELECT COALESCE(SUM(stock_in), 0) - COALESCE(SUM(stock_out), 0) as available_stock
              FROM product_stock
              WHERE ProductID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Return available stock as JSON
    echo json_encode(['available_stock' => $row['available_stock']]);
} else {
    // If product_id is not set or empty, return an error
    echo json_encode(['error' => 'Product ID is missing.']);
}

// Close the database connection
$stmt->close();
$conn->close();
