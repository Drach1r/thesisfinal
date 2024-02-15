<?php
// Include the database connection file
include 'db_connect.php';

// Check if the product ID is provided in the POST request
if (isset($_POST['product_id'])) {
    // Sanitize the input to prevent SQL injection
    $selectedProductId = mysqli_real_escape_string($conn, $_POST['product_id']);

    // Query to fetch the available quantity for the selected product
    $query = "SELECT (stock_in - stock_out) AS available_quantity FROM product_stock WHERE ProductID = ?";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Bind the product ID parameter
        $stmt->bind_param("i", $selectedProductId);

        // Execute the query
        $stmt->execute();

        // Bind the result variables
        $stmt->bind_result($availableQuantity);

        // Fetch the result
        $stmt->fetch();

        // Close the statement
        $stmt->close();

        // Return the available quantity as JSON
        echo json_encode($availableQuantity);
    } else {
        // Return an error message if the statement preparation fails
        echo json_encode("Error preparing statement");
    }
} else {
    // Return an error message if the product ID is not provided
    echo json_encode("Product ID not provided");
}
