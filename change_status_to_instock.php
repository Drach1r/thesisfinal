<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the order ID from the POST request
    $orderId = isset($_POST['order_id']) ? $_POST['order_id'] : '';

    if ($orderId) {
        // Update the status in the manufacturing_orders table
        $updateQuery = "UPDATE manufacturing_orders SET status = 'In Stock' WHERE order_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('s', $orderId);

        if ($stmt->execute()) {
            // Return a success response
            echo json_encode(['success' => true]);
        } else {
            // Return an error response
            echo json_encode(['error' => 'Error updating status']);
        }

        $stmt->close();
    } else {
        // Return an error response if order ID is not provided
        echo json_encode(['error' => 'Order ID not provided']);
    }
} else {
    // Return an error response for non-POST requests
    echo json_encode(['error' => 'Invalid request method']);
}

// Close the database connection
$conn->close();
